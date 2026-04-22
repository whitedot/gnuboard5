<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function g5_extract_request_globals()
{
    $allowed_keys = array(
        'gr_id',
        'page',
        'sca',
        'sfl',
        'sod',
        'sop',
        'spt',
        'sst',
        'stx',
        'url',
        'w',
    );

    foreach (array($_GET, $_POST) as $source) {
        foreach ($allowed_keys as $key) {
            if (!array_key_exists($key, $source) || is_array($source[$key])) {
                continue;
            }

            $GLOBALS[$key] = $source[$key];
        }
    }
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
        $state['urlencode'] = rtrim(G5_DOMAIN, '%2F') . '%2F' . ltrim(urldecode(preg_replace("/^" . urlencode($parsed_domain['path']) . "/", "", $state['urlencode'])), '%2F');
    }

    if (isset($_REQUEST['gr_id']) && !is_array($_REQUEST['gr_id'])) {
        $state['gr_id'] = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['gr_id']));
    }

    return $state;
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
