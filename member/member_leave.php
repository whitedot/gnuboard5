<?php
include_once('./_common.php');

$member_request_context = member_get_runtime_request_context();
$request = member_read_leave_request($member_request_context['post'], $member_request_context['query_state']);
member_complete_leave_request($member, $is_admin, $request);
