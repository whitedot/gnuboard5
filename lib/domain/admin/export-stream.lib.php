<?php
if (!defined('_GNUBOARD_')) {
    exit;
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

function admin_validate_member_export_stream_request(array $request, array $runtime)
{
    if (empty($request['params']) || !is_array($request['params'])) {
        return '데이터가 올바르게 전달되지 않아 작업에 실패하였습니다.';
    }

    if ($request['mode'] !== 'start') {
        return '잘못된 요청 입니다.';
    }

    if (empty($runtime['environment_ready'])) {
        return isset($runtime['environment_error']) && $runtime['environment_error'] !== ''
            ? $runtime['environment_error']
            : '회원 엑셀 내보내기 실행 환경이 준비되지 않았습니다.';
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

    $stream_error = admin_validate_member_export_stream_request($request, $runtime);
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
