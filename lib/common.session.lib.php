<?php
if (!defined('_GNUBOARD_')) exit;

// 세션변수 생성
function set_session($session_name, $value)
{
    global $g5;

    static $check_cookie = null;
    
    if( $check_cookie === null ){
        $cookie_session_name = session_name();
        if( ! isset($g5['session_cookie_samesite']) && ! ($cookie_session_name && isset($_COOKIE[$cookie_session_name]) && $_COOKIE[$cookie_session_name]) && ! headers_sent() ){
            @session_regenerate_id(false);
        }

        $check_cookie = 1;
    }

    if (PHP_VERSION < '5.3.0')
        session_register($session_name);
    // PHP 버전별 차이를 없애기 위한 방법
    $$session_name = $_SESSION[$session_name] = $value;
}

// 세션변수값 얻음
function get_session($session_name)
{
    return isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : '';
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire, $path='/', $domain=G5_COOKIE_DOMAIN, $secure=false, $httponly=true)
{
    global $g5;
    
    $c = run_replace('set_cookie_params', array('path'=>$path, 'domain'=>$domain, 'secure'=>$secure, 'httponly'=>$httponly), $cookie_name);
    
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $c['secure'] = true;
    }

    setcookie(md5($cookie_name), base64_encode($value), G5_SERVER_TIME + $expire, $c['path'], $c['domain'], $c['secure'], $c['httponly']);
}

// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    $cookie = md5($cookie_name);
    if (array_key_exists($cookie, $_COOKIE))
        return base64_decode($_COOKIE[$cookie]);
    else
        return "";
}

// 토큰 생성
function _token()
{
    return md5(uniqid(rand(), true));
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_token()
{
    $token = md5(uniqid(rand(), true));
    set_session('ss_token', $token);

    return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_token()
{
    set_session('ss_token', '');
    return true;
}

/**
 * 브라우저 검증을 위한 세션 반환 및 재생성
 * @param array $member 로그인 된 회원의 정보. 가입일시(mb_datetime)를 반드시 포함해야 한다.
 * @param bool $regenerate true 이면 재생성
 * @return string
 */
function ss_mb_key($member, $regenerate = false)
{
    $client_key = ($regenerate) ? null : get_cookie('mb_client_key');

    if (!$client_key) {
        $client_key = get_random_token_string(16);
        set_cookie('mb_client_key', $client_key, G5_SERVER_TIME * -1);
    }

    $mb_key = md5($member['mb_datetime'] . $client_key) . run_replace('ss_mb_key_user_agent', md5($_SERVER['HTTP_USER_AGENT']));

    return $mb_key;
}

/**
 * 회원의 클라이언트 검증
 * @param array $member 로그인 된 회원의 정보. 가입일시(mb_datetime)를 반드시 포함해야 한다.
 * @return bool
 */
function verify_mb_key($member)
{
    $mb_key = ss_mb_key($member);
    $verified = get_session('ss_mb_key') === $mb_key;

    if (!$verified) {
        ss_mb_key($member, true);
    }

    return $verified;
}

/**
 * 회원의 클라이언트 검증 키 생성
 * 클라이언트 키를 다시 생성하여 생성된 키는 `ss_mb_key` 세션에 저장됨
 * @param array $member 로그인 된 회원의 정보. 가입일시(mb_datetime)를 반드시 포함해야 한다.
 */
function generate_mb_key($member)
{
    $mb_key = ss_mb_key($member, true);
    set_session('ss_mb_key', $mb_key);
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_write_token($bo_table)
{
    $token = md5(uniqid(rand(), true));
    set_session('ss_write_'.$bo_table.'_token', $token);

    return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_write_token($bo_table)
{
    if(!$bo_table)
        alert('올바른 방법으로 이용해 주십시오.', G5_URL);

    $token = get_session('ss_write_'.$bo_table.'_token');
    set_session('ss_write_'.$bo_table.'_token', '');

    if(!$token || !$_REQUEST['token'] || $token != $_REQUEST['token'])
        alert('올바른 방법으로 이용해 주십시오.', G5_URL);

    return true;
}

function check_auth_session_token($str=''){
    if (get_session('ss_mb_token_key') === get_token_encryption_key($str)) {
        return true;
    }
    return false;
}

function update_auth_session_token($str=''){
    set_session('ss_mb_token_key', get_token_encryption_key($str));
}

function get_token_encryption_key($str=''){
    $token = G5_TABLE_PREFIX.(defined('G5_SHOP_TABLE_PREFIX') ? G5_SHOP_TABLE_PREFIX : '').(defined('G5_TOKEN_ENCRYPTION_KEY') ? G5_TOKEN_ENCRYPTION_KEY : '').$str;

    return md5($token);
}

function get_random_token_string($length=6)
{
    if(function_exists('random_bytes')){
        return bin2hex(random_bytes($length));
    }

    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $output = substr(str_shuffle($characters), 0, $length);

    return bin2hex($output);
}
