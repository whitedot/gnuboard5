<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');
include_once(G5_LIB_PATH.'/support/mail.lib.php');

$member_request_context = member_get_runtime_request_context();
$register_request = member_read_registration_request($member_request_context['post'], $member_request_context['session'], $member_request_context['query_state']);
member_complete_register_submit_request($register_request['w'], $register_request, $member, $config, $is_admin, $member_view_path);
