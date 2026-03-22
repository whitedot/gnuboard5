<?php
include_once('./_common.php');

// 로그인중인 경우 회원가입 할 수 없습니다.
if ($is_member) {
    goto_url(G5_URL);
}

// 세션을 지웁니다.
set_session("ss_mb_reg", "");

$register_action_url = G5_MEMBER_URL.'/register_form.php';
MemberPageController::render('회원가입약관', 'register.skin.php', array(
    'register_action_url' => $register_action_url,
));
