<?php
include_once('./_common.php');

$member_request_context = member_get_runtime_request_context();
$request = member_read_password_reset_request($member_request_context['post'], $member_request_context['session']);
member_complete_password_reset_request($request);
