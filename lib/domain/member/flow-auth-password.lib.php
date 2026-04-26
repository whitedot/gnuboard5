<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_issue_password_lost_mail($email, array $mb)
{
    $change_password = rand(100000, 999999);
    $mb_lost_certify = get_encrypt_string($change_password);
    $mb_nonce = md5(pack('V*', rand(), rand(), rand(), rand()));

    member_store_password_lost_certify($mb['mb_id'], $mb_nonce . ' ' . $mb_lost_certify);
    MemberNotificationService::sendPasswordLostMail($email, $mb, $change_password, $mb_nonce, $mb_lost_certify);
}

function member_complete_password_lost_request(array $request, $is_member)
{
    member_validate_password_lost_submit_access($is_member);
    member_validate_password_lost_request($request);

    $email = $request['mb_email'];
    $count = member_count_by_email($email);
    member_validate_password_lost_candidate_count($count);

    $mb = member_find_password_lost_candidate($email);
    member_validate_password_lost_member($mb);

    member_issue_password_lost_mail($email, $mb);

    alert_close($email . ' 메일로 회원아이디와 비밀번호를 인증할 수 있는 메일이 발송 되었습니다.\\n\\n메일을 확인하여 주십시오.');
}

function member_finish_password_reset(array $request)
{
    member_update_password_by_dupinfo(
        $request['mb_id'],
        $request['mb_dupinfo'],
        get_encrypt_string($request['mb_password'])
    );

    set_session('ss_cert_mb_id', '');
    set_session('ss_cert_dupinfo', '');
}

function member_complete_password_reset_request(array $request)
{
    member_validate_password_reset_request($request);

    $mb_check = member_find_by_id_and_dupinfo($request['mb_id'], $request['mb_dupinfo']);
    member_validate_password_reset_member($mb_check);

    member_finish_password_reset($request);

    goto_url(G5_MEMBER_URL . '/login.php');
}

function member_process_password_lost_certify(array $request)
{
    run_event('password_lost_certify_before');

    $mb = member_find_password_lost_certify_row($request['mb_no']);
    member_validate_password_lost_certify_row($mb);
    member_validate_password_lost_certify_nonce($request, $mb);

    $new_password_hash = substr($mb['mb_lost_certify'], 33);
    member_confirm_password_lost_certify($request['mb_no'], $mb['mb_lost_certify'], $new_password_hash);

    run_event('password_lost_certify_after', $mb, $request['mb_nonce']);
}

function member_complete_password_lost_certify_request(array $request)
{
    member_guard_mail_link_bot();
    member_process_password_lost_certify($request);

    alert('비밀번호가 변경됐습니다.\\n\\n회원아이디와 변경된 비밀번호로 로그인 하시기 바랍니다.', G5_MEMBER_URL . '/login.php');
}
