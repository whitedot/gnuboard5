<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

member_validate_password_lost_page_access($is_member);

$page_view = member_build_password_lost_page_view();
MemberPageController::render($page_view['title'], 'password_lost.skin.php', $page_view['data']);
