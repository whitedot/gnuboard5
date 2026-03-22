<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if ($is_member) {
    alert("이미 로그인중입니다.", G5_URL);
}

$action_url = G5_HTTPS_MEMBER_URL."/password_lost2.php";
MemberPageController::render('회원정보 찾기', 'password_lost.skin.php', array(
    'action_url' => $action_url,
));
