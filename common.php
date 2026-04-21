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

// IIS 에서 SERVER_ADDR 서버변수가 없다면
if (!isset($_SERVER['SERVER_ADDR'])) {
    $_SERVER['SERVER_ADDR'] = isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : '';
}

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}


// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('G5_ESCAPE_PATTERN') && defined('G5_ESCAPE_REPLACE')) {
        $pattern = G5_ESCAPE_PATTERN;
        $replace = G5_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}

function g5_extract_request_globals()
{
    // PHP 4.1.0 부터 지원됨
    // php.ini 의 register_globals=off 일 경우
    @extract($_GET);
    @extract($_POST);
    @extract($_SERVER);
}

function g5_initialize_runtime_globals()
{
    global $config, $member, $g5, $g5_debug;

    $config = array();
    $member = array(
        'mb_id' => '',
        'mb_level' => 1,
        'mb_name' => '',
        'mb_certify' => '',
        'mb_email' => '',
        'mb_open' => '',
        'mb_hp' => ''
    );
    $g5 = array();

    if (version_compare(phpversion(), '8.0.0', '>=')) {
        $g5 = array('title' => '');
    }

    $g5_debug = array('php' => array(), 'sql' => array());
}

function g5_include_bootstrap_libraries()
{
    include_once(G5_LIB_PATH.'/hook.lib.php');
    include_once(G5_LIB_PATH.'/get_data.lib.php');
    include_once(G5_LIB_PATH.'/cache.lib.php');
    include_once(G5_LIB_PATH.'/uri.lib.php');
}

function g5_include_core_libraries()
{
    include_once(G5_LIB_PATH.'/common.lib.php');
    include_once(G5_LIB_PATH.'/member.render.lib.php');
    include_once(G5_LIB_PATH.'/member.flow.lib.php');
    include_once(G5_LIB_PATH.'/member.page.lib.php');
}

function g5_render_install_required()
{
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>오류! <?php echo G5_VERSION ?> 설치하기</title>
<link rel="stylesheet" href="install/install.css">
</head>
<body>

<div id="ins_bar">
    <span id="bar_img">GNUBOARD5</span>
    <span id="bar_txt">Message</span>
</div>
<h1>그누보드5를 먼저 설치해주십시오.</h1>
<div class="ins_inner">
    <p>다음 파일을 찾을 수 없습니다.</p>
    <ul>
        <li><strong><?php echo G5_DATA_DIR.'/'.G5_DBCONFIG_FILE ?></strong></li>
    </ul>
    <p>그누보드 설치 후 다시 실행하시기 바랍니다.</p>
    <div class="inner_btn">
        <a href="<?php echo G5_URL; ?>/install/"><?php echo G5_VERSION ?> 설치하기</a>
    </div>
</div>
<div id="ins_ft">
    <strong>GNUBOARD5</strong>
    <p>GPL! OPEN SOURCE GNUBOARD</p>
</div>

</body>
</html>

<?php
    exit;
}

function g5_bootstrap_database()
{
    global $g5;

    $dbconfig_file = G5_DATA_PATH.'/'.G5_DBCONFIG_FILE;
    if (!file_exists($dbconfig_file)) {
        g5_render_install_required();
    }

    include_once($dbconfig_file);
    g5_include_core_libraries();

    $connect_db = sql_connect(G5_MYSQL_HOST, G5_MYSQL_USER, G5_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(G5_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    $g5['connect_db'] = $connect_db;

    sql_set_charset(G5_DB_CHARSET, $connect_db);
    if (defined('G5_MYSQL_SET_MODE') && G5_MYSQL_SET_MODE) {
        sql_reset_session_sql_mode($connect_db);
    }
    if (defined('G5_TIMEZONE')) {
        sql_set_time_zone(G5_TIMEZONE);
    }

    return $select_db;
}

function g5_build_query_state()
{
    $state = array(
        'qstr' => '',
        'sca' => '',
        'sfl' => '',
        'stx' => '',
        'sst' => '',
        'sod' => '',
        'sop' => '',
        'spt' => '',
        'page' => '',
        'w' => '',
        'url' => '',
        'urlencode' => urlencode($_SERVER['REQUEST_URI']),
        'gr_id' => '',
    );

    if (isset($_REQUEST['sca'])) {
        $state['sca'] = clean_xss_tags(trim($_REQUEST['sca']));
        if ($state['sca']) {
            $state['sca'] = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $state['sca']);
            $state['qstr'] .= '&amp;sca=' . urlencode($state['sca']);
        }
    }

    if (isset($_REQUEST['sfl'])) {
        $state['sfl'] = trim($_REQUEST['sfl']);
        $state['sfl'] = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s\#]/", "", $state['sfl']);
        if ($state['sfl']) {
            $state['qstr'] .= '&amp;sfl=' . urlencode($state['sfl']);
        }
    }

    if (isset($_REQUEST['stx'])) {
        $state['stx'] = get_search_string(trim($_REQUEST['stx']));
        if ($state['stx'] || $state['stx'] === '0') {
            $state['qstr'] .= '&amp;stx=' . urlencode(cut_str($state['stx'], 20, ''));
        }
    }

    if (isset($_REQUEST['sst'])) {
        $state['sst'] = trim($_REQUEST['sst']);
        $state['sst'] = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $state['sst']);
        if ($state['sst']) {
            $state['qstr'] .= '&amp;sst=' . urlencode($state['sst']);
        }
    }

    if (isset($_REQUEST['sod'])) {
        $request_sod = isset($_REQUEST['sod']) ? (string) $_REQUEST['sod'] : '';
        $state['sod'] = preg_match("/^(asc|desc)$/i", $request_sod) ? $request_sod : '';
        if ($state['sod']) {
            $state['qstr'] .= '&amp;sod=' . urlencode($state['sod']);
        }
    }

    if (isset($_REQUEST['sop'])) {
        $request_sop = isset($_REQUEST['sop']) ? (string) $_REQUEST['sop'] : '';
        $state['sop'] = preg_match("/^(or|and)$/i", $request_sop) ? $request_sop : '';
        if ($state['sop']) {
            $state['qstr'] .= '&amp;sop=' . urlencode($state['sop']);
        }
    }

    if (isset($_REQUEST['spt'])) {
        $state['spt'] = (int) $_REQUEST['spt'];
        if ($state['spt']) {
            $state['qstr'] .= '&amp;spt=' . urlencode($state['spt']);
        }
    }

    if (isset($_REQUEST['page'])) {
        $state['page'] = (int) $_REQUEST['page'];
        if ($state['page']) {
            $state['qstr'] .= '&amp;page=' . urlencode($state['page']);
        }
    }

    if (isset($_REQUEST['w'])) {
        $request_w = isset($_REQUEST['w']) ? (string) $_REQUEST['w'] : '';
        $state['w'] = substr($request_w, 0, 2);
    }

    if (isset($_REQUEST['url'])) {
        $state['url'] = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', trim($_REQUEST['url']));
        $state['urlencode'] = urlencode($state['url']);
    } elseif (G5_DOMAIN) {
        $parsed_domain = @parse_url(G5_DOMAIN);
        $parsed_domain['path'] = isset($parsed_domain['path']) ? $parsed_domain['path'] : '/';
        $state['urlencode'] = rtrim(G5_DOMAIN, '%2F').'%2F'.ltrim(urldecode(preg_replace("/^".urlencode($parsed_domain['path'])."/", "", $state['urlencode'])), '%2F');
    }

    if (isset($_REQUEST['gr_id']) && !is_array($_REQUEST['gr_id'])) {
        $state['gr_id'] = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['gr_id']));
    }

    return $state;
}

function g5_refresh_member_login(array $member)
{
    global $g5;

    if (substr($member['mb_today_login'], 0, 10) == G5_TIME_YMD) {
        return;
    }

    sql_query_prepared(" update {$g5['member_table']} set mb_today_login = :mb_today_login, mb_login_ip = :mb_login_ip where mb_id = :mb_id ", array(
        'mb_today_login' => G5_TIME_YMDHIS,
        'mb_login_ip' => $_SERVER['REMOTE_ADDR'],
        'mb_id' => $member['mb_id'],
    ));
}

function g5_restore_logged_in_member()
{
    $session_mb_id = isset($_SESSION['ss_mb_id']) ? $_SESSION['ss_mb_id'] : '';
    if (!$session_mb_id) {
        return null;
    }

    $member = get_member($session_mb_id);
    $is_invalid_member =
        ($member['mb_intercept_date'] && $member['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) ||
        ($member['mb_leave_date'] && $member['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) ||
        (function_exists('check_auth_session_token') && !check_auth_session_token($member['mb_datetime']));

    if ($is_invalid_member) {
        set_session('ss_mb_id', '');
        return array();
    }

    g5_refresh_member_login($member);

    return $member;
}

function g5_try_restore_auto_login()
{
    global $config, $g5;

    $tmp_mb_id = get_cookie('ck_mb_id');
    if (!$tmp_mb_id) {
        return false;
    }

    $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
    if (strtolower($tmp_mb_id) === strtolower($config['cf_admin'])) {
        return false;
    }

    $sql = " select mb_password, mb_intercept_date, mb_leave_date, mb_email_certify, mb_datetime from {$g5['member_table']} where mb_id = :mb_id ";
    $row = sql_fetch_prepared($sql, array(
        'mb_id' => $tmp_mb_id,
    ));

    if (empty($row['mb_password'])) {
        return false;
    }

    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $row['mb_password']);
    $tmp_key = get_cookie('ck_auto');
    if ($tmp_key !== $key || !$tmp_key) {
        return false;
    }

    if ($row['mb_intercept_date'] != '' || $row['mb_leave_date'] != '') {
        return false;
    }

    if ($config['cf_use_email_certify'] && !preg_match('/[1-9]/', $row['mb_email_certify'])) {
        return false;
    }

    set_session('ss_mb_id', $tmp_mb_id);
    if (function_exists('update_auth_session_token')) {
        update_auth_session_token($row['mb_datetime']);
    }

    echo "<script type='text/javascript'> window.location.reload(); </script>";
    exit;
}

function g5_match_ip_pattern_list($patterns_text, $ip)
{
    $patterns = explode("\n", trim((string) $patterns_text));

    for ($i = 0; $i < count($patterns); $i++) {
        $pattern = trim($patterns[$i]);
        if ($pattern === '') {
            continue;
        }

        $pattern = str_replace(".", "\.", $pattern);
        $pattern = str_replace("+", "[0-9\.]+", $pattern);
        if (preg_match("/^{$pattern}$/", $ip)) {
            return true;
        }
    }

    return false;
}

function g5_enforce_ip_access_policy(array $member, array $config)
{
    if (isset($member['mb_id']) && $config['cf_admin'] === $member['mb_id']) {
        return;
    }

    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $cf_possible_ip = trim($config['cf_possible_ip']);
    if ($cf_possible_ip && !g5_match_ip_pattern_list($cf_possible_ip, $remote_addr)) {
        die("<meta charset=utf-8>접근이 가능하지 않습니다.");
    }

    if (g5_match_ip_pattern_list($config['cf_intercept_ip'], $remote_addr)) {
        die("<meta charset=utf-8>접근 불가합니다.");
    }
}

function g5_resolve_member_state(array $member)
{
    $state = array(
        'member' => $member,
        'is_member' => false,
        'is_guest' => false,
        'is_admin' => '',
    );

    if (isset($member['mb_id']) && $member['mb_id']) {
        $state['is_member'] = true;
        $state['is_admin'] = is_admin($member['mb_id']);
        $state['member']['mb_dir'] = substr($member['mb_id'], 0, 2);
    } else {
        $state['is_guest'] = true;
        $state['member']['mb_id'] = '';
        $state['member']['mb_level'] = 1;
    }

    return $state;
}

function g5_apply_theme_constants()
{
    $theme_path = G5_PATH.'/'.G5_THEME_DIR;
    if (!is_dir($theme_path)) {
        return;
    }

    define('G5_THEME_PATH',        $theme_path);
    define('G5_THEME_URL',         G5_URL.'/'.G5_THEME_DIR);
    define('G5_THEME_CSS_URL',     G5_THEME_URL.'/'.G5_CSS_DIR);
    define('G5_THEME_CSS_PATH',    G5_THEME_PATH.'/'.G5_CSS_DIR);
    define('G5_THEME_IMG_URL',     G5_THEME_URL.'/'.G5_IMG_DIR);
    define('G5_THEME_IMG_PATH',    G5_THEME_PATH.'/'.G5_IMG_DIR);
    define('G5_THEME_JS_URL',      G5_THEME_URL.'/'.G5_JS_DIR);
    define('G5_THEME_JS_PATH',     G5_THEME_PATH.'/'.G5_JS_DIR);
}

function g5_normalize_cert_vendor_config()
{
    global $config;

    if (isset($config['cf_cert_ipin']) && $config['cf_cert_ipin'] === 'kcb') {
        $config['cf_cert_ipin'] = '';
    }
    if (isset($config['cf_cert_hp']) && $config['cf_cert_hp'] === 'kcb') {
        $config['cf_cert_hp'] = '';
    }
    if (isset($config['cf_cert_hp']) && $config['cf_cert_hp'] === 'lg') {
        $config['cf_cert_hp'] = '';
    }
}

function g5_resolve_mobile_state()
{
    $is_mobile = false;

    if (G5_USE_MOBILE) {
        if (isset($_REQUEST['device']) && $_REQUEST['device'] === 'pc') {
            $is_mobile = false;
        } elseif (isset($_REQUEST['device']) && $_REQUEST['device'] === 'mobile') {
            $is_mobile = true;
        } elseif (isset($_SESSION['ss_is_mobile'])) {
            $is_mobile = (bool) $_SESSION['ss_is_mobile'];
        } elseif (is_mobile()) {
            $is_mobile = true;
        }
    }

    $_SESSION['ss_is_mobile'] = $is_mobile;

    return $is_mobile;
}

function g5_configure_session_environment()
{
    @ini_set("session.use_trans_sid", 0);
    @ini_set("url_rewriter.tags", "");

    if (isset($GLOBALS['SESSION_CACHE_LIMITER'])) {
        @session_cache_limiter($GLOBALS['SESSION_CACHE_LIMITER']);
    } else {
        @session_cache_limiter("no-cache, must-revalidate");
    }

    ini_set("session.cache_expire", 180);
    ini_set("session.gc_maxlifetime", 10800);
    ini_set("session.gc_probability", 1);
    ini_set("session.gc_divisor", 100);

    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        session_set_cookie_params(0, '/', null, true, true);
    } else {
        session_set_cookie_params(0, '/', null, false, true);
    }

    ini_set("session.cookie_domain", G5_COOKIE_DOMAIN);
}

function g5_apply_chrome_session_name_workaround()
{
    $domain_array = array(
        '.cafe24.com',
        '.dothome.co.kr',
        '.phps.kr',
        '.maru.net',
    );

    $add_str = '';
    $document_root_path = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));

    if (G5_PATH !== $document_root_path) {
        $add_str = substr_count(G5_PATH, '/').basename(dirname(__FILE__));
    }

    if ($add_str || (isset($_SERVER['HTTP_HOST']) && preg_match('/('.implode('|', $domain_array).')/i', $_SERVER['HTTP_HOST']))) {
        if (!defined('G5_SESSION_NAME')) {
            define('G5_SESSION_NAME', 'G5'.$add_str.'PHPSESSID');
        }
        @session_name(G5_SESSION_NAME);
    }
}

function g5_handle_special_post_session_recovery()
{
    if (!XenoPostToForm::check()) {
        return;
    }

    if (check_special_post_return_page()) {
        XenoPostToForm::submit($_POST);
    }
}

function g5_start_session_with_samesite_support()
{
    global $config;

    if (!function_exists('session_start_samesite')) {
        function session_start_samesite($options = array())
        {
            global $g5;

            $res = @session_start($options);

            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                if (preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT'])
                    || preg_match('/(iPhone|iPod|iPad).*AppleWebKit.*Safari/i', $_SERVER['HTTP_USER_AGENT'])
                    || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'])
                    || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT'])
                    || !(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')) {
                    return $res;
                }
            }

            $headers = headers_list();
            krsort($headers);
            $cookie_session_name = method_exists('XenoPostToForm', 'g5_session_name') ? XenoPostToForm::g5_session_name() : 'PHPSESSID';
            foreach ($headers as $header) {
                if (!preg_match('~^Set-Cookie: '.$cookie_session_name.'=~', $header)) {
                    continue;
                }
                $header = preg_replace('~(; secure; HttpOnly)?$~', '; secure; HttpOnly; SameSite=None', $header);
                header($header, false);
                $g5['session_cookie_samesite'] = 'none';
                break;
            }
            return $res;
        }
    }

    if ($config['cf_cert_use']) {
        session_start_samesite();
    } else {
        @session_start();
    }
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

if( ! class_exists('XenoPostToForm') ){
    class XenoPostToForm
    {
        public static function g5_session_name(){
            return (defined('G5_SESSION_NAME') && G5_SESSION_NAME) ? G5_SESSION_NAME : 'PHPSESSID';
        }

        public static function php52_request_check(){
            $cookie_session_name = self::g5_session_name();
            if (isset($_REQUEST[$cookie_session_name]) && $_REQUEST[$cookie_session_name] != session_id())
                goto_url(G5_MEMBER_URL.'/logout.php');
        }

        public static function check() {
            $cookie_session_name = self::g5_session_name(); 

            return !isset($_COOKIE[$cookie_session_name]) && count($_POST) && ((isset($_SERVER['HTTP_REFERER']) && !preg_match('~^https://'.preg_quote($_SERVER['HTTP_HOST'], '~').'/~', $_SERVER['HTTP_REFERER']) || ! isset($_SERVER['HTTP_REFERER']) ));
        }

        public static function submit($posts) {
            echo '<html><head><meta charset="UTF-8"></head><body>';
            echo '<form id="f" name="f" method="post">';
            echo self::makeInputArray($posts);
            echo '</form>';
            echo '<script>';
            echo 'document.f.submit();';
            echo '</script></body></html>';
            exit;
        }

        public static function makeInputArray($posts) {
            $res = array();
            foreach($posts as $k => $v) {
                $res[] = self::makeInputArray_($k, $v);
            }
            return implode('', $res);
        }

        private static function makeInputArray_($k, $v) {
            if(is_array($v)) {
                $res = array();
                foreach($v as $i => $j) {
                    $res[] = self::makeInputArray_($k.'['.htmlspecialchars($i).']', $j);
                }
                return implode('', $res);
            }
            return '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v).'" />';
        }
    }
}

if( !function_exists('check_special_post_return_page') ){
    function check_special_post_return_page(){
        $pg_checks_pages = array(
            'plugin/inicert/ini_result.php',
            'plugin/inicert/ini_find_result.php',
        );

        $server_script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);

        foreach( $pg_checks_pages as $pg_page ){
            if( preg_match('~'.preg_quote($pg_page).'$~i', $server_script_name) ){
                return true;
            }
        }

        return false;
    }
}

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
