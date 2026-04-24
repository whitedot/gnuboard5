<?php
include_once('./_common.php');

$member_request_context = member_get_runtime_request_context();
$member_query_state = $member_request_context['query_state'];
$request = member_read_login_page_request($member_query_state);
member_validate_login_page_request($request);
member_redirect_if_logged_in($is_member, $request['url']);

$login_view = member_build_login_page_view($member_view_path, $request['url']);
$page_view = member_build_login_render_page_view($login_view, $request['url']);
MemberPageController::renderPage('login', $page_view);
