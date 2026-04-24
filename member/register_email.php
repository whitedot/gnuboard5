<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

$member_request_context = member_get_runtime_request_context();
$request = member_read_register_email_request($member_request_context['request']);
$mb = member_prepare_register_email_page($request);
$page_view = member_build_register_email_page_view($mb, $request['mb_id']);

MemberPageController::renderPage('register_email', $page_view);
