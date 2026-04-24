<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$member_request_context = member_get_runtime_request_context();
$member_query_state = $member_request_context['query_state'];
$request = member_read_register_form_request($member_request_context['post'], $member_query_state);
member_prepare_register_form_entry();
member_validate_register_form_request($request, $member, $is_member, $is_admin);

$page_view = member_build_register_form_page_view($request, $member, $config, member_read_current_url_encoded($member_query_state));
MemberPageController::renderPage('register_form', $page_view);
