<?php
$sub_menu = "100100";
require_once './_common.php';

check_demo();

$ori_config = admin_read_config_row();
$config_form_request = admin_read_config_form_update_request(g5_get_runtime_post_input(), $ori_config);
admin_complete_config_form_update_request($config_form_request, $ori_config, $auth, $sub_menu, $is_admin);
