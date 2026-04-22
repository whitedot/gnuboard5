<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_bootstrap_collect_menu_files()
{
    $amenu = array();
    $menu_files = array();
    $tmp = dir(G5_ADMIN_PATH);

    while ($entry = $tmp->read()) {
        if (!preg_match('/^admin.menu([0-9]{3}).*\.php$/', $entry, $m)) {
            continue;
        }

        $amenu[$m[1]] = $entry;
        $menu_files[] = G5_ADMIN_PATH . '/' . $entry;
    }

    @asort($menu_files);
    @ksort($amenu);

    return array(
        'amenu' => $amenu,
        'menu_files' => $menu_files,
    );
}

function admin_bootstrap_load_menus()
{
    global $menu;

    unset($menu);
    unset($amenu);

    $menu_state = admin_bootstrap_collect_menu_files();
    foreach ($menu_state['menu_files'] as $file) {
        include_once $file;
    }

    $amenu = run_replace('admin_amenu', $menu_state['amenu']);
    if (isset($menu) && $menu) {
        $menu = run_replace('admin_menu', $menu);
    }

    return array(
        'amenu' => $amenu,
        'menu' => isset($menu) ? $menu : array(),
    );
}
