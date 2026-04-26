<?php
$sub_menu = "200100";
require_once './_common.php';

check_demo();

$admin_post_input = g5_get_runtime_post_input();
$member_list_qstr = admin_build_member_list_qstr($admin_post_input, $config);
$request = admin_read_member_list_update_request($admin_post_input);
admin_complete_member_list_update_request($request, $member, $is_admin, $auth, $sub_menu, $member_list_qstr);
