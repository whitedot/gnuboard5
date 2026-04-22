<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function get_admin_token()
{
    $token = md5(uniqid(rand(), true));
    set_session('ss_admin_token', $token);

    return $token;
}

function get_admin_captcha_by($type = 'get')
{
    $captcha_name = 'ss_admin_use_captcha';

    if ($type === 'remove') {
        set_session($captcha_name, '');
    }

    return get_session($captcha_name);
}

function get_sanitize_input($s, $is_html = false)
{
    if (!$is_html) {
        $s = strip_tags($s);
    }

    return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

function check_admin_token()
{
    $token = get_session('ss_admin_token');
    set_session('ss_admin_token', '');

    if (!$token || !$_REQUEST['token'] || $token != $_REQUEST['token']) {
        alert('올바른 방법으로 이용해 주십시오.', G5_URL);
    }

    return true;
}

function admin_csrf_token_key($is_must = 0)
{
    global $member;

    $key = '';

    if ($is_must || !((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))) {
        $key = md5((isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '') . (defined('G5_TOKEN_ENCRYPTION_KEY') ? G5_TOKEN_ENCRYPTION_KEY : '') . $member['mb_id'] . $_SERVER['DOCUMENT_ROOT']);
    }

    return run_replace('admin_csrf_token_key', $key, $is_must);
}

function admin_read_ajax_token_request(array $post)
{
    return array(
        'admin_csrf_token_key' => isset($post['admin_csrf_token_key']) ? (string) $post['admin_csrf_token_key'] : '',
    );
}

function admin_validate_ajax_token_request(array $request)
{
    if (function_exists('admin_csrf_token_key') && $request['admin_csrf_token_key'] !== admin_csrf_token_key(1)) {
        return array('error' => '토큰키 에러!', 'url' => G5_URL);
    }

    $error = admin_referer_check(true);
    if ($error) {
        return array('error' => $error, 'url' => G5_URL);
    }

    return array('error' => '', 'url' => '');
}

function admin_issue_ajax_token_response(array $validation)
{
    if ($validation['error'] !== '') {
        return array(
            'error' => $validation['error'],
            'token' => '',
            'url' => $validation['url'],
        );
    }

    return array(
        'error' => '',
        'token' => get_admin_token(),
        'url' => '',
    );
}

function admin_complete_ajax_token_request(array $request)
{
    set_session('ss_admin_token', '');

    $validation = admin_validate_ajax_token_request($request);
    die(json_encode(admin_issue_ajax_token_response($validation)));
}

function admin_sanitize_token_value($token)
{
    return @htmlspecialchars(strip_tags($token), ENT_QUOTES);
}
