<?php
$sub_menu = '200400';
require_once './_common.php';

$page_request = admin_build_member_export_stream_page_request(g5_get_runtime_get_input(), $g5, isset($member) && is_array($member) ? $member : array());
admin_complete_member_export_stream_page($page_request, $auth, $sub_menu);
