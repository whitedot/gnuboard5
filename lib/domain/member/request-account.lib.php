<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_read_admin_member_request(array $post)
{
    $request = array(
        'mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'mb_certify_case' => isset($post['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', $post['mb_certify_case']) : '',
        'mb_certify' => isset($post['mb_certify']) ? preg_replace('/[^0-9a-z_]/i', '', $post['mb_certify']) : '',
        'mb_marketing_agree' => isset($post['mb_marketing_agree']) ? clean_xss_tags($post['mb_marketing_agree'], 1, 1) : '0',
        'mb_email' => isset($post['mb_email']) ? get_email_address(trim($post['mb_email'])) : '',
        'mb_nick' => isset($post['mb_nick']) ? trim(strip_tags($post['mb_nick'])) : '',
        'mb_memo' => isset($post['mb_memo']) ? clean_xss_tags(trim($post['mb_memo']), 1, 1) : '',
        'mb_hp' => hyphen_hp_number(isset($post['mb_hp']) ? $post['mb_hp'] : ''),
        'passive_certify' => !empty($post['passive_certify']),
        'posts' => array(),
    );

    $check_keys = array('mb_name', 'mb_leave_date', 'mb_intercept_date', 'mb_mailling', 'mb_open', 'mb_level');
    for ($i = 1; $i <= 10; $i++) {
        $check_keys[] = 'mb_' . $i;
    }

    foreach ($check_keys as $key) {
        $request['posts'][$key] = isset($post[$key]) ? clean_xss_tags($post[$key], 1, 1) : '';
    }

    if ($request['mb_certify_case'] && $request['mb_certify']) {
        $request['mb_certify'] = isset($post['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', (string) $post['mb_certify_case']) : '';
        $request['mb_adult'] = isset($post['mb_adult']) ? preg_replace('/[^0-9a-z_]/i', '', (string) $post['mb_adult']) : '';
    } else {
        $request['mb_certify'] = '';
        $request['mb_adult'] = 0;
    }

    return $request;
}

function member_read_confirm_request($url = '')
{
    return array(
        'url' => $url,
    );
}

function member_read_leave_request(array $post, $url = '')
{
    return array(
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'url' => $url,
    );
}

function member_read_cert_refresh_request($w, array $post, $url = '')
{
    return array(
        'w' => $w,
        'url' => urldecode($url),
        'mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
        'mb_name' => isset($post['mb_name']) ? trim($post['mb_name']) : '',
        'mb_hp' => isset($post['mb_hp']) ? trim($post['mb_hp']) : '',
    );
}

function member_read_email_stop_request(array $request, $mb_md5 = '')
{
    return array(
        'mb_id' => isset($request['mb_id']) ? clean_xss_tags($request['mb_id'], 1, 1) : '',
        'mb_md5' => $mb_md5,
    );
}
