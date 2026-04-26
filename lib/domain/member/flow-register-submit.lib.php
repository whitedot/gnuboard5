<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_prepare_register_submit_state($w, array $request, array $member, array $config)
{
    $mb_hp = hyphen_hp_number($request['mb_hp']);
    $certification_session = member_read_certification_session_state();

    if ($config['cf_cert_use'] && $certification_session['cert_type'] && $certification_session['cert_dupinfo']) {
        $row = member_find_dupinfo_owner(isset($member['mb_id']) ? $member['mb_id'] : '', $certification_session['cert_dupinfo']);
        member_validate_cert_refresh_dupinfo_conflict(isset($row['mb_id']) ? $row['mb_id'] : '');
    }

    $md5_cert_no = $certification_session['cert_no'];
    $cert_type = $certification_session['cert_type'];

    return array(
        'mb_hp' => $mb_hp,
        'md5_cert_no' => $md5_cert_no,
        'cert_type' => $cert_type,
        'sql_certify' => build_member_certify_fields($w, $request['mb_name'], $mb_hp, $cert_type, $md5_cert_no),
    );
}

function member_submit_register_create(array $request, array $config, array $submit_state)
{
    $mb_id = $request['mb_id'];
    $mb_name = $request['mb_name'];
    $mb_email = $request['mb_email'];

    ensure_member_cert_history_table();

    $insert_fields = member_build_register_insert_fields($request, $config, $submit_state['sql_certify']);

    if (!member_insert_account_with_history($mb_id, $insert_fields, $mb_name, $submit_state['mb_hp'], $submit_state['cert_type'], $submit_state['md5_cert_no'])) {
        alert('회원정보를 저장하는 중 오류가 발생했습니다.');
    }

    $old_email = '';

    if ($config['cf_use_email_certify']) {
        MemberNotificationService::sendRegisterEmailCertify($mb_id, $mb_name, $mb_email);
        $old_email = $mb_email;
    } else {
        set_session('ss_mb_id', $mb_id);
        if (function_exists('update_auth_session_token')) {
            update_auth_session_token(G5_TIME_YMDHIS);
        }
    }

    set_session('ss_mb_reg', $mb_id);

    return array(
        'old_email' => $old_email,
    );
}

function member_submit_register_update(array $request, array $config, array $submit_state, $old_email)
{
    member_validate_register_update_submission($request);
    ensure_member_cert_history_table();

    $update_fields = member_build_register_update_fields($request, $config, $submit_state['sql_certify'], $old_email);

    if (!member_update_account_with_history($request['mb_id'], $update_fields, $request['mb_name'], $submit_state['mb_hp'], $submit_state['cert_type'], $submit_state['md5_cert_no'])) {
        alert('회원정보를 수정하는 중 오류가 발생했습니다.');
    }
}

function member_finish_register_submit($mb_id, $mb_name, array $member, $w, $old_email, $mb_email, $msg = '')
{
    $config = member_get_runtime_config();

    if ($config['cf_use_email_certify'] && $old_email != $mb_email) {
        MemberNotificationService::sendRegisterEmailChange($mb_id, $mb_name, $mb_email, $w);
    }

    member_clear_certification_session();
    MemberRegisterResponseFlow::finishSubmit($mb_id, $member, $w, $old_email, $mb_email, $msg);
}

function member_complete_register_submit_request($w, array $request, array $member, array $config, $is_admin, $member_view_path)
{
    member_validate_register_submit_request($w, $request, $is_admin);

    $mb_id = $request['mb_id'];
    $mb_name = $request['mb_name'];
    $mb_email = $request['mb_email'];

    run_event('register_form_update_before', $mb_id, $w);

    $validation_state = member_validate_register_request($w, $request, $member, $config);
    $mb_nick = $request['mb_nick'];
    $old_email = $validation_state['old_email'];

    run_event('register_form_update_valid', $w, $mb_id, $mb_nick, $mb_email);
    member_validate_register_uniqueness($mb_id, $mb_nick, $mb_email);

    $submit_state = member_prepare_register_submit_state($w, $request, $member, $config);

    if ($w == '') {
        $submission_result = member_submit_register_create($request, $config, $submit_state);
        $old_email = $submission_result['old_email'];
    } elseif ($w == 'u') {
        member_submit_register_update($request, $config, $submit_state, $old_email);
    }

    member_finish_register_submit($mb_id, $mb_name, $member, $w, $old_email, $mb_email, '');
}
