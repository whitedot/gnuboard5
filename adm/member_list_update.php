<?php
$sub_menu = "200100";
require_once './_common.php';

check_demo();

$request = admin_read_member_list_update_request($_POST);
admin_complete_member_list_update_request($request, $member, $is_admin, $auth, $sub_menu, $qstr);
