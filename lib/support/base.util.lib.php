<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

/**
 * 마이크로타임을 반환
 * @return float
 * @deprecated use `microtime(true)`
 */
function get_microtime()
{
    return microtime(true);
}

// 변수 또는 배열의 이름과 값을 얻어냄. print_r() 함수의 변형
function print_r2($var)
{
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = str_replace(" ", "&nbsp;", $str);
    echo nl2br("<span>$str</span>");
}

// 리퍼러 체크
function referer_check($url = '')
{
    /*
    // 제대로 체크를 하지 못하여 주석 처리함
    global $g5;

    if (!$url)
        $url = G5_URL;

    if (!preg_match("/^http['s']?:\/\/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER']))
        alert("제대로 된 접근이 아닌것 같습니다.", $url);
    */
}

// 한글 요일
function get_yoil($date, $full = 0)
{
    $arr_yoil = array('일', '월', '화', '수', '목', '금', '토');

    $yoil = date("w", strtotime($date));
    $str = $arr_yoil[$yoil];
    if ($full) {
        $str .= '요일';
    }
    return $str;
}

// DEMO 라는 파일이 있으면 데모 화면으로 인식함
function check_demo()
{
    global $is_admin;
    if ($is_admin != 'super' && file_exists(G5_PATH . '/DEMO')) {
        alert('데모 화면에서는 하실(보실) 수 없는 작업입니다.');
    }
}

// 문자열이 한글, 영문, 숫자, 특수문자로 구성되어 있는지 검사
function check_string($str, $options)
{
    global $g5;

    $s = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $c = $str[$i];
        $oc = ord($c);

        if ($oc >= 0xA0 && $oc <= 0xFF) {
            if ($options & G5_HANGUL) {
                $s .= $c . $str[$i + 1] . $str[$i + 2];
            }
            $i += 2;
        } elseif ($oc >= 0x30 && $oc <= 0x39) {
            if ($options & G5_NUMERIC) {
                $s .= $c;
            }
        } elseif ($oc >= 0x41 && $oc <= 0x5A) {
            if (($options & G5_ALPHABETIC) || ($options & G5_ALPHAUPPER)) {
                $s .= $c;
            }
        } elseif ($oc >= 0x61 && $oc <= 0x7A) {
            if (($options & G5_ALPHABETIC) || ($options & G5_ALPHALOWER)) {
                $s .= $c;
            }
        } elseif ($oc == 0x20) {
            if ($options & G5_SPACE) {
                $s .= $c;
            }
        } else {
            if ($options & G5_SPECIAL) {
                $s .= $c;
            }
        }
    }

    return ($str == $s);
}

// 한글(2bytes)에서 마지막 글자가 1byte로 끝나는 경우
function cut_hangul_last($hangul)
{
    global $g5;

    $cnt = 0;
    for ($i = 0; $i < strlen($hangul); $i++) {
        if (ord($hangul[$i]) >= 0xA0) {
            $cnt++;
        }
    }

    return $hangul;
}

function is_checked($field)
{
    return !empty($_POST[$field]);
}

function abs_ip2long($ip = '')
{
    $ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
    return abs(ip2long($ip));
}

function get_selected($field, $value)
{
    if (is_int($value)) {
        return ((int) $field === $value) ? ' selected="selected"' : '';
    }

    return ($field === $value) ? ' selected="selected"' : '';
}

function get_checked($field, $value)
{
    if (is_int($value)) {
        return ((int) $field === $value) ? ' checked="checked"' : '';
    }

    return ($field === $value) ? ' checked="checked"' : '';
}

function get_uniqid()
{
    global $g5;

    if ($get_uniqid_key = run_replace('get_uniqid_key', '')) {
        return $get_uniqid_key;
    }

    if (!sql_lock_tables_write($g5['uniqid_table'])) {
        return '';
    }
    while (1) {
        $key = date('YmdHis', time()) . str_pad((int) ((float) microtime() * 100), 2, "0", STR_PAD_LEFT);

        $result = sql_query_prepared(" insert into {$g5['uniqid_table']} set uq_id = :uq_id, uq_ip = :uq_ip ", array(
            'uq_id' => $key,
            'uq_ip' => $_SERVER['REMOTE_ADDR'],
        ), false);
        if ($result) {
            break;
        }

        usleep(10000);
    }
    sql_unlock_tables();

    return $key;
}

function hyphen_hp_number($hp)
{
    $hp = preg_replace("/[^0-9]/", "", $hp);
    return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $hp);
}

function conv_date_format($format, $date, $add = '')
{
    if ($add) {
        $timestamp = strtotime($add, strtotime($date));
    } else {
        $timestamp = strtotime($date);
    }

    return date($format, $timestamp);
}

function safe_replace_regex($str, $str_case = '')
{
    if ($str_case === 'time') {
        return preg_replace('/[^0-9 _\-:]/i', '', $str);
    }

    return preg_replace('/[^0-9a-z_\-]/i', '', $str);
}

function get_call_func_cache($func, $args = array())
{
    static $cache = array();

    $key = md5(serialize($args));

    if (isset($cache[$func]) && isset($cache[$func][$key])) {
        return $cache[$func][$key];
    }

    $result = null;

    try {
        $cache[$func][$key] = $result = call_user_func_array($func, $args);
    } catch (Exception $e) {
        return null;
    }

    return $result;
}
