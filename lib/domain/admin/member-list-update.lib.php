<?php
if (!defined('_GNUBOARD_')) {
    exit;
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
