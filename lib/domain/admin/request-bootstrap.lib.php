<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_bootstrap_build_qstr(array $request)
{
    $arr_query = array();

    foreach (array('sst', 'sod', 'sfl', 'stx', 'page') as $key) {
        if (isset($request[$key])) {
            $arr_query[] = $key . '=' . $request[$key];
        }
    }

    return implode('&amp;', $arr_query);
}

function admin_bootstrap_validate_request(array $request)
{
    if (!$request) {
        return;
    }

    if (admin_referer_check(true)) {
        admin_check_xss_params($request);
    }
}
