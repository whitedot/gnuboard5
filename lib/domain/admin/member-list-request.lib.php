<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_member_list_allowed_search_fields()
{
    return array('mb_id', 'mb_nick', 'mb_name', 'mb_level', 'mb_email', 'mb_hp', 'mb_datetime', 'mb_ip');
}

function admin_member_list_allowed_sort_fields()
{
    return array('mb_id', 'mb_name', 'mb_nick', 'mb_email', 'mb_level', 'mb_datetime', 'mb_intercept_date', 'mb_leave_date');
}

function admin_read_member_list_request(array $request, array $config)
{
    $sfl = isset($request['sfl']) && !is_array($request['sfl']) ? trim((string) $request['sfl']) : '';
    $stx = isset($request['stx']) && !is_array($request['stx']) ? trim((string) $request['stx']) : '';
    $sst = isset($request['sst']) && !is_array($request['sst']) ? trim((string) $request['sst']) : '';
    $sod = isset($request['sod']) && !is_array($request['sod']) ? trim((string) $request['sod']) : '';
    $page = isset($request['page']) ? (int) $request['page'] : 1;
    $rows = isset($config['cf_page_rows']) ? (int) $config['cf_page_rows'] : 30;

    return array(
        'sfl' => in_array($sfl, admin_member_list_allowed_search_fields(), true) ? $sfl : 'mb_id',
        'stx' => $stx,
        'sst' => in_array($sst, admin_member_list_allowed_sort_fields(), true) ? $sst : 'mb_datetime',
        'sod' => strtolower($sod) === 'asc' ? 'asc' : 'desc',
        'page' => $page > 0 ? $page : 1,
        'rows' => $rows > 0 ? $rows : 30,
    );
}

function admin_build_member_list_qstr(array $request, array $config)
{
    return admin_bootstrap_build_qstr(admin_read_member_list_request($request, $config));
}

function admin_read_member_list_update_request(array $post)
{
    return array(
        'act_button' => isset($post['act_button']) ? trim((string) $post['act_button']) : '',
        'chk' => (isset($post['chk']) && is_array($post['chk'])) ? $post['chk'] : array(),
        'mb_id' => isset($post['mb_id']) && is_array($post['mb_id']) ? $post['mb_id'] : array(),
        'mb_certify' => isset($post['mb_certify']) && is_array($post['mb_certify']) ? $post['mb_certify'] : array(),
        'mb_level' => isset($post['mb_level']) && is_array($post['mb_level']) ? $post['mb_level'] : array(),
        'mb_intercept_date' => isset($post['mb_intercept_date']) && is_array($post['mb_intercept_date']) ? $post['mb_intercept_date'] : array(),
        'mb_mailling' => isset($post['mb_mailling']) && is_array($post['mb_mailling']) ? $post['mb_mailling'] : array(),
        'mb_mailling_default' => isset($post['mb_mailling_default']) && is_array($post['mb_mailling_default']) ? $post['mb_mailling_default'] : array(),
        'mb_open' => isset($post['mb_open']) && is_array($post['mb_open']) ? $post['mb_open'] : array(),
        'mb_adult' => isset($post['mb_adult']) && is_array($post['mb_adult']) ? $post['mb_adult'] : array(),
    );
}
