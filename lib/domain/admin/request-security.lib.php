<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_referer_check($return = false)
{
    $server_input = g5_get_runtime_server_input();
    $referer = isset($server_input['HTTP_REFERER']) ? trim($server_input['HTTP_REFERER']) : '';
    if (!$referer) {
        $msg = '정보가 올바르지 않습니다.';

        if ($return) {
            return $msg;
        }

        alert($msg, G5_URL);
    }

    $p = @parse_url($referer);
    $host = isset($server_input['HTTP_HOST']) ? preg_replace('/:[0-9]+$/', '', $server_input['HTTP_HOST']) : '';
    $msg = '';

    if (!isset($p['host']) || $host != $p['host']) {
        $msg = '올바른 방법으로 이용해 주십시오.';
    }

    if (isset($p['path']) && $p['path'] && !preg_match('/\/' . preg_quote(G5_ADMIN_DIR) . '\//i', $p['path'])) {
        $msg = '올바른 방법으로 이용해 주십시오';
    }

    if ($msg) {
        if ($return) {
            return $msg;
        }

        alert($msg, G5_URL);
    }
}

function admin_check_xss_params($params)
{
    if (!$params) {
        return;
    }

    foreach ($params as $value) {
        if (empty($value)) {
            continue;
        }

        if (is_array($value)) {
            admin_check_xss_params($value);
        } elseif (
            (preg_match('/<\s?[^\>]*\/?\s?>/i', $value) && (preg_match('/script.*?\/script/ius', $value) || preg_match('/on[a-z]+=*/ius', $value)))
            || preg_match('/^(?=.*token\()(?=.*xmlhttprequest\()(?=.*send\().*$/im', $value)
            || (preg_match('/(on[a-z]+|focus)=.*/ius', $value) && preg_match('/(eval|atob|fetch|expression|exec|prompt)(\s*)\((.*)\)/ius', $value))
        ) {
            alert('요청 쿼리에 잘못된 스크립트문장이 있습니다.\\nXSS 공격일수도 있습니다.', G5_URL);
            die();
        } elseif (preg_match('/atob\s*\(\s*[\'"]?([a-zA-Z0-9+\/=]+)[\'"]?\s*\)/ius', $value, $matches)) {
            $decoded = base64_decode($matches[1], true);
            if ($decoded && preg_match('/(eval|fetch|script|alert|settimeout|setinterval)/ius', $decoded)) {
                alert('Base64로 인코딩된 위험한 스크립트가 발견되었습니다.', G5_URL);
                die();
            }
        }
    }
}
