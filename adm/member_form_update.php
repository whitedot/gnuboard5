<?php
$sub_menu = "200100";
require_once "./_common.php";

$update_request = admin_read_member_form_update_request(g5_get_runtime_post_input(), $config);
admin_complete_member_form_update_request($update_request, $member, $is_admin, $auth, $sub_menu);
