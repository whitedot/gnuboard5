<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function auth_check_menu($auth, $sub_menu, $attr, $return = false)
{
    $check_auth = isset($auth[$sub_menu]) ? $auth[$sub_menu] : '';
    return auth_check($check_auth, $attr, $return);
}

function auth_check($auth, $attr, $return = false)
{
    global $is_admin;

    if ($is_admin == 'super') {
        return;
    }

    if (!trim($auth)) {
        $msg = '이 메뉴에는 접근 권한이 없습니다.\\n\\n접근 권한은 최고관리자만 부여할 수 있습니다.';
        if ($return) {
            return $msg;
        } else {
            alert($msg);
        }
    }

    $attr = strtolower($attr);

    if (!strstr($auth, $attr)) {
        if ($attr == 'r') {
            $msg = '읽을 권한이 없습니다.';
        } else if ($attr == 'w') {
            $msg = '입력, 추가, 생성, 수정 권한이 없습니다.';
        } else if ($attr == 'd') {
            $msg = '삭제 권한이 없습니다.';
        } else {
            $msg = '속성이 잘못 되었습니다.';
        }

        if ($return) {
            return $msg;
        } else {
            alert($msg);
        }
    }
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

    $s = htmlspecialchars($s, ENT_QUOTES, 'utf-8');

    return $s;
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

function admin_referer_check($return = false)
{
    $referer = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
    if (!$referer) {
        $msg = '정보가 올바르지 않습니다.';

        if ($return) {
            return $msg;
        } else {
            alert($msg, G5_URL);
        }
    }

    $p = @parse_url($referer);

    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
    $msg = '';

    if ($host != $p['host']) {
        $msg = '올바른 방법으로 이용해 주십시오.';
    }

    if ($p['path'] && !preg_match('/\/' . preg_quote(G5_ADMIN_DIR) . '\//i', $p['path'])) {
        $msg = '올바른 방법으로 이용해 주십시오';
    }

    if ($msg) {
        if ($return) {
            return $msg;
        } else {
            alert($msg, G5_URL);
        }
    }
}

function admin_check_xss_params($params)
{
    if (!$params) {
        return;
    }

    foreach ($params as $key => $value) {
        if (empty($value)) {
            continue;
        }

        if (is_array($value)) {
            admin_check_xss_params($value);
        } else if (
            (preg_match('/<\s?[^\>]*\/?\s?>/i', $value) && (preg_match('/script.*?\/script/ius', $value) || preg_match('/on[a-z]+=*/ius', $value))) || preg_match('/^(?=.*token\()(?=.*xmlhttprequest\()(?=.*send\().*$/im', $value) ||
            (preg_match('/(on[a-z]+|focus)=.*/ius', $value) && preg_match('/(eval|atob|fetch|expression|exec|prompt)(\s*)\((.*)\)/ius', $value))) {
            alert('요청 쿼리에 잘못된 스크립트문장이 있습니다.\\nXSS 공격일수도 있습니다.', G5_URL);
            die();
        } else if (preg_match('/atob\s*\(\s*[\'"]?([a-zA-Z0-9+\/=]+)[\'"]?\s*\)/ius', $value, $matches)) {
            $decoded = base64_decode($matches[1], true);
            if ($decoded && preg_match('/(eval|fetch|script|alert|settimeout|setinterval)/ius', $decoded)) {
                alert('Base64로 인코딩된 위험한 스크립트가 발견되었습니다.', G5_URL);
                die();
            }
        }
    }

    return;
}
