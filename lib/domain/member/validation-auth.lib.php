<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_validate_password_lost_request(array $request)
{
    if (!$request['mb_email']) {
        alert_close('메일주소 오류입니다.');
    }
}

function member_validate_password_lost_submit_access($is_member)
{
    if ($is_member) {
        alert_close('이미 로그인중입니다.', G5_URL);
    }

    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

function member_validate_password_lost_candidate_count($count)
{
    if ((int) $count > 1) {
        alert('동일한 메일주소가 2개 이상 존재합니다.\\n\\n관리자에게 문의하여 주십시오.');
    }
}

function member_validate_password_lost_member(array $mb)
{
    if (empty($mb['mb_id']) || $mb['mb_leave_date']) {
        alert('존재하지 않는 회원입니다.');
    } elseif (is_admin($mb['mb_id'])) {
        alert('관리자 아이디는 접근 불가합니다.');
    }
}

function member_validate_password_reset_request(array $request)
{
    if (!$request['mb_id']) {
        alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.', G5_URL);
    }
    if (!$request['mb_dupinfo']) {
        alert('잘못된 접근입니다.', G5_URL);
    }
    if (!$request['mb_password']) {
        alert('비밀번호가 넘어오지 않았습니다.');
    }
    if ($request['mb_password'] !== $request['mb_password_re']) {
        alert('비밀번호가 일치하지 않습니다.');
    }
}

function member_validate_password_reset_member($mb_check)
{
    if (!$mb_check) {
        alert('잘못된 접근입니다.', G5_URL);
    }
}

function member_validate_login_request(array $request)
{
    run_event('member_login_check_before', $request['mb_id']);

    if (!$request['mb_id'] || run_replace('check_empty_member_login_password', !$request['mb_password'], $request['mb_id'])) {
        alert('회원아이디나 비밀번호가 공백이면 안됩니다.');
    }
}

function member_validate_login_credentials(array $request, array $mb)
{
    $is_need_not_password = run_replace('login_check_need_not_password', false, $request['mb_id'], $request['mb_password'], $mb, false);
    if ($is_need_not_password) {
        return;
    }

    if (!(isset($mb['mb_id']) && $mb['mb_id']) || !login_password_check($mb, $request['mb_password'], $mb['mb_password'])) {
        run_event('password_is_wrong', 'login', $mb);
        alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
    }
}

function member_validate_login_status(array $mb)
{
    if ($mb['mb_intercept_date'] && $mb['mb_intercept_date'] <= date('Ymd', G5_SERVER_TIME)) {
        $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_intercept_date']);
        alert('회원님의 아이디는 접근이 금지되어 있습니다.\n처리일 : ' . $date);
    }

    if ($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date('Ymd', G5_SERVER_TIME)) {
        $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_leave_date']);
        alert('탈퇴한 아이디이므로 접근하실 수 없습니다.\n탈퇴일 : ' . $date);
    }
}

function member_validate_login_email_certify(array $mb, $mb_id)
{
    if (is_use_email_certify() && !preg_match("/[1-9]/", $mb['mb_email_certify'])) {
        $ckey = md5($mb['mb_ip'] . $mb['mb_datetime']);
        confirm(
            "{$mb['mb_email']} 메일로 메일인증을 받으셔야 로그인 가능합니다. 다른 메일주소로 변경하여 인증하시려면 취소를 클릭하시기 바랍니다.",
            G5_URL,
            G5_MEMBER_URL . '/register_email.php?mb_id=' . $mb_id . '&ckey=' . $ckey
        );
    }
}

function member_validate_password_reset_page_request(array $request, $is_member, array $config)
{
    if ($is_member) {
        alert('이미 로그인중입니다.');
        goto_url(G5_URL);
    }

    if ($request['mb_id'] !== $request['session_mb_id']) {
        alert('잘못된 접근입니다.');
        goto_url(G5_URL);
    }

    if ($config['cf_cert_find'] != 1) {
        alert('본인인증을 이용하여 아이디/비밀번호 찾기를 할 수 없습니다. 관리자에게 문의 하십시오.');
    }
}

function member_validate_login_page_request(array $request)
{
    check_url_host($request['url']);
}

function member_validate_password_lost_page_access($is_member)
{
    if ($is_member) {
        alert('이미 로그인중입니다.', G5_URL);
    }
}

function member_validate_logout_request(array $request)
{
    if (!$request['url']) {
        return;
    }

    if (substr($request['url'], 0, 2) == '//') {
        $request['url'] = 'http:' . $request['url'];
    }
}

function member_fail_password_lost_certify()
{
    die('Error');
}

function member_validate_password_lost_certify_row(array $mb)
{
    if (strlen(isset($mb['mb_lost_certify']) ? $mb['mb_lost_certify'] : '') < 33) {
        member_fail_password_lost_certify();
    }
}

function member_validate_password_lost_certify_nonce(array $request, array $mb)
{
    if ($request['mb_nonce'] !== substr($mb['mb_lost_certify'], 0, 32)) {
        member_fail_password_lost_certify();
    }
}
