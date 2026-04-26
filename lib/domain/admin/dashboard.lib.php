<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_read_dashboard_request(array $request)
{
    return array(
        'sst' => isset($request['sst']) && !is_array($request['sst']) ? trim((string) $request['sst']) : '',
        'sod' => isset($request['sod']) && !is_array($request['sod']) ? trim((string) $request['sod']) : '',
    );
}

function admin_dashboard_allowed_sort_fields()
{
    return array('mb_datetime', 'mb_id', 'mb_name', 'mb_nick', 'mb_level', 'mb_mailling', 'mb_open', 'mb_email_certify', 'mb_intercept_date');
}

function admin_build_dashboard_query_context(array $request, array $member, $is_admin)
{
    $sort_field = in_array($request['sst'], admin_dashboard_allowed_sort_fields(), true) ? $request['sst'] : 'mb_datetime';
    $sort_direction = strtolower((string) $request['sod']) === 'asc' ? 'asc' : 'desc';
    $conditions = array('(1)');
    $query_params = array();

    if ($is_admin != 'super') {
        $conditions[] = 'mb_level <= :admin_mb_level';
        $query_params['admin_mb_level'] = (int) $member['mb_level'];
    }

    return array(
        'sql_search' => ' where ' . implode(' and ', $conditions),
        'sql_order' => " order by {$sort_field} {$sort_direction} ",
        'query_params' => $query_params,
    );
}

function admin_build_dashboard_member_item(array $row)
{
    return array(
        'mb_id' => $row['mb_id'],
        'display_mb_id' => member_get_display_id($row),
        'mb_name' => get_text($row['mb_name']),
        'mb_nick_text' => get_text($row['mb_nick']),
        'sideview_html' => get_sideview($row['mb_id'], get_text($row['mb_nick']), get_text($row['mb_email'])),
        'mb_email' => get_text($row['mb_email']),
        'mb_level' => $row['mb_level'],
        'mb_mailling' => $row['mb_mailling'] ? '예' : '아니오',
        'mb_open' => $row['mb_open'] ? '예' : '아니오',
        'mb_email_certify' => preg_match('/[1-9]/', $row['mb_email_certify']) ? '예' : '아니오',
        'mb_intercept_date' => $row['mb_intercept_date'] ? '예' : '아니오',
    );
}

function admin_build_dashboard_empty_view(array $member, $is_admin, array $auth)
{
    return array(
        'title' => '관리자메인',
        'admin_container_class' => 'admin-page-dashboard',
        'admin_page_subtitle' => '신규 가입 회원 현황을 빠르게 확인하고 회원 관리 화면으로 자연스럽게 이어가세요.',
        'additional_content_before' => run_replace('adm_index_addtional_content_before', '', $is_admin, $auth, $member),
        'additional_content_after' => run_replace('adm_index_addtional_content_after', '', $is_admin, $auth, $member),
        'can_read_member_menu' => !auth_check_menu($auth, '200100', 'r', true),
        'new_member_rows' => 5,
        'total_count' => 0,
        'leave_count' => 0,
        'intercept_count' => 0,
        'items' => array(),
        'colspan' => 8,
    );
}

function admin_build_dashboard_view(array $request, array $member, $is_admin, array $auth)
{
    global $g5;

    $view = admin_build_dashboard_empty_view($member, $is_admin, $auth);
    if (!$view['can_read_member_menu']) {
        return $view;
    }

    $sql_common = " from {$g5['member_table']} ";
    $query = admin_build_dashboard_query_context($request, $member, $is_admin);
    $query_params = $query['query_params'];

    $view['total_count'] = (int) sql_fetch_value_prepared(" SELECT count(*) as cnt {$sql_common} {$query['sql_search']} ", $query_params);
    $view['leave_count'] = (int) sql_fetch_value_prepared(" select count(*) as cnt {$sql_common} {$query['sql_search']} and mb_leave_date <> '' ", $query_params);
    $view['intercept_count'] = (int) sql_fetch_value_prepared(" SELECT count(*) as cnt {$sql_common} {$query['sql_search']} and mb_intercept_date <> '' ", $query_params);

    $list_params = $query_params;
    $list_params['new_member_rows'] = (int) $view['new_member_rows'];
    $result = sql_query_prepared(" SELECT * {$sql_common} {$query['sql_search']} {$query['sql_order']} limit :new_member_rows ", $list_params);

    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $view['items'][] = admin_build_dashboard_member_item($row);
    }

    return $view;
}

function admin_build_dashboard_page_view(array $request, array $member, $is_admin, array $auth)
{
    return admin_build_dashboard_view($request, $member, $is_admin, $auth);
}
