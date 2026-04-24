<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_LIB_PATH.'/pbkdf2.compat.php');

function mysql_password_hash_41($value)
{
    return '*' . strtoupper(sha1(sha1((string) $value, true)));
}

function mysql_old_password_hash_323($value)
{
    $nr = 1345345333;
    $add = 7;
    $nr2 = 0x12345671;
    $value = (string) preg_replace('/[\x20\t]+/', '', (string) $value);
    $length = strlen($value);

    for ($i = 0; $i < $length; $i++) {
        $tmp = ord($value[$i]);
        $nr ^= ((($nr & 63) + $add) * $tmp) + ($nr << 8);
        $nr &= 0xFFFFFFFF;
        $nr2 += ($nr2 << 8) ^ $nr;
        $nr2 &= 0xFFFFFFFF;
        $add += $tmp;
    }

    return sprintf('%08x%08x', $nr & 0x7FFFFFFF, $nr2 & 0x7FFFFFFF);
}

function mysql_legacy_password_hash($value, $hash_length = G5_MYSQL_PASSWORD_LENGTH)
{
    if ((int) $hash_length === 16) {
        return mysql_old_password_hash_323($value);
    }

    return mysql_password_hash_41($value);
}

function sql_password($value)
{
    return mysql_legacy_password_hash($value, G5_MYSQL_PASSWORD_LENGTH);
}

// 문자열 암호화
function get_encrypt_string($str)
{
    if(defined('G5_STRING_ENCRYPT_FUNCTION') && G5_STRING_ENCRYPT_FUNCTION) {
        $encrypt = call_user_func(G5_STRING_ENCRYPT_FUNCTION, $str);
    } else {
        $encrypt = create_hash($str);
    }

    return $encrypt;
}

// 비밀번호 비교
function check_password($pass, $hash)
{
    if(defined('G5_STRING_ENCRYPT_FUNCTION') && G5_STRING_ENCRYPT_FUNCTION === 'create_hash') {
        return validate_password($pass, $hash);
    }

    $password = get_encrypt_string($pass);

    return ($password === $hash);
}

// 로그인 패스워드 체크
function login_password_check($mb, $pass, $hash)
{
    $mb_id = isset($mb['mb_id']) ? $mb['mb_id'] : '';
    $member_table = function_exists('g5_get_runtime_table_name') ? g5_get_runtime_table_name('member_table') : '';

    if(!$mb_id)
        return false;

    if(G5_STRING_ENCRYPT_FUNCTION === 'create_hash' && (strlen($hash) === G5_MYSQL_PASSWORD_LENGTH || strlen($hash) === 16)) {
        if (mysql_legacy_password_hash($pass, strlen($hash)) === $hash) {
            $new_password = create_hash($pass);
            $sql = " update {$member_table} set mb_password = :mb_password where mb_id = :mb_id ";
            sql_query_prepared($sql, array(
                'mb_password' => $new_password,
                'mb_id' => $mb_id,
            ));
            return true;
        }
    }

    return check_password($pass, $hash);
}

// 문자열 암복호화
class str_encrypt
{
    var $salt;
    var $length;

    function __construct($salt='')
    {
        global $config;

        if (!$salt) {
            $config_hash = md5(serialize(array($config['cf_title'], $config['cf_admin_email_name'])));

            $this->salt = hash('sha256', preg_replace('/[^0-9A-Za-z]/', substr($config_hash, -1), $_SERVER['SERVER_SOFTWARE'].$config_hash.$_SERVER['DOCUMENT_ROOT']));
        } else {
            $this->salt = $salt;
        }

        $this->length = strlen($this->salt);
    }

    function encrypt($str)
    {
        $length = strlen($str);
        $result = '';

        for($i=0; $i<$length; $i++) {
            $char    = substr($str, $i, 1);
            $keychar = substr($this->salt, ($i % $this->length) - 1, 1);
            $char    = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return strtr(base64_encode($result) , '+/=', '._-');
    }

    function decrypt($str) {
        $result = '';
        $str    = base64_decode(strtr($str, '._-', '+/='));
        $length = strlen($str);

        for($i=0; $i<$length; $i++) {
            $char    = substr($str, $i, 1);
            $keychar = substr($this->salt, ($i % $this->length) - 1, 1);
            $char    = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }
}
