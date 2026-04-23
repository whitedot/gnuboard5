<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberRegisterNotificationService
{
    public static function sendRegisterEmailCertify($mb_id, $mb_name, $mb_email)
    {
        global $config;

        $subject = '[' . $config['cf_title'] . '] 인증확인 메일입니다.';
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
        sql_query_prepared(" update {$GLOBALS['g5']['member_table']} set mb_email_certify2 = :mb_email_certify2 where mb_id = :mb_id ", array(
            'mb_email_certify2' => $mb_md5,
            'mb_id' => $mb_id,
        ));

        $certify_href = G5_MEMBER_URL . '/email_certify.php?mb_id=' . $mb_id . '&amp;mb_md5=' . $mb_md5;
        $content = MemberMailRenderer::capture('register_email_certify.mail.php', array(
            'certify_href' => $certify_href,
            'mb_name' => $mb_name,
        ));
        $content = run_replace('register_form_update_mail_mb_content', $content, $mb_id);

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
        run_event('register_form_update_send_mb_mail', $config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content);
    }

    public static function sendRegisterEmailChange($mb_id, $mb_name, $mb_email, $w = 'u')
    {
        global $config;

        $subject = '[' . $config['cf_title'] . '] 인증확인 메일입니다.';
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
        sql_query_prepared(" update {$GLOBALS['g5']['member_table']} set mb_email_certify2 = :mb_email_certify2 where mb_id = :mb_id ", array(
            'mb_email_certify2' => $mb_md5,
            'mb_id' => $mb_id,
        ));

        $certify_href = G5_MEMBER_URL . '/email_certify.php?mb_id=' . $mb_id . '&amp;mb_md5=' . $mb_md5;
        $content = MemberMailRenderer::capture('register_email_change.mail.php', array(
            'certify_href' => $certify_href,
            'mb_name' => $mb_name,
            'w' => $w,
        ));
        $content = run_replace('register_form_update_mail_certify_content', $content, $mb_id);

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
        run_event('register_form_update_send_certify_mail', $config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content);
    }
}

class MemberRegisterResponseFlow
{
    public static function finishSubmit($mb_id, array $member, $w, $old_email, $mb_email, $msg = '')
    {
        global $config;

        if ($msg) {
            MemberResponseRenderer::alertScript($msg);
        }

        run_event('register_form_update_after', $mb_id, $w);

        if ($w == '') {
            goto_url(G5_HTTP_MEMBER_URL . '/register_result.php');
        }

        if ($w != 'u') {
            return;
        }

        $tmp_password = sql_fetch_value_prepared(" select mb_password from {$GLOBALS['g5']['member_table']} where mb_id = :mb_id ", array(
            'mb_id' => $member['mb_id'],
        ));

        if ($old_email != $mb_email && $config['cf_use_email_certify']) {
            set_session('ss_mb_id', '');
            alert('회원 정보가 수정 되었습니다.\n\nE-mail 주소가 변경되었으므로 다시 인증하셔야 합니다.', G5_URL);
        }

        MemberResponseRenderer::autoPost(
            G5_HTTP_MEMBER_URL . '/register_form.php',
            array(
                'w' => 'u',
                'mb_id' => $mb_id,
                'mb_password' => $tmp_password,
                'is_update' => '1',
            ),
            '회원 정보가 수정 되었습니다.',
            '회원정보수정'
        );
    }
}

function member_process_email_certify(array $request)
{
    $member_row = member_find_email_certify_candidate($request['mb_id']);
    member_validate_email_certify_member($member_row);

    member_clear_email_certify_token($request['mb_id']);

    $message = member_validate_email_certify_hash($request['mb_md5'], $member_row['mb_email_certify2'], $request['mb_id']);
    member_mark_email_certified($request['mb_id']);

    return $message;
}

function member_complete_email_certify_request(array $request)
{
    member_guard_mail_link_bot();
    $message = member_process_email_certify($request);

    alert($message, G5_URL);
}

function member_prepare_register_email_page(array $request)
{
    $mb = member_find_register_email_member($request['mb_id']);
    member_validate_register_email_member($mb);

    $key = md5($mb['mb_ip'] . $mb['mb_datetime']);
    member_validate_register_email_key($request['ckey'], $key);

    return $mb;
}

function member_resend_register_email_change(array $request)
{
    $mb = member_find_pending_register_email_member($request['mb_id']);
    member_validate_register_email_pending_member($mb);
    member_validate_register_email_update_captcha();

    $count = member_count_other_members_by_email($request['mb_id'], $request['mb_email']);
    member_validate_register_email_uniqueness($count, $request['mb_email']);

    MemberNotificationService::sendRegisterEmailChange($request['mb_id'], $mb['mb_name'], $request['mb_email'], 'u');
    member_update_email_address($request['mb_id'], $request['mb_email']);
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

function member_prepare_register_submit_state($w, array $request, array $member, array $config)
{
    $mb_hp = hyphen_hp_number($request['mb_hp']);

    if ($config['cf_cert_use'] && get_session('ss_cert_type') && get_session('ss_cert_dupinfo')) {
        $row = member_find_dupinfo_owner(isset($member['mb_id']) ? $member['mb_id'] : '', get_session('ss_cert_dupinfo'));
        member_validate_cert_refresh_dupinfo_conflict(isset($row['mb_id']) ? $row['mb_id'] : '');
    }

    $md5_cert_no = get_session('ss_cert_no');
    $cert_type = get_session('ss_cert_type');

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
    global $config;

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
