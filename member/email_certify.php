<?php
include_once('./_common.php');

$member_request_context = member_get_runtime_request_context();
$request = member_read_email_certify_request($member_request_context['request']);
member_complete_email_certify_request($request);
