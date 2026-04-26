<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_validate_email_certify_member(array $member_row)
{
    if (empty($member_row['mb_id'])) {
        alert('존재하는 회원이 아닙니다.', G5_URL);
    }

    if ($member_row['mb_leave_date'] || $member_row['mb_intercept_date']) {
        alert('탈퇴 또는 차단된 회원입니다.', G5_URL);
    }
}

function member_validate_email_certify_hash($mb_md5, $stored_hash, $mb_id)
{
    if (!$mb_md5) {
        alert('제대로 된 값이 넘어오지 않았습니다.', G5_URL);
    }

    if ($mb_md5 !== $stored_hash) {
        alert('메일인증 요청 정보가 올바르지 않습니다.', G5_URL);
    }

    return "메일인증 처리를 완료 하였습니다.\\n\\n지금부터 {$mb_id} 아이디로 로그인 가능합니다.";
}

function member_validate_register_email_member(array $mb)
{
    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        alert('해당 회원이 존재하지 않습니다.', G5_URL);
    }

    if (substr($mb['mb_email_certify'], 0, 1) != 0) {
        alert('이미 메일인증 하신 회원입니다.', G5_URL);
    }
}

function member_validate_register_email_key($ckey, $key)
{
    if (!$ckey || $ckey != $key) {
        alert('올바른 방법으로 이용해 주십시오.', G5_URL);
    }
}

function member_validate_register_email_update_request(array $request)
{
    if (!$request['mb_id'] || !$request['mb_email']) {
        alert('올바른 방법으로 이용해 주십시오.', G5_URL);
    }
}

function member_validate_register_email_update_captcha()
{
    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

function member_validate_register_email_pending_member($mb)
{
    if (!$mb) {
        alert('이미 메일인증 하신 회원입니다.', G5_URL);
    }
}

function member_validate_register_email_uniqueness($count, $mb_email)
{
    if ($count) {
        alert("{$mb_email} 메일은 이미 존재하는 메일주소 입니다.\\n\\n다른 메일주소를 입력해 주십시오.");
    }
}
