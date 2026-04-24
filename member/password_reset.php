<?php
include_once('./_common.php');

$member_request_context = member_get_runtime_request_context();
$request = member_read_password_reset_page_request($member_request_context['post'], $member_request_context['session']);
member_validate_password_reset_page_request($request, $is_member, $config);

$page_view = member_build_password_reset_page_view($request);
MemberPageController::renderPage('password_reset', $page_view);
