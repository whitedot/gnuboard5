<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_write_member_export_log($params, $result = array(), $actor_id = 'guest')
{
    $max_size = 1024 * 1024 * 2;
    $max_files = 10;
    $username = $actor_id !== '' ? $actor_id : 'guest';
    $datetime = date('Y-m-d H:i:s');

    if (!is_dir(ADMIN_MEMBER_EXPORT_LOG_DIR)) {
        @mkdir(ADMIN_MEMBER_EXPORT_LOG_DIR, G5_DIR_PERMISSION, true);
        @chmod(ADMIN_MEMBER_EXPORT_LOG_DIR, G5_DIR_PERMISSION);
    }

    $log_files = glob(ADMIN_MEMBER_EXPORT_LOG_DIR . '/export_log_*.log');
    if (!is_array($log_files)) {
        $log_files = array();
    }

    usort($log_files, function ($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    $latest_log_file = isset($log_files[0]) ? $log_files[0] : null;
    if (!$latest_log_file || filesize($latest_log_file) >= $max_size) {
        $latest_log_file = ADMIN_MEMBER_EXPORT_LOG_DIR . '/export_log_' . date('YmdHi') . '.log';
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
        $sfl_list = admin_get_member_export_config('sfl_list');
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
        $ad_range_list = admin_get_member_export_config('ad_range_list');
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
        $intercept_list = admin_get_member_export_config('intercept_list');
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
        error_log('[Admin Member Export Error] 로그 파일 기록 실패: ' . $latest_log_file);
    }
}
