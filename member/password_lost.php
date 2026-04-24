<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

member_validate_password_lost_page_access($is_member);

$page_view = member_build_password_lost_page_view($config);
MemberPageController::renderPage('password_lost', $page_view);
