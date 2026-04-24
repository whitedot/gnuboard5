<?php
$sub_menu = "200100";
require_once "./_common.php";

check_demo();

$member_list_qstr = admin_build_member_list_qstr($_POST, $config);
$request = admin_read_member_delete_request($_POST);
admin_complete_member_delete_request($request, $member, $auth, $sub_menu, $member_list_qstr);
