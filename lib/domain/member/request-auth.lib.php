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
    $certification_session = member_read_certification_session_state($session);

    return array(
        'mb_id' => $certification_session['cert_mb_id'],
        'mb_dupinfo' => $certification_session['cert_dupinfo'],
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'mb_password_re' => isset($post['mb_password_re']) ? trim($post['mb_password_re']) : '',
    );
}

function member_read_login_request(array $post, array $query_state = array())
{
    return array(
        'mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'auto_login' => !empty($post['auto_login']),
        'url' => member_read_request_url($post, $query_state),
    );
}

function member_read_password_reset_page_request(array $post, array $session)
{
    $certification_session = member_read_certification_session_state($session);

    return array(
        'mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
        'session_mb_id' => $certification_session['cert_mb_id'],
    );
}

function member_read_login_page_request(array $query_state = array())
{
    return array(
        'url' => member_read_request_url(array(), $query_state),
    );
}

function member_read_logout_request(array $query_state = array())
{
    return array(
        'url' => member_read_request_url(array(), $query_state),
    );
}

function member_read_password_lost_certify_request(array $get)
{
    return array(
        'mb_no' => isset($get['mb_no']) ? preg_replace('#[^0-9]#', '', trim($get['mb_no'])) : 0,
        'mb_nonce' => isset($get['mb_nonce']) ? trim($get['mb_nonce']) : '',
    );
}
