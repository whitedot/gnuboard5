<?php
if (!defined('_GNUBOARD_')) exit;

function member_read_registration_request($w, array $post, array $session)
{
    $request = array(
        'mb_id' => '',
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'mb_password_re' => isset($post['mb_password_re']) ? trim($post['mb_password_re']) : '',
        'mb_name' => clean_xss_tags(isset($post['mb_name']) ? trim($post['mb_name']) : '', 1, 1),
        'mb_nick' => isset($post['mb_nick']) ? trim($post['mb_nick']) : '',
        'mb_email' => get_email_address(isset($post['mb_email']) ? trim($post['mb_email']) : ''),
        'mb_sex' => isset($post['mb_sex']) ? trim($post['mb_sex']) : '',
        'mb_birth' => isset($post['mb_birth']) ? trim($post['mb_birth']) : '',
        'mb_hp' => isset($post['mb_hp']) ? trim($post['mb_hp']) : '',
        'mb_mailling' => isset($post['mb_mailling']) ? trim($post['mb_mailling']) : '0',
        'mb_open' => isset($post['mb_open']) ? trim($post['mb_open']) : '0',
        'mb_marketing_agree' => isset($post['mb_marketing_agree']) ? trim($post['mb_marketing_agree']) : '0',
        'mb_nick_default' => isset($post['mb_nick_default']) ? trim($post['mb_nick_default']) : '',
        'mb_open_default' => isset($post['mb_open_default']) ? trim($post['mb_open_default']) : '0',
        'mb_marketing_agree_default' => isset($post['mb_marketing_agree_default']) ? trim($post['mb_marketing_agree_default']) : '0',
        'mb_mailling_default' => isset($post['mb_mailling_default']) ? trim($post['mb_mailling_default']) : '0',
        'cert_no' => isset($post['cert_no']) ? trim($post['cert_no']) : '',
    );

    if ($w === 'u') {
        $request['mb_id'] = isset($session['ss_mb_id']) ? trim($session['ss_mb_id']) : '';
    } elseif ($w === '') {
        $request['mb_id'] = isset($post['mb_id']) ? trim($post['mb_id']) : '';
    }

    return $request;
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

    $check_keys = array(
        'mb_name',
        'mb_leave_date',
        'mb_intercept_date',
        'mb_mailling',
        'mb_open',
        'mb_level'
    );

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

function member_clear_certification_session()
{
    $session_keys = array(
        'ss_cert_type',
        'ss_cert_no',
        'ss_cert_hash',
        'ss_cert_birth',
        'ss_cert_adult',
    );

    foreach ($session_keys as $session_key) {
        if (isset($_SESSION[$session_key])) {
            unset($_SESSION[$session_key]);
        }
    }
}
