<?php
include_once('./_common.php');

$request = member_read_password_reset_page_request($_POST, $_SESSION);
member_validate_password_reset_page_request($request, $is_member, $config);

$page_view = member_build_password_reset_page_view();
MemberPageController::render($page_view['title'], 'password_reset.skin.php', $page_view['data']);
