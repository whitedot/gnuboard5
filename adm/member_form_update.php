<?php
$sub_menu = "200100";
require_once "./_common.php";
require_once G5_LIB_PATH . "/register.lib.php";

$member_request = member_read_admin_member_request($_POST);
admin_complete_member_form_update_request($w, $member_request, $member, $is_admin, $auth, $sub_menu, $qstr);
