<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * MySQL PASSWORD() 함수로 생성된 비밀번호의 hash 값을 반환
 * 
 * MySQL 버전에 따라 결과가 다르게 나올 수 있음.
 * MySQL 8.0.11 버전 이상에서는 오류 발생(PASSWORD 함수가 제거됨)으로 사용할 수 없음.
 * 
 * @deprecated 이 함수는 안전하지 않으므로 사용하지 않는 것을 권장 함
 * @see get_encrypt_string() and check_password()
 * @param string $value
 * @return string
 */
function sql_password($value)
{
    // mysql 4.0x 이하 버전에서는 password() 함수의 결과가 16bytes
    // mysql 4.1x 이상 버전에서는 password() 함수의 결과가 41bytes
    $row = sql_fetch(" SELECT password('{$value}') as pass ");

    return $row['pass'];
}

// 문자열 암호화
function get_encrypt_string($str)
{
    if(defined('G5_STRING_ENCRYPT_FUNCTION') && G5_STRING_ENCRYPT_FUNCTION) {
        $encrypt = call_user_func(G5_STRING_ENCRYPT_FUNCTION, $str);
    } else {
        $encrypt = sql_password($str);
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
    global $g5;

    $mb_id = isset($mb['mb_id']) ? $mb['mb_id'] : '';

    if(!$mb_id)
        return false;

    if(G5_STRING_ENCRYPT_FUNCTION === 'create_hash' && (strlen($hash) === G5_MYSQL_PASSWORD_LENGTH || strlen($hash) === 16)) {
        if( sql_password($pass) === $hash ){

            if( ! isset($mb['mb_password2']) ){
                $sql = "ALTER TABLE `{$g5['member_table']}` ADD `mb_password2` varchar(255) NOT NULL default '' AFTER `mb_password`";
                sql_query($sql);
            }
            
            $new_password = create_hash($pass);
            $sql = " update {$g5['member_table']} set mb_password = '$new_password', mb_password2 = '$hash' where mb_id = '$mb_id' ";
            sql_query($sql);
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
        if(!$salt)
            $this->salt = md5(preg_replace('/[^0-9A-Za-z]/', substr(G5_MYSQL_USER, -1), $_SERVER['SERVER_SOFTWARE'].$_SERVER['DOCUMENT_ROOT']));
        else
            $this->salt = $salt;

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
