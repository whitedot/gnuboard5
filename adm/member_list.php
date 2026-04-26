<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$member_list_request = admin_read_member_list_request(g5_get_runtime_get_input(), $config);
$member_list_qstr = admin_bootstrap_build_qstr($member_list_request);
$member_list_view = admin_build_member_list_page_view($member_list_request, $member, $is_admin, $config, $member_list_qstr);

$g5['title'] = $member_list_view['title'];
$admin_container_class = $member_list_view['admin_container_class'];
$admin_page_subtitle = $member_list_view['admin_page_subtitle'];
require_once './admin.head.php';
include_once G5_ADMIN_PATH . '/member_list_parts/summary.php';
include_once G5_ADMIN_PATH . '/member_list_parts/search.php';
include_once G5_ADMIN_PATH . '/member_list_parts/table.php';
require_once './admin.tail.php';
