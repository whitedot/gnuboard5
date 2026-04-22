<?php
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!defined('G5_SET_TIME_LIMIT')) define('G5_SET_TIME_LIMIT', 0);
@set_time_limit(G5_SET_TIME_LIMIT);

if( version_compare( PHP_VERSION, '5.2.17' , '<' ) ){
    die(sprintf('PHP 5.2.17 or higher required. Your PHP version is %s', PHP_VERSION));
}

//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================

// Cloudflare 사용시 REMOTE_ADDR 에 사용자 IP 적용과 https 사용 여부
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    include_once('cloudflare.check.php');    // cloudflare 의 ip 대역인지 체크
}

function g5_path()
{
    $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__))); 
    $result['path'] = str_replace('\\', '/', $chroot.dirname(__FILE__)); 
    $server_script_name = preg_replace('/\/+/', '/', str_replace('\\', '/', $_SERVER['SCRIPT_NAME'])); 
    $server_script_filename = preg_replace('/\/+/', '/', str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'])); 
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $server_script_name); 
    $document_root = str_replace($tilde_remove, '', $server_script_filename); 
    $pattern = '/.*?' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']); 
    $port = ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? '' : ':'.$_SERVER['SERVER_PORT']; 
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://'; 
    $user = str_replace(preg_replace($pattern, '', $server_script_filename), '', $server_script_name); 
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']; 
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host)) 
        $host = preg_replace('/:[0-9]+$/', '', $host); 
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host); 
    $result['url'] = $http.$host.$port.$user.$root; 
    return $result;
}

$g5_path = g5_path();

include_once($g5_path['path'].'/config.php');   // 설정 파일

unset($g5_path);

include_once(G5_LIB_PATH.'/bootstrap/core.lib.php');
include_once(G5_LIB_PATH.'/bootstrap/runtime.lib.php');
include_once(G5_LIB_PATH.'/bootstrap/auth.lib.php');
include_once(G5_LIB_PATH.'/bootstrap/session.lib.php');

// IIS 에서 SERVER_ADDR 서버변수가 없다면
if (!isset($_SERVER['SERVER_ADDR'])) {
    $_SERVER['SERVER_ADDR'] = isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : '';
}


//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
if (7.0 > (float)phpversion()) {
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $_POST    = array_map_deep('stripslashes',  $_POST);
        $_GET     = array_map_deep('stripslashes',  $_GET);
        $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
        $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
    }
}

// sql_escape_string 적용
$_POST    = array_map_deep(G5_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(G5_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(G5_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(G5_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================


g5_extract_request_globals();


// 완두콩님이 알려주신 보안관련 오류 수정
// $member 에 값을 직접 넘길 수 있음

g5_initialize_runtime_globals();
g5_include_bootstrap_libraries();

$g5_object = new G5_object_cache();

//==============================================================================
// 공통
//------------------------------------------------------------------------------
g5_bootstrap_database();
//==============================================================================


//==============================================================================
// SESSION 설정
//------------------------------------------------------------------------------
g5_configure_session_environment();

// 세션파일 저장 디렉토리를 지정할 경우
// session_save_path(G5_SESSION_PATH);
g5_apply_chrome_session_name_workaround();

g5_handle_special_post_session_recovery();

//==============================================================================
// 공용 변수
//------------------------------------------------------------------------------
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config = get_config(true);

g5_start_session_with_samesite_support();
//==============================================================================

define('G5_HTTP_MEMBER_URL',  https_url(G5_MEMBER_DIR, false));
define('G5_HTTPS_MEMBER_URL', https_url(G5_MEMBER_DIR, true));

define('G5_CAPTCHA_DIR',    !empty($config['cf_captcha']) ? $config['cf_captcha'] : 'kcaptcha');
define('G5_CAPTCHA_URL',    G5_PLUGIN_URL.'/'.G5_CAPTCHA_DIR);
define('G5_CAPTCHA_PATH',   G5_PLUGIN_PATH.'/'.G5_CAPTCHA_DIR);

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다. php5.2 버전 이하에서만 해당되는 코드이며, 오히려 무한리다이렉트 오류가 일어날수 있으므로 주석처리합니다.
// if( method_exists('XenoPostToForm', 'php52_request_check') ) XenoPostToForm::php52_request_check();

$request_state = g5_build_query_state();
$qstr = $request_state['qstr'];
$sca = $request_state['sca'];
$sfl = $request_state['sfl'];
$stx = $request_state['stx'];
$sst = $request_state['sst'];
$sod = $request_state['sod'];
$sop = $request_state['sop'];
$spt = $request_state['spt'];
$page = $request_state['page'];
$w = $request_state['w'];
$url = $request_state['url'];
$urlencode = $request_state['urlencode'];
$gr_id = $request_state['gr_id'];
//===================================

$restored_member = g5_restore_logged_in_member();
if ($restored_member !== null) {
    $member = $restored_member;
} else {
    g5_try_restore_auto_login();
}

g5_enforce_ip_access_policy($member, $config);

/** @var array $write 필터용 데이터 */
$write = array();

$member_state = g5_resolve_member_state($member);
$member = $member_state['member'];
$is_member = $member_state['is_member'];
$is_guest = $member_state['is_guest'];
$is_admin = $member_state['is_admin'];

g5_apply_theme_constants();

g5_normalize_cert_vendor_config();

//==============================================================================
// Mobile 모바일 설정
// 회원 전용 앱 셸에서는 device 파라미터 또는 세션값으로 기기 모드를 강제할 수 있습니다.
//------------------------------------------------------------------------------
$is_mobile = g5_resolve_mobile_state();
define('G5_IS_MOBILE', $is_mobile);
//==============================================================================


//==============================================================================
// 스킨경로
//------------------------------------------------------------------------------
    $member_skin_path = G5_SKIN_PATH.'/member/basic';
//==============================================================================

if (!defined('KGINICIS_USE_CERT_SEED')) {
    define('KGINICIS_USE_CERT_SEED', isset($config['cf_cert_use_seed']) ? (int) $config['cf_cert_use_seed'] : 1);
}

if($is_member && !$is_admin && (!defined("G5_CERT_IN_PROG") || !G5_CERT_IN_PROG) && $config['cf_cert_use'] <> 0 && $config['cf_cert_req']) { // 본인인증이 필수일때
    if ((empty($member['mb_certify']) || (!empty($member['mb_certify']) && strlen($member['mb_dupinfo']) == 64))) { // di로 인증되어 있거나 본인인증이 안된 계정일때
        goto_url(G5_MEMBER_URL."/member_cert_refresh.php");
    }
}

ob_start();

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

run_event('common_header');
