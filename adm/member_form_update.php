<?php
$sub_menu = "200100";
require_once "./_common.php";
require_once G5_LIB_PATH . "/register.lib.php";

$member_form_request = admin_read_member_form_request($_POST);
$member_list_qstr = admin_build_member_list_qstr($_POST, $config);
$member_request = member_read_admin_member_request($_POST);
admin_complete_member_form_update_request($member_form_request['w'], $member_request, $member, $is_admin, $auth, $sub_menu, $member_list_qstr);
