<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_reset_registration_progress()
{
    set_session('ss_mb_reg', '');
}

function member_prepare_register_form_entry()
{
    run_event('register_form_before');

    $token = md5(uniqid(rand(), true));
    set_session('ss_token', $token);
    set_session('ss_cert_no', '');
    set_session('ss_cert_hash', '');
    set_session('ss_cert_type', '');
}

function member_prepare_register_form_create_state(array $member, array $request)
{
    $member['mb_birth'] = $request['birth'];
    $member['mb_sex'] = $request['sex'];
    $member['mb_name'] = $request['mb_name'];

    return array(
        'title' => '회원 가입',
        'member' => $member,
    );
}

function member_prepare_register_form_update_state(array $member)
{
    set_session('ss_reg_mb_name', $member['mb_name']);
    set_session('ss_reg_mb_hp', $member['mb_hp']);

    $member['mb_email'] = get_text($member['mb_email']);
    $member['mb_birth'] = get_text($member['mb_birth']);
    $member['mb_hp'] = get_text($member['mb_hp']);

    return array(
        'title' => '회원 정보 수정',
        'member' => $member,
    );
}

function member_apply_register_form_defaults(array $member)
{
    $defaults = array(
        'mb_marketing_agree' => '0',
        'mb_marketing_date' => '0000-00-00 00:00:00',
        'mb_mailling' => '0',
        'mb_mailling_date' => '0000-00-00 00:00:00',
    );

    foreach ($defaults as $member_key => $default_value) {
        if (!isset($member[$member_key])) {
            $member[$member_key] = $default_value;
        }
    }

    return $member;
}
