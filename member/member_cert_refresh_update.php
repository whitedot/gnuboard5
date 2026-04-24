<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$member_request_context = member_get_runtime_request_context();
$request = member_read_cert_refresh_request($member_request_context['post'], $member_request_context['query_state']);
member_complete_cert_refresh_update_request($request, $is_member, $member, $config);
