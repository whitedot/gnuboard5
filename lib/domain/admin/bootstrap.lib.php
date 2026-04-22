<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

require_once __DIR__ . '/access-bootstrap.lib.php';
require_once __DIR__ . '/menu-bootstrap.lib.php';
require_once __DIR__ . '/request-bootstrap.lib.php';

$auth = admin_bootstrap_require_access($member, $is_admin);
admin_bootstrap_verify_client($member);

$menu_state = admin_bootstrap_load_menus();
$amenu = $menu_state['amenu'];
$menu = $menu_state['menu'];
$qstr = admin_bootstrap_build_qstr($_REQUEST);

if (isset($_REQUEST) && $_REQUEST) {
    admin_bootstrap_validate_request($_REQUEST);
}
