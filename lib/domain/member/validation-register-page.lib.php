<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_validate_register_page_access($is_member)
{
    if ($is_member) {
        goto_url(G5_URL);
    }
}

function member_validate_register_form_request(array $request, array $member, $is_member, $is_admin)
{
    if (!($request['w'] === '' || $request['w'] === 'u')) {
        alert('w 값이 제대로 넘어오지 않았습니다.');
    }

    if ($request['w'] === '') {
        if ($is_member) {
            goto_url(G5_URL);
        }

        referer_check();
        if (!$request['agree']) {
            alert('회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.', G5_MEMBER_URL . '/register.php');
        }

        if (!$request['agree2']) {
            alert('개인정보 수집 및 이용의 내용에 동의하셔야 회원가입 하실 수 있습니다.', G5_MEMBER_URL . '/register.php');
        }

        return;
    }

    if ($is_admin == 'super') {
        alert('관리자의 회원정보는 관리자 화면에서 수정해 주십시오.', G5_URL);
    }

    if (!$is_member) {
        alert('로그인 후 이용하여 주십시오.', G5_URL);
    }

    if ($member['mb_id'] != $request['mb_id']) {
        alert('로그인된 회원과 넘어온 정보가 서로 다릅니다.');
    }

    if ($request['mb_id'] && !$request['mb_password']) {
        alert('비밀번호를 입력해 주세요.');
    }

    if ($request['mb_password']) {
        $pass_check = $request['is_update']
            ? ($member['mb_password'] === $request['mb_password'])
            : check_password($request['mb_password'], $member['mb_password']);

        if (!$pass_check) {
            alert('비밀번호가 틀립니다.');
        }
    }
}
