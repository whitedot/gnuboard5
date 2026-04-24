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
        'admin_container_class' => 'admin-page-member-export-delete',
        'admin_page_subtitle' => '서버에 남아 있는 회원 내보내기 산출물을 정리하고 삭제 결과를 바로 확인하세요.',
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

function admin_build_member_export_filter_state(array $query, array $config, array $params)
{
    $has_token = isset($query['token']);
    $ad_range_texts = admin_member_export_ad_range_texts();

    return array(
        'params' => $params,
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

function admin_build_member_export_links()
{
    return array(
        'file_delete_url' => G5_ADMIN_URL . '/member_list_file_delete.php',
        'member_list_url' => G5_ADMIN_URL . '/member_list.php',
        'reset_url' => G5_ADMIN_URL . '/member_list_exel.php',
        'stream_url' => G5_ADMIN_URL . '/member_list_exel_export.php',
    );
}

function admin_build_member_export_client_config(array $links)
{
    return array(
        'stream_url' => $links['stream_url'],
        'popup_progress_title' => '엑셀 다운로드 진행 중',
        'popup_done_title' => '엑셀 파일 다운로드 완료',
        'close_confirm_message' => "엑셀 다운로드가 진행 중입니다.\n정말 중지하시겠습니까?",
        'download_stopped_message' => '엑셀 다운로드가 중단되었습니다.',
        'download_failed_summary' => '엑셀 파일 다운로드 실패',
        'download_failed_message' => '엑셀 파일 다운로드에 실패하였습니다',
        'estimated_seconds_multiplier' => 0.00144,
        'estimated_seconds_min' => 5,
    );
}

function admin_build_member_export_runtime_context(array $tables, array $member_row = array())
{
    return array(
        'member_table' => isset($tables['member_table']) ? $tables['member_table'] : '',
        'actor_id' => isset($member_row['mb_id']) ? $member_row['mb_id'] : 'guest',
    );
}

function admin_build_member_export_page_view(array $query, array $config, array $runtime)
{
    $params = get_member_export_params($query);
    $total_count = 0;
    $total_error = '';

    try {
        $total_count = member_export_get_total_count($params, $runtime['member_table']);
    } catch (Exception $e) {
        $total_error = $e->getMessage();
    }

    $filter_state = admin_build_member_export_filter_state($query, $config, $params);
    $links = admin_build_member_export_links();

    return array(
        'title' => '회원관리파일',
        'admin_container_class' => 'admin-page-member-export',
        'admin_page_subtitle' => '회원 조건을 조합해 내보내기 범위를 좁히고, 대용량 다운로드 진행 상태까지 한 화면에서 확인하세요.',
        'colspan' => 14,
        'form_token' => get_token(),
        'total_count' => $total_count,
        'total_error' => $total_error,
        'sfl_options' => get_export_config('sfl_list'),
        'intercept_options' => get_export_config('intercept_list'),
        'ad_range_options' => get_export_config('ad_range_list'),
        'filter_state' => $filter_state,
        'links' => $links,
        'client_config' => admin_build_member_export_client_config($links),
    );
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

function admin_fail_member_export_stream(array $params, $message, array $runtime)
{
    member_export_send_progress('error', $message);
    member_export_write_log($params, array(
        'success' => false,
        'error' => $message,
    ), $runtime['actor_id']);
}

function admin_complete_member_export_stream_request(array $query, $auth, $sub_menu, array $runtime)
{
    auth_check_menu($auth, $sub_menu, 'w');

    admin_prepare_member_export_runtime();

    $request = admin_read_member_export_stream_request($query);
    $params = $request['params'];

    member_export_delete();
    member_export_set_sse_headers();

    $stream_error = admin_validate_member_export_stream_request($request);
    if ($stream_error !== '') {
        admin_fail_member_export_stream(is_array($params) ? $params : array(), $stream_error, $runtime);
        exit;
    }

    return array(
        'request' => $request,
        'params' => $params,
        'runtime' => $runtime,
    );
}

function admin_run_member_export(array $params, array $runtime)
{
    $total = member_export_get_total_count($params, $runtime['member_table']);
    $pages = 1;

    if ($total > MEMBER_EXPORT_MAX_SIZE) {
        throw new Exception('엑셀 다운로드 가능 범위(최대 ' . number_format(MEMBER_EXPORT_MAX_SIZE) . '건)를 초과했습니다.<br>조건을 추가로 설정하신 후 다시 시도해 주세요.');
    }

    if ($total <= 0) {
        throw new Exception('조회된 데이터가 없어 엑셀 파일을 생성할 수 없습니다.<br>조건을 추가로 설정하신 후 다시 시도해 주세요.');
    }

    $file_name = 'member_' . MEMBER_BASE_DATE;
    $file_list = array();
    $zip_file_name = '';

    if ($total > MEMBER_EXPORT_PAGE_SIZE) {
        $pages = (int) ceil($total / MEMBER_EXPORT_PAGE_SIZE);
        member_export_send_progress('progress', '', 2, $total, 0, $pages, 0);

        for ($i = 1; $i <= $pages; $i++) {
            $params['page'] = $i;
            member_export_send_progress('progress', '', 2, $total, ($pages == $i ? $total : $i * MEMBER_EXPORT_PAGE_SIZE), $pages, $i);

            try {
                $file_list[] = member_export_create_xlsx($params, $file_name, $i, $runtime['member_table']);
            } catch (Exception $e) {
                throw new Exception('총 ' . $pages . '개 중 ' . $i . '번째 파일을 생성하지 못했습니다<br>' . $e->getMessage());
            }
        }

        if (count($file_list) > 1) {
            member_export_send_progress('zipping', '', 2, $total, $total, $pages, $i);
            $zip_result = member_export_create_zip($file_list, $file_name);

            if ($zip_result['error']) {
                member_export_write_log($params, array('success' => false, 'error' => $zip_result['error']), $runtime['actor_id']);
                member_export_send_progress('zippingError', $zip_result['error']);
            }

            if ($zip_result && $zip_result['result']) {
                member_export_delete($file_list);
                $zip_file_name = $zip_result['zipFile'];
            }
        }
    } else {
        member_export_send_progress('progress', '', 1, $total, 0);
        member_export_send_progress('progress', '', 1, $total, $total / 2);
        $file_list[] = member_export_create_xlsx($params, $file_name, 0, $runtime['member_table']);
        member_export_send_progress('progress', '', 1, $total, $total);
    }

    member_export_write_log($params, array(
        'success' => true,
        'total' => $total,
        'files' => $file_list,
        'zip' => $zip_file_name !== '' ? $zip_file_name : null,
    ), $runtime['actor_id']);
    member_export_send_progress('done', '', 2, $total, $total, $pages, $pages, $file_list, $zip_file_name);
}

function member_export_send_progress($status, $message = '', $downloadType = 1, $total = 1, $current = 1, $totalChunks = 1, $currentChunk = 1, $files = array(), $zipFile = '')
{
    if (connection_aborted()) {
        return;
    }

    $data = array(
        'status' => $status,
        'message' => $message,
        'downloadType' => $downloadType,
        'total' => $total,
        'current' => $current,
        'totalChunks' => $totalChunks,
        'currentChunk' => $currentChunk,
        'files' => $files,
        'zipFile' => $zipFile,
        'filePath' => G5_DATA_URL . '/' . MEMBER_BASE_DIR . '/' . MEMBER_BASE_DATE,
    );

    echo 'data: ' . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n\n";

    if (ob_get_level()) {
        ob_end_flush();
    }
    flush();
}

function member_export_get_config()
{
    $type = 1;
    $configs = array(
        1 => array(
            'title' => array('회원관리파일(일반)'),
            'headers' => array('아이디', '이름', '닉네임', '휴대폰번호', '이메일', '회원권한', '가입일', '차단', '광고성 이메일 수신동의', '광고성 이메일 동의일자', '마케팅목적의개인정보수집및이용동의', '마케팅목적의개인정보수집및이용동의일자'),
            'fields' => array('mb_id', 'mb_name', 'mb_nick', 'mb_hp', 'mb_email', 'mb_level', 'mb_datetime', 'mb_intercept_date', 'mb_mailling', 'mb_mailling_date', 'mb_marketing_agree', 'mb_marketing_date'),
            'widths' => array(20, 20, 20, 20, 30, 10, 25, 10, 20, 25, 20, 25),
        ),
    );

    return isset($configs[$type]) ? $configs[$type] : $configs[1];
}

function member_export_set_sse_headers()
{
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');
    header('X-Accel-Buffering: no');

    if (ob_get_level()) {
        ob_end_flush();
    }
    ob_implicit_flush(true);
}

function member_export_open_statement($params, $member_table, &$fields = array())
{
    $config = member_export_get_config();
    $fields = array_unique($config['fields']);

    $sql_transform_map = array(
        'mb_datetime' => "IF(mb_datetime = '0000-00-00 00:00:00', '', mb_datetime) AS mb_datetime",
        'mb_intercept_date' => "IF(mb_intercept_date != '', '차단됨', '정상') AS mb_intercept_date",
        'mb_mailling' => "IF(mb_mailling = '1', '동의', '미동의') AS mb_mailling",
        'mb_mailling_date' => "IF(mb_mailling != '1' OR mb_mailling_date = '0000-00-00 00:00:00', '', mb_mailling_date) AS mb_mailling_date",
        'mb_marketing_agree' => "IF(mb_marketing_agree = '1', '동의', '미동의') AS mb_marketing_agree",
        'mb_marketing_date' => "IF(mb_marketing_agree != '1' OR mb_marketing_date = '0000-00-00 00:00:00', '', mb_marketing_date) AS mb_marketing_date",
    );

    $sql_fields = array();
    foreach ($fields as $field) {
        $sql_fields[] = isset($sql_transform_map[$field]) ? $sql_transform_map[$field] : $field;
    }
    $field_list = implode(', ', $sql_fields);

    $where_data = member_export_build_where($params);

    $page = (int) (isset($params['page']) ? $params['page'] : 1);
    if ($page < 1) {
        $page = 1;
    }

    $query_params = $where_data['params'];
    $query_params['offset'] = (int) (($page - 1) * MEMBER_EXPORT_PAGE_SIZE);
    $query_params['page_size'] = (int) MEMBER_EXPORT_PAGE_SIZE;

    $sql = "SELECT {$field_list} FROM {$member_table} {$where_data['clause']} ORDER BY mb_no DESC LIMIT :offset, :page_size";
    $statement = sql_statement_prepared($sql, $query_params, false);
    if (!$statement) {
        throw new Exception('데이터 조회에 실패하였습니다');
    }

    return $statement;
}

function member_export_fetch_sheet_rows($params, array $fields, $member_table)
{
    $statement = member_export_open_statement($params, $member_table, $fields);
    $rows = array();

    try {
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $row_data = array();
            foreach ($fields as $field) {
                $row_data[] = isset($row[$field]) ? (string) $row[$field] : '';
            }
            $rows[] = $row_data;
        }
    } finally {
        $statement->closeCursor();
    }

    return $rows;
}

function member_export_create_xlsx($params, $file_name, $index = 0, $member_table = '')
{
    $config = member_export_get_config();
    $fields = array_unique($config['fields']);

    try {
        $rows = array_merge(
            array($config['title'], $config['headers']),
            member_export_fetch_sheet_rows($params, $fields, $member_table)
        );

        member_export_ensure_directory(MEMBER_EXPORT_DIR);

        $sub_name = $index == 0 ? 'all' : sprintf('%02d', $index);
        $filename = $file_name . '_' . $sub_name . '.xlsx';
        $file_path = MEMBER_EXPORT_DIR . '/' . $filename;

        admin_xlsx_write_file($file_path, $rows, $config['widths'], '회원관리파일', 2);
    } catch (Exception $e) {
        throw new Exception('XLSX 파일 생성에 실패하였습니다: ' . $e->getMessage());
    }

    return $filename;
}

function member_export_create_zip($files, $zip_file_name)
{
    if (!class_exists('ZipArchive')) {
        error_log('[Member Export Error]  ZipArchive 클래스를 사용할 수 없습니다.');
        return array('error' => '파일을 압축하는 중 문제가 발생했습니다. 개별 파일로 제공됩니다.<br>: ZipArchive 클래스를 사용할 수 없습니다.');
    }

    member_export_ensure_directory(MEMBER_EXPORT_DIR);
    $destination_zip_path = rtrim(MEMBER_EXPORT_DIR, '/') . '/' . $zip_file_name . '.zip';

    $zip = new ZipArchive();
    if ($zip->open($destination_zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        return array('error' => '파일을 압축하는 중 문제가 발생했습니다. 개별 파일로 제공됩니다.');
    }

    foreach ($files as $file) {
        $file_path = MEMBER_EXPORT_DIR . '/' . $file;
        if (file_exists($file_path)) {
            $zip->addFile($file_path, basename($file_path));
        }
    }

    $result = $zip->close();

    return array(
        'result' => $result,
        'zipFile' => $zip_file_name . '.zip',
        'zipPath' => $destination_zip_path,
        'error' => '',
    );
}

function member_export_ensure_directory($dir)
{
    if (!is_dir($dir)) {
        if (!@mkdir($dir, G5_DIR_PERMISSION, true)) {
            throw new Exception('디렉토리 생성 실패');
        }
        @chmod($dir, G5_DIR_PERMISSION);
    }

    if (!is_writable($dir)) {
        throw new Exception('디렉토리 쓰기 권한 없음');
    }
}

function member_export_delete($file_list = array())
{
    $count = 0;

    if (!empty($file_list)) {
        foreach ($file_list as $file) {
            $file_path = rtrim(MEMBER_EXPORT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
            if (file_exists($file_path) && is_file($file_path) && @unlink($file_path)) {
                $count++;
            }
        }

        return $count;
    }

    $files = glob(rtrim(G5_DATA_PATH . '/' . MEMBER_BASE_DIR, '/') . '/*');
    if (!is_array($files)) {
        $files = array();
    }

    foreach ($files as $file) {
        $name = basename($file);
        if ($name === 'log' || preg_match('/^' . date('Ymd') . '\d{6}$/', $name)) {
            continue;
        }

        if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) !== 'log' && @unlink($file)) {
            $count++;
            continue;
        }

        if (is_dir($file)) {
            member_export_delete_directory($file);
            $count++;
        }
    }

    return $count;
}

function member_export_delete_directory($dir)
{
    foreach (glob($dir . '/{.,}*', GLOB_BRACE) as $item) {
        if (in_array(basename($item), array('.', '..'), true)) {
            continue;
        }

        if (is_dir($item)) {
            member_export_delete_directory($item);
            continue;
        }

        unlink($item);
    }

    rmdir($dir);
}

function member_export_write_log($params, $result = array(), $actor_id = 'guest')
{
    $max_size = 1024 * 1024 * 2;
    $max_files = 10;
    $username = $actor_id !== '' ? $actor_id : 'guest';
    $datetime = date('Y-m-d H:i:s');

    if (!is_dir(MEMBER_LOG_DIR)) {
        @mkdir(MEMBER_LOG_DIR, G5_DIR_PERMISSION, true);
        @chmod(MEMBER_LOG_DIR, G5_DIR_PERMISSION);
    }

    $log_files = glob(MEMBER_LOG_DIR . '/export_log_*.log');
    if (!is_array($log_files)) {
        $log_files = array();
    }

    usort($log_files, function ($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    $latest_log_file = isset($log_files[0]) ? $log_files[0] : null;
    if (!$latest_log_file || filesize($latest_log_file) >= $max_size) {
        $latest_log_file = MEMBER_LOG_DIR . '/export_log_' . date('YmdHi') . '.log';
        file_put_contents($latest_log_file, '');
        array_unshift($log_files, $latest_log_file);
    }

    if (count($log_files) > $max_files) {
        foreach (array_slice($log_files, $max_files) as $file) {
            @unlink($file);
        }
    }

    $success = isset($result['success']) && $result['success'] === true;
    $status = $success ? '성공' : '실패';
    $condition = array();

    if ($params['use_stx'] == 1 && !empty($params['stx'])) {
        $sfl_list = get_export_config('sfl_list');
        $label = isset($sfl_list[$params['sfl']]) ? $sfl_list[$params['sfl']] : '';
        $condition[] = '검색(' . $params['stx_cond'] . ') : ' . $label . ' - ' . $params['stx'];
    }

    if ($params['use_level'] == 1 && ($params['level_start'] || $params['level_end'])) {
        $condition[] = '레벨: ' . $params['level_start'] . '~' . $params['level_end'];
    }

    if ($params['use_date'] == 1 && ($params['date_start'] || $params['date_end'])) {
        $condition[] = '가입일: ' . $params['date_start'] . '~' . $params['date_end'];
    }

    if ($params['use_hp_exist'] == 1) {
        $condition[] = '휴대폰번호 있는 경우만';
    }

    if ($params['ad_range_only'] == 1) {
        $ad_range_list = get_export_config('ad_range_list');
        $label = isset($ad_range_list[$params['ad_range_type']]) ? $ad_range_list[$params['ad_range_type']] : '';
        $condition[] = '수신동의: 예 (' . $label . ')';

        if ($params['ad_range_type'] == 'custom_period' && ($params['agree_date_start'] || $params['agree_date_end'])) {
            $condition[] = '수신동의일: ' . $params['agree_date_start'] . '~' . $params['agree_date_end'];
        }

        if (in_array($params['ad_range_type'], array('month_confirm', 'custom_period'), true)) {
            $channels = array_filter(array(
                !empty($params['ad_mailling']) && (int) $params['ad_mailling'] === 1 ? '이메일' : null,
            ));
            if ($channels) {
                $condition[] = '수신채널: ' . implode(', ', $channels);
            }
        }
    }

    if ($params['use_intercept'] == 1) {
        $intercept_list = get_export_config('intercept_list');
        $label = isset($intercept_list[$params['intercept']]) ? $intercept_list[$params['intercept']] : '';
        if ($label) {
            $condition[] = $label;
        }
    }

    $condition_str = !empty($condition) ? implode(', ', $condition) : '없음';
    $line1 = '[' . $datetime . '] [' . $status . '] 관리자: ' . $username;
    if ($success) {
        $total = isset($result['total']) ? $result['total'] : 0;
        $file_count = isset($result['zip']) ? 1 : count(isset($result['files']) ? $result['files'] : array());
        $line1 .= ' | 총 ' . $total . '건 | 파일: ' . $file_count . '개';
    }

    $log_entry = $line1 . PHP_EOL;
    $log_entry .= '조건: ' . $condition_str . PHP_EOL;

    if (!$success && !empty($result['error'])) {
        $log_entry .= '오류 메시지: ' . $result['error'] . PHP_EOL;
    }

    $log_entry .= PHP_EOL;

    if (@file_put_contents($latest_log_file, $log_entry, FILE_APPEND | LOCK_EX) === false) {
        error_log('[Member Export Error] 로그 파일 기록 실패: ' . $latest_log_file);
    }
}
