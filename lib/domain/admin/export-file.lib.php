<?php
if (!defined('_GNUBOARD_')) {
    exit;
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
