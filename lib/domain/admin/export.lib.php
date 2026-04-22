<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_delete_directory_tree($folder_path)
{
    $items = glob($folder_path . '/*');
    if (!is_array($items)) {
        $items = array();
    }

    foreach ($items as $item) {
        if (is_dir($item)) {
            admin_delete_directory_tree($item);
            continue;
        }

        @unlink($item);
    }

    @rmdir($folder_path);
}

function admin_process_member_list_file_delete()
{
    $base_path = G5_DATA_PATH . '/member_list';
    $messages = array();
    $count = 0;

    if (!@opendir($base_path)) {
        return array(
            'messages' => array('회원관리파일를 열지못했습니다.'),
            'count' => 0,
        );
    }

    $files = glob($base_path . '/*');
    if (!is_array($files)) {
        $files = array();
    }

    foreach ($files as $member_list_file) {
        $ext = strtolower(pathinfo($member_list_file, PATHINFO_EXTENSION));
        $basename = basename($member_list_file);

        if (is_file($member_list_file) && $ext !== 'log') {
            @unlink($member_list_file);
            $messages[] = '파일 삭제: ' . $member_list_file;
            $count++;
            continue;
        }

        if (is_dir($member_list_file) && $basename !== 'log') {
            admin_delete_directory_tree($member_list_file);
            $messages[] = '폴더 삭제: ' . $member_list_file;
            $count++;
        }
    }

    $messages[] = '완료됨';

    return array(
        'messages' => $messages,
        'count' => $count,
    );
}

function admin_complete_member_list_file_delete_request($is_admin)
{
    admin_require_super_admin($is_admin);

    return array(
        'title' => '회원관리파일 일괄삭제',
        'result' => admin_process_member_list_file_delete(),
    );
}

function admin_member_export_ad_range_texts()
{
    return array(
        'all' => '* <b>광고성 이메일 수신</b> / <b>마케팅 목적의 개인정보 수집 및 이용</b>에 모두 동의한 회원을 선택합니다.',
        'mailling_only' => '* <b>광고성 이메일 수신</b> / <b>마케팅 목적의 개인정보 수집 및 이용</b>에 모두 동의한 회원을 선택합니다.',
        'month_confirm' => '* 23개월 전(' . date('Y년 m월', strtotime('-23 month')) . ') <b>광고성 이메일 수신 동의</b>한 회원을 선택합니다.',
    );
}

function admin_build_member_export_view(array $query, array $config)
{
    $params = get_member_export_params($query);
    $total_count = 0;
    $total_error = '';

    try {
        $total_count = member_export_get_total_count($params);
    } catch (Exception $e) {
        $total_error = $e->getMessage();
    }

    $has_token = isset($query['token']);
    $ad_range_texts = admin_member_export_ad_range_texts();

    return array(
        'title' => '회원관리파일',
        'params' => $params,
        'colspan' => 14,
        'form_token' => get_token(),
        'total_count' => $total_count,
        'total_error' => $total_error,
        'sfl_options' => get_export_config('sfl_list'),
        'intercept_options' => get_export_config('intercept_list'),
        'ad_range_options' => get_export_config('ad_range_list'),
        'use_hp_checked' => $has_token
            ? (!empty($query['use_hp_exist']) ? 'checked' : '')
            : (($config['cf_use_hp'] || $config['cf_req_hp']) ? 'checked' : ''),
        'ad_mailling_checked' => $has_token
            ? (!empty($query['ad_mailling']) ? 'checked' : '')
            : 'checked',
        'agree_date_start_value' => !empty($params['agree_date_start']) ? $params['agree_date_start'] : date('Y-m-d', strtotime('-1 month')),
        'agree_date_end_value' => !empty($params['agree_date_end']) ? $params['agree_date_end'] : date('Y-m-d'),
        'active_ad_range_text' => (!empty($query['ad_range_only']) && isset($ad_range_texts[$params['ad_range_type']]))
            ? $ad_range_texts[$params['ad_range_type']]
            : '',
    );
}

function admin_build_member_export_page_view(array $query, array $config)
{
    return admin_build_member_export_view($query, $config);
}

function admin_prepare_member_export_runtime()
{
    ini_set('memory_limit', '-1');
    session_write_close();
}

function admin_read_member_export_stream_request(array $query)
{
    return array(
        'mode' => isset($query['mode']) ? trim((string) $query['mode']) : '',
        'params' => get_member_export_params($query),
    );
}

function admin_validate_member_export_stream_request(array $request)
{
    if (empty($request['params']) || !is_array($request['params'])) {
        return '데이터가 올바르게 전달되지 않아 작업에 실패하였습니다.';
    }

    if ($request['mode'] !== 'start') {
        return '잘못된 요청 입니다.';
    }

    return '';
}

function admin_fail_member_export_stream(array $params, $message)
{
    member_export_send_progress('error', $message);
    member_export_write_log($params, array(
        'success' => false,
        'error' => $message,
    ));
}

function admin_complete_member_export_stream_request(array $query, $auth, $sub_menu)
{
    auth_check_menu($auth, $sub_menu, 'w');

    admin_prepare_member_export_runtime();

    $request = admin_read_member_export_stream_request($query);
    $params = $request['params'];

    member_export_delete();
    member_export_set_sse_headers();

    $stream_error = admin_validate_member_export_stream_request($request);
    if ($stream_error !== '') {
        admin_fail_member_export_stream(is_array($params) ? $params : array(), $stream_error);
        exit;
    }

    return array(
        'request' => $request,
        'params' => $params,
    );
}
