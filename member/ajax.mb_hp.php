<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$member_request_context = member_get_runtime_request_context();
$request = member_read_ajax_identity_request($member_request_context['post']);
member_process_ajax_mb_hp($request);
