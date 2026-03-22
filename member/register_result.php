<?php
include_once('./_common.php');

if (isset($_SESSION['ss_mb_reg']))
    $mb = get_member($_SESSION['ss_mb_reg']);

// 회원정보가 없다면 초기 페이지로 이동
if (!(isset($mb['mb_id']) && $mb['mb_id']))
    goto_url(G5_URL);

MemberPageController::render('회원가입 완료', 'register_result.skin.php', array(
    'mb' => $mb,
));
