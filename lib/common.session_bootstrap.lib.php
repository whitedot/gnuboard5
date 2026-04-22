<?php
if (!defined('_GNUBOARD_')) {
    exit;
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
        $add_str = substr_count(G5_PATH, '/') . basename(dirname(__FILE__));
    }

    if ($add_str || (isset($_SERVER['HTTP_HOST']) && preg_match('/(' . implode('|', $domain_array) . ')/i', $_SERVER['HTTP_HOST']))) {
        if (!defined('G5_SESSION_NAME')) {
            define('G5_SESSION_NAME', 'G5' . $add_str . 'PHPSESSID');
        }
        @session_name(G5_SESSION_NAME);
    }
}

if (!class_exists('XenoPostToForm')) {
    class XenoPostToForm
    {
        public static function g5_session_name()
        {
            return (defined('G5_SESSION_NAME') && G5_SESSION_NAME) ? G5_SESSION_NAME : 'PHPSESSID';
        }

        public static function php52_request_check()
        {
            $cookie_session_name = self::g5_session_name();
            if (isset($_REQUEST[$cookie_session_name]) && $_REQUEST[$cookie_session_name] != session_id()) {
                goto_url(G5_MEMBER_URL . '/logout.php');
            }
        }

        public static function check()
        {
            $cookie_session_name = self::g5_session_name();

            return !isset($_COOKIE[$cookie_session_name]) && count($_POST) && ((isset($_SERVER['HTTP_REFERER']) && !preg_match('~^https://' . preg_quote($_SERVER['HTTP_HOST'], '~') . '/~', $_SERVER['HTTP_REFERER']) || !isset($_SERVER['HTTP_REFERER'])));
        }

        public static function submit($posts)
        {
            echo '<html><head><meta charset="UTF-8"></head><body>';
            echo '<form id="f" name="f" method="post">';
            echo self::makeInputArray($posts);
            echo '</form>';
            echo '<script>';
            echo 'document.f.submit();';
            echo '</script></body></html>';
            exit;
        }

        public static function makeInputArray($posts)
        {
            $res = array();
            foreach ($posts as $k => $v) {
                $res[] = self::makeInputArray_($k, $v);
            }
            return implode('', $res);
        }

        private static function makeInputArray_($k, $v)
        {
            if (is_array($v)) {
                $res = array();
                foreach ($v as $i => $j) {
                    $res[] = self::makeInputArray_($k . '[' . htmlspecialchars($i) . ']', $j);
                }
                return implode('', $res);
            }
            return '<input type="hidden" name="' . $k . '" value="' . htmlspecialchars($v) . '" />';
        }
    }
}

if (!function_exists('check_special_post_return_page')) {
    function check_special_post_return_page()
    {
        $pg_checks_pages = array(
            'plugin/inicert/ini_result.php',
            'plugin/inicert/ini_find_result.php',
        );

        $server_script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);

        foreach ($pg_checks_pages as $pg_page) {
            if (preg_match('~' . preg_quote($pg_page) . '$~i', $server_script_name)) {
                return true;
            }
        }

        return false;
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

if (!function_exists('session_start_samesite')) {
    function session_start_samesite($options = array())
    {
        global $g5;

        $res = @session_start($options);

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            if (preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT'])
                || preg_match('/(iPhone|iPod|iPad).*AppleWebKit.*Safari/i', $_SERVER['HTTP_USER_AGENT'])
                || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'])
                || preg_match('~Trident/7.0(; Touch)?; rv:11.0~', $_SERVER['HTTP_USER_AGENT'])
                || !(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
                return $res;
            }
        }

        $headers = headers_list();
        krsort($headers);
        $cookie_session_name = method_exists('XenoPostToForm', 'g5_session_name') ? XenoPostToForm::g5_session_name() : 'PHPSESSID';
        foreach ($headers as $header) {
            if (!preg_match('~^Set-Cookie: ' . $cookie_session_name . '=~', $header)) {
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

function g5_start_session_with_samesite_support()
{
    global $config;

    if ($config['cf_cert_use']) {
        session_start_samesite();
    } else {
        @session_start();
    }
}
