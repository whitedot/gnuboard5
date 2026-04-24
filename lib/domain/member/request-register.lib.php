<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_read_registration_request(array $post, array $session, array $query_state = array())
{
    $w = member_read_request_w($post, $query_state);
    $request = array(
        'mb_id' => '',
        'posted_mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
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
        $request['mb_id'] = $request['posted_mb_id'];
    }

    return $request;
}

function member_clear_certification_session()
{
    member_clear_session_keys(array(
        'ss_cert_type',
        'ss_cert_no',
        'ss_cert_hash',
        'ss_cert_birth',
        'ss_cert_adult',
    ));
}

function member_read_register_result_request(array $session)
{
    return array(
        'mb_id' => isset($session['ss_mb_reg']) ? trim($session['ss_mb_reg']) : '',
    );
}

function member_read_email_certify_request(array $get)
{
    return array(
        'mb_id' => isset($get['mb_id']) ? trim($get['mb_id']) : '',
        'mb_md5' => isset($get['mb_md5']) ? trim($get['mb_md5']) : '',
    );
}

function member_read_register_email_request(array $get)
{
    return array(
        'mb_id' => isset($get['mb_id']) ? substr(clean_xss_tags($get['mb_id']), 0, 20) : '',
        'ckey' => isset($get['ckey']) ? trim($get['ckey']) : '',
    );
}

function member_read_register_email_update_request(array $post)
{
    return array(
        'mb_id' => isset($post['mb_id']) ? substr(clean_xss_tags($post['mb_id']), 0, 20) : '',
        'mb_email' => isset($post['mb_email']) ? get_email_address(trim($post['mb_email'])) : '',
    );
}

function member_read_register_form_request(array $post, array $query_state = array())
{
    $w = member_read_request_w($post, $query_state);

    return array(
        'w' => $w,
        'agree' => isset($post['agree']) ? preg_replace('#[^0-9]#', '', $post['agree']) : '',
        'agree2' => isset($post['agree2']) ? preg_replace('#[^0-9]#', '', $post['agree2']) : '',
        'birth' => isset($post['birth']) ? $post['birth'] : '',
        'sex' => isset($post['sex']) ? $post['sex'] : '',
        'mb_name' => isset($post['mb_name']) ? $post['mb_name'] : '',
        'mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'is_update' => !empty($post['is_update']),
    );
}
