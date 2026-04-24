<?php
include_once('./_common.php');

member_validate_confirm_access($is_guest);

$member_request_context = member_get_runtime_request_context();
$request = member_read_confirm_request($member_request_context['query_state']);
$url = member_prepare_confirm_url($request['url']);
$page_view = member_build_confirm_page_view($url, $member);

MemberPageController::renderPage('member_confirm', $page_view);
