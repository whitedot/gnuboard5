<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_read_password_lost_request(array $post)
{
    return array(
        'mb_email' => get_email_address(trim(isset($post['mb_email']) ? $post['mb_email'] : '')),
    );
}

function member_read_password_reset_request(array $post, array $session)
{
    return array(
        'mb_id' => isset($session['ss_cert_mb_id']) ? trim(get_session('ss_cert_mb_id')) : '',
        'mb_dupinfo' => isset($session['ss_cert_dupinfo']) ? trim(get_session('ss_cert_dupinfo')) : '',
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'mb_password_re' => isset($post['mb_password_re']) ? trim($post['mb_password_re']) : '',
    );
}

function member_read_login_request(array $post, $fallback_url = '')
{
    return array(
        'mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'auto_login' => !empty($post['auto_login']),
        'url' => isset($post['url']) ? trim($post['url']) : $fallback_url,
    );
}

function member_read_password_reset_page_request(array $post, array $session)
{
    return array(
        'mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
        'session_mb_id' => isset($session['ss_cert_mb_id']) ? trim(get_session('ss_cert_mb_id')) : '',
    );
}

function member_read_login_page_request($url = '')
{
    return array(
        'url' => $url,
    );
}

function member_read_logout_request($url = '')
{
    return array(
        'url' => $url,
    );
}

function member_read_password_lost_certify_request(array $get)
{
    return array(
        'mb_no' => isset($get['mb_no']) ? preg_replace('#[^0-9]#', '', trim($get['mb_no'])) : 0,
        'mb_nonce' => isset($get['mb_nonce']) ? trim($get['mb_nonce']) : '',
    );
}
