<?php
$sub_menu = "200100";
require_once "./_common.php";

$delete_action_request = admin_read_member_delete_action_request($_POST, $config);
admin_complete_member_delete_request($delete_action_request, $member, $auth, $sub_menu);
