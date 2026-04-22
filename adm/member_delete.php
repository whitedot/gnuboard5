<?php
$sub_menu = "200100";
require_once "./_common.php";

check_demo();

$request = admin_read_member_delete_request($_POST, isset($url) ? $url : '');
admin_complete_member_delete_request($request, $member, $auth, $sub_menu, $qstr);
