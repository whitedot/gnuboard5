<?php
if (!defined('_GNUBOARD_')) {
    exit;
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

function admin_validate_member_list_update_request(array $request)
{
    if (empty($request['chk'])) {
        alert($request['act_button'] . ' 하실 항목을 하나 이상 체크하세요.');
    }
}

function admin_member_list_update_error(array $mb, array $member, $is_admin)
{
    $display_mb_id = member_get_display_id($mb);

    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        return $display_mb_id . ' : 회원자료가 존재하지 않습니다.\\n';
    }
    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
        return $display_mb_id . ' : 자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.\\n';
    }
    if ($member['mb_id'] == $mb['mb_id']) {
        return $display_mb_id . ' : 로그인 중인 관리자는 수정 할 수 없습니다.\\n';
    }

    return '';
}

function admin_member_list_delete_error(array $mb, array $member, $is_admin)
{
    $display_mb_id = member_get_display_id($mb);

    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        return $display_mb_id . ' : 회원자료가 존재하지 않습니다.\\n';
    }
    if ($member['mb_id'] == $mb['mb_id']) {
        return $display_mb_id . ' : 로그인 중인 관리자는 삭제 할 수 없습니다.\\n';
    }
    if (is_admin($mb['mb_id']) == 'super') {
        return $display_mb_id . ' : 최고 관리자는 삭제할 수 없습니다.\\n';
    }
    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
        return $display_mb_id . ' : 자신보다 권한이 높거나 같은 회원은 삭제할 수 없습니다.\\n';
    }

    return '';
}

function admin_build_member_list_update_payload(array $request, $index, array $mb)
{
    $post_mb_certify = (!empty($request['mb_certify'][$index])) ? clean_xss_tags($request['mb_certify'][$index], 1, 1, 20) : '';
    $post_mb_level = isset($request['mb_level'][$index]) ? (int) $request['mb_level'][$index] : 0;
    $post_mb_intercept_date = (!empty($request['mb_intercept_date'][$index])) ? clean_xss_tags($request['mb_intercept_date'][$index], 1, 1, 8) : '';
    $post_mb_mailling = isset($request['mb_mailling'][$index]) ? (int) $request['mb_mailling'][$index] : 0;
    $post_mb_mailling_default = isset($request['mb_mailling_default'][$index]) ? (int) $request['mb_mailling_default'][$index] : 0;
    $post_mb_open = isset($request['mb_open'][$index]) ? (int) $request['mb_open'][$index] : 0;
    $mb_adult = $post_mb_certify ? (isset($request['mb_adult'][$index]) ? (int) $request['mb_adult'][$index] : 0) : 0;

    $agree_items = array();
    if ($post_mb_mailling_default != $post_mb_mailling) {
        $agree_items[] = '광고성 이메일 수신(' . ($post_mb_mailling == 1 ? '동의' : '철회') . ')';
    }

    $params = array(
        'mb_level' => $post_mb_level,
        'mb_intercept_date' => $post_mb_intercept_date,
        'mb_mailling' => $post_mb_mailling,
        'mb_open' => $post_mb_open,
        'mb_certify' => $post_mb_certify,
        'mb_adult' => $mb_adult,
        'mb_id' => $mb['mb_id'],
    );

    $sql_mailling_date = '';
    if ($post_mb_mailling_default != $post_mb_mailling) {
        $sql_mailling_date = ' , mb_mailling_date = :mb_mailling_date ';
        $params['mb_mailling_date'] = G5_TIME_YMDHIS;
    }

    $sql_agree_log = '';
    if (!empty($agree_items)) {
        $sql_agree_log = ' , mb_agree_log = :mb_agree_log';
        $params['mb_agree_log'] = '[' . G5_TIME_YMDHIS . ', 회원관리 선택수정] ' . implode(' | ', $agree_items) . "\n" . (isset($mb['mb_agree_log']) ? $mb['mb_agree_log'] : '');
    }

    return array('sql_mailling_date' => $sql_mailling_date, 'sql_agree_log' => $sql_agree_log, 'params' => $params);
}

function admin_apply_member_list_update(array $payload)
{
    global $g5;

    $sql = " update {$g5['member_table']}
                set mb_level = :mb_level,
                    mb_intercept_date = :mb_intercept_date,
                    mb_mailling = :mb_mailling,
                    mb_open = :mb_open,
                    mb_certify = :mb_certify,
                    mb_adult = :mb_adult
                    {$payload['sql_mailling_date']}
                    {$payload['sql_agree_log']}
              where mb_id = :mb_id ";

    sql_query_prepared($sql, $payload['params']);
}

function admin_process_member_list_update(array $request, array $member, $is_admin)
{
    $mb_datas = array();
    $msg = '';

    foreach ($request['chk'] as $selected_index) {
        $k = (int) $selected_index;
        $mb_id = isset($request['mb_id'][$k]) ? $request['mb_id'][$k] : '';
        $mb_datas[] = $mb = get_member($mb_id);
        $error = admin_member_list_update_error($mb, $member, $is_admin);
        if ($error !== '') {
            $msg .= $error;
            continue;
        }

        admin_apply_member_list_update(admin_build_member_list_update_payload($request, $k, $mb));
    }

    return array('mb_datas' => $mb_datas, 'msg' => $msg);
}

function admin_process_member_list_delete(array $request, array $member, $is_admin)
{
    $mb_datas = array();
    $msg = '';

    foreach ($request['chk'] as $selected_index) {
        $k = (int) $selected_index;
        $mb_id = isset($request['mb_id'][$k]) ? $request['mb_id'][$k] : '';
        $mb_datas[] = $mb = get_member($mb_id);
        $error = admin_member_list_delete_error($mb, $member, $is_admin);
        if ($error !== '') {
            $msg .= $error;
            continue;
        }

        member_delete($mb['mb_id']);
    }

    return array('mb_datas' => $mb_datas, 'msg' => $msg);
}

function admin_complete_member_list_update_request(array $request, array $member, $is_admin, $auth, $sub_menu, $qstr)
{
    admin_validate_member_list_update_request($request);

    auth_check_menu($auth, $sub_menu, 'w');
    check_admin_token();

    if ($request['act_button'] == '선택수정') {
        $result = admin_process_member_list_update($request, $member, $is_admin);
    } elseif ($request['act_button'] == '선택삭제') {
        $result = admin_process_member_list_delete($request, $member, $is_admin);
    } else {
        alert('제대로 된 값이 넘어오지 않았습니다.');
    }

    if ($result['msg']) {
        alert($result['msg']);
    }

    run_event('admin_member_list_update', $request['act_button'], $result['mb_datas']);
    goto_url('./member_list.php?' . $qstr);
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

function admin_build_member_list_search(array $request, array $member, $is_admin)
{
    $search_params = array();
    $sql_search = ' where (1) ';

    if ($request['stx'] !== '') {
        if ($request['sfl'] === 'mb_level') {
            $sql_search .= " and {$request['sfl']} = :stx_exact ";
            $search_params['stx_exact'] = (int) $request['stx'];
        } elseif ($request['sfl'] === 'mb_hp') {
            $sql_search .= " and {$request['sfl']} like :stx_suffix ";
            $search_params['stx_suffix'] = '%' . $request['stx'];
        } else {
            $sql_search .= " and {$request['sfl']} like :stx_prefix ";
            $search_params['stx_prefix'] = $request['stx'] . '%';
        }
    }

    if ($is_admin != 'super') {
        $sql_search .= ' and mb_level <= :max_member_level ';
        $search_params['max_member_level'] = (int) $member['mb_level'];
    }

    return array('sql_search' => $sql_search, 'search_params' => $search_params);
}

function admin_build_member_list_filter_query(array $request, array $overrides = array())
{
    return http_build_query(array_merge(array('sfl' => $request['sfl'], 'stx' => $request['stx']), $overrides), '', '&');
}

function admin_build_member_list_actions(array $row, array $member, $is_admin, $qstr)
{
    $actions = array();

    if ($is_admin != 'group') {
        $actions[] = array(
            'type' => 'link',
            'href' => './member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $row['mb_id'],
            'label' => '수정',
            'class' => 'btn btn-sm btn-surface-default-soft',
        );
    }

    if ($member['mb_id'] != $row['mb_id'] && is_admin($row['mb_id']) != 'super' && ($is_admin == 'super' || $row['mb_level'] < $member['mb_level'])) {
        $actions[] = array(
            'type' => 'delete',
            'mb_id' => $row['mb_id'],
            'label' => '삭제',
            'class' => 'btn btn-sm btn-outline-danger',
        );
    }

    return $actions;
}

function admin_build_member_list_item(array $row, array $member, $is_admin, $qstr)
{
    $status_label = '정상';
    $status_class = 'is-normal';

    if ($row['mb_leave_date']) {
        $status_label = '탈퇴';
        $status_class = 'is-left';
    } elseif ($row['mb_intercept_date']) {
        $status_label = '차단';
        $status_class = 'is-blocked';
    }

    return array(
        'mb_id' => $row['mb_id'],
        'display_mb_id' => member_get_display_id($row),
        'mb_name' => get_text($row['mb_name']),
        'mb_nick_text' => get_text($row['mb_nick']),
        'mb_email' => get_text($row['mb_email']),
        'mb_level' => (int) $row['mb_level'],
        'status_label' => $status_label,
        'status_class' => $status_class,
        'actions' => admin_build_member_list_actions($row, $member, $is_admin, $qstr),
    );
}

function admin_build_member_list_view(array $request, array $member, $is_admin, array $config, $qstr)
{
    global $g5;

    $sql_common = " from {$g5['member_table']} ";
    $search = admin_build_member_list_search($request, $member, $is_admin);
    $sql_search = $search['sql_search'];
    $search_params = $search['search_params'];
    $sql_order = " order by {$request['sst']} {$request['sod']} ";

    $total_count = (int) sql_fetch_value_prepared(" select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ", $search_params);
    $total_page = (int) ceil($total_count / $request['rows']);
    $from_record = ($request['page'] - 1) * $request['rows'];
    $leave_count = (int) sql_fetch_value_prepared(" select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ", $search_params);
    $intercept_count = (int) sql_fetch_value_prepared(" select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ", $search_params);

    $list_params = $search_params;
    $list_params['from_record'] = (int) $from_record;
    $list_params['page_rows'] = (int) $request['rows'];
    $result = sql_query_prepared(" select * {$sql_common} {$sql_search} {$sql_order} limit :from_record, :page_rows ", $list_params);

    $items = array();
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $items[] = admin_build_member_list_item($row, $member, $is_admin, $qstr);
    }

    $quick_view = 'all';
    if ($request['sst'] === 'mb_intercept_date' && $request['sod'] === 'desc') {
        $quick_view = 'blocked';
    } elseif ($request['sst'] === 'mb_leave_date' && $request['sod'] === 'desc') {
        $quick_view = 'left';
    }

    return array(
        'list_all_url' => $_SERVER['SCRIPT_NAME'],
        'quick_view' => $quick_view,
        'blocked_url' => '?' . admin_build_member_list_filter_query($request, array('sst' => 'mb_intercept_date', 'sod' => 'desc')),
        'left_url' => '?' . admin_build_member_list_filter_query($request, array('sst' => 'mb_leave_date', 'sod' => 'desc')),
        'total_count' => $total_count,
        'total_page' => $total_page,
        'leave_count' => $leave_count,
        'intercept_count' => $intercept_count,
        'items' => $items,
        'colspan' => 8,
        'empty_message' => '자료가 없습니다.',
        'admin_token' => get_admin_token(),
        'paging_url' => '?' . $qstr . '&amp;page=',
        'title' => '회원관리',
        'admin_container_class' => 'admin-page-member-list',
        'admin_page_subtitle' => '회원 상태를 한눈에 확인하고, 조건 검색과 빠른 관리 동선을 자연스럽게 이어가세요.',
    );
}

function admin_build_member_list_page_view(array $request, array $member, $is_admin, array $config, $qstr)
{
    return admin_build_member_list_view($request, $member, $is_admin, $config, $qstr);
}

function admin_build_dashboard_view(array $request, array $member, $is_admin, array $auth)
{
    global $g5;

    $can_read_member_menu = !auth_check_menu($auth, '200100', 'r', true);
    $view = array(
        'title' => '관리자메인',
        'admin_container_class' => 'admin-page-dashboard',
        'admin_page_subtitle' => '신규 가입 회원 현황을 빠르게 확인하고 회원 관리 화면으로 자연스럽게 이어가세요.',
        'can_read_member_menu' => $can_read_member_menu,
        'new_member_rows' => 5,
        'total_count' => 0,
        'leave_count' => 0,
        'intercept_count' => 0,
        'items' => array(),
        'colspan' => 8,
    );

    if (!$can_read_member_menu) {
        return $view;
    }

    $allowed_sort_fields = array('mb_datetime', 'mb_id', 'mb_name', 'mb_nick', 'mb_level', 'mb_mailling', 'mb_open', 'mb_email_certify', 'mb_intercept_date');
    $sort_field = (isset($request['sst']) && in_array($request['sst'], $allowed_sort_fields, true)) ? $request['sst'] : 'mb_datetime';
    $sort_direction = (isset($request['sod']) && strtolower((string) $request['sod']) === 'asc') ? 'asc' : 'desc';

    $sql_common = " from {$g5['member_table']} ";
    $conditions = array('(1)');
    $query_params = array();
    if ($is_admin != 'super') {
        $conditions[] = 'mb_level <= :admin_mb_level';
        $query_params['admin_mb_level'] = (int) $member['mb_level'];
    }

    $sql_search = ' where ' . implode(' and ', $conditions);
    $sql_order = " order by {$sort_field} {$sort_direction} ";
    $view['total_count'] = (int) sql_fetch_value_prepared(" SELECT count(*) as cnt {$sql_common} {$sql_search} ", $query_params);
    $view['leave_count'] = (int) sql_fetch_value_prepared(" select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' ", $query_params);
    $view['intercept_count'] = (int) sql_fetch_value_prepared(" SELECT count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' ", $query_params);

    $list_params = $query_params;
    $list_params['new_member_rows'] = (int) $view['new_member_rows'];
    $result = sql_query_prepared(" SELECT * {$sql_common} {$sql_search} {$sql_order} limit :new_member_rows ", $list_params);

    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $view['items'][] = array(
            'mb_id' => $row['mb_id'],
            'display_mb_id' => member_get_display_id($row),
            'mb_name' => get_text($row['mb_name']),
            'mb_nick_text' => get_text($row['mb_nick']),
            'mb_email' => get_text($row['mb_email']),
            'mb_level' => $row['mb_level'],
            'mb_mailling' => $row['mb_mailling'] ? '예' : '아니오',
            'mb_open' => $row['mb_open'] ? '예' : '아니오',
            'mb_email_certify' => preg_match('/[1-9]/', $row['mb_email_certify']) ? '예' : '아니오',
            'mb_intercept_date' => $row['mb_intercept_date'] ? '예' : '아니오',
        );
    }

    return $view;
}

function admin_build_dashboard_page_view(array $request, array $member, $is_admin, array $auth)
{
    return admin_build_dashboard_view($request, $member, $is_admin, $auth);
}

function admin_read_dashboard_request(array $request)
{
    return array(
        'sst' => isset($request['sst']) && !is_array($request['sst']) ? trim((string) $request['sst']) : '',
        'sod' => isset($request['sod']) && !is_array($request['sod']) ? trim((string) $request['sod']) : '',
    );
}
