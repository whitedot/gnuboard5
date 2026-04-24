<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function g5_read_request_scalar(array $source, $key, $default = '')
{
    if (!isset($source[$key]) || is_array($source[$key])) {
        return $default;
    }

    return $source[$key];
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
        'mb_hp' => '',
    );
    $g5 = array();

    if (version_compare(phpversion(), '8.0.0', '>=')) {
        $g5 = array('title' => '');
    }

    $g5_debug = array('php' => array(), 'sql' => array());
}

function g5_build_query_state(array $source, $request_uri = '')
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
        'urlencode' => urlencode((string) $request_uri),
        'gr_id' => '',
    );

    if (isset($source['sca']) && !is_array($source['sca'])) {
        $state['sca'] = clean_xss_tags(trim((string) $source['sca']));
        if ($state['sca']) {
            $state['sca'] = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $state['sca']);
            $state['qstr'] .= '&amp;sca=' . urlencode($state['sca']);
        }
    }

    if (isset($source['sfl']) && !is_array($source['sfl'])) {
        $state['sfl'] = trim((string) $source['sfl']);
        $state['sfl'] = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s\#]/", "", $state['sfl']);
        if ($state['sfl']) {
            $state['qstr'] .= '&amp;sfl=' . urlencode($state['sfl']);
        }
    }

    if (isset($source['stx']) && !is_array($source['stx'])) {
        $state['stx'] = get_search_string(trim((string) $source['stx']));
        if ($state['stx'] || $state['stx'] === '0') {
            $state['qstr'] .= '&amp;stx=' . urlencode(cut_str($state['stx'], 20, ''));
        }
    }

    if (isset($source['sst']) && !is_array($source['sst'])) {
        $state['sst'] = trim((string) $source['sst']);
        $state['sst'] = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $state['sst']);
        if ($state['sst']) {
            $state['qstr'] .= '&amp;sst=' . urlencode($state['sst']);
        }
    }

    if (isset($source['sod']) && !is_array($source['sod'])) {
        $request_sod = (string) $source['sod'];
        $state['sod'] = preg_match("/^(asc|desc)$/i", $request_sod) ? $request_sod : '';
        if ($state['sod']) {
            $state['qstr'] .= '&amp;sod=' . urlencode($state['sod']);
        }
    }

    if (isset($source['sop']) && !is_array($source['sop'])) {
        $request_sop = (string) $source['sop'];
        $state['sop'] = preg_match("/^(or|and)$/i", $request_sop) ? $request_sop : '';
        if ($state['sop']) {
            $state['qstr'] .= '&amp;sop=' . urlencode($state['sop']);
        }
    }

    if (isset($source['spt']) && !is_array($source['spt'])) {
        $state['spt'] = (int) $source['spt'];
        if ($state['spt']) {
            $state['qstr'] .= '&amp;spt=' . urlencode($state['spt']);
        }
    }

    if (isset($source['page']) && !is_array($source['page'])) {
        $state['page'] = (int) $source['page'];
        if ($state['page']) {
            $state['qstr'] .= '&amp;page=' . urlencode($state['page']);
        }
    }

    if (isset($source['w']) && !is_array($source['w'])) {
        $request_w = (string) $source['w'];
        $state['w'] = substr($request_w, 0, 2);
    }

    if (isset($source['url']) && !is_array($source['url'])) {
        $state['url'] = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', trim((string) $source['url']));
        $state['urlencode'] = urlencode($state['url']);
    } elseif (G5_DOMAIN) {
        $parsed_domain = @parse_url(G5_DOMAIN);
        $parsed_domain['path'] = isset($parsed_domain['path']) ? $parsed_domain['path'] : '/';
        $state['urlencode'] = rtrim(G5_DOMAIN, '%2F') . '%2F' . ltrim(urldecode(preg_replace("/^" . urlencode($parsed_domain['path']) . "/", "", $state['urlencode'])), '%2F');
    }

    if (isset($source['gr_id']) && !is_array($source['gr_id'])) {
        $state['gr_id'] = preg_replace('/[^a-z0-9_]/i', '', trim((string) $source['gr_id']));
    }

    return $state;
}

function g5_build_runtime_request_context(array $source)
{
    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';

    return array(
        'request' => $source,
        'query_state' => g5_build_query_state($source, $request_uri),
        'token' => g5_read_request_scalar($source, 'token', ''),
    );
}

function g5_get_runtime_request_context()
{
    global $g5;

    if (isset($g5['request_context']) && is_array($g5['request_context'])) {
        return $g5['request_context'];
    }

    return g5_build_runtime_request_context($_REQUEST);
}

function g5_get_runtime_query_state()
{
    $request_context = g5_get_runtime_request_context();

    return isset($request_context['query_state']) && is_array($request_context['query_state'])
        ? $request_context['query_state']
        : array();
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
    $theme_path = G5_PATH . '/' . G5_THEME_DIR;
    if (!is_dir($theme_path)) {
        return;
    }

    define('G5_THEME_PATH', $theme_path);
    define('G5_THEME_URL', G5_URL . '/' . G5_THEME_DIR);
    define('G5_THEME_CSS_URL', G5_THEME_URL . '/' . G5_CSS_DIR);
    define('G5_THEME_CSS_PATH', G5_THEME_PATH . '/' . G5_CSS_DIR);
    define('G5_THEME_IMG_URL', G5_THEME_URL . '/' . G5_IMG_DIR);
    define('G5_THEME_IMG_PATH', G5_THEME_PATH . '/' . G5_IMG_DIR);
    define('G5_THEME_JS_URL', G5_THEME_URL . '/' . G5_JS_DIR);
    define('G5_THEME_JS_PATH', G5_THEME_PATH . '/' . G5_JS_DIR);
}

function g5_normalize_cert_vendor_config()
{
    global $config;

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
        if (isset($_SESSION['ss_is_mobile'])) {
            $is_mobile = (bool) $_SESSION['ss_is_mobile'];
        } elseif (is_mobile()) {
            $is_mobile = true;
        }
    }

    $_SESSION['ss_is_mobile'] = $is_mobile;

    return $is_mobile;
}
