<?php
if (!defined('_GNUBOARD_')) {
    exit;
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

function admin_build_member_export_select_options(array $options, $selected_value)
{
    $items = array();
    foreach ($options as $value => $label) {
        $items[] = array(
            'value' => (string) $value,
            'label' => (string) $label,
            'selected' => (string) $selected_value === (string) $value,
        );
    }

    return $items;
}

function admin_build_member_export_level_options($selected_value)
{
    $items = array();
    for ($i = 1; $i <= 10; $i++) {
        $items[] = array(
            'value' => $i,
            'label' => (string) $i,
            'selected' => (string) $selected_value === (string) $i,
        );
    }

    return $items;
}

function admin_member_export_supports_xlsx()
{
    return admin_archive_supports_zip();
}

function admin_member_export_runtime_error_message()
{
    if (admin_member_export_supports_xlsx()) {
        return '';
    }

    return 'ZIP archive 지원을 사용할 수 없어 회원 엑셀 내보내기를 실행할 수 없습니다. 서버 PHP 설정에서 zip 또는 phar 지원 상태를 확인한 뒤 다시 시도해 주세요.';
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
        'environment_ready' => admin_member_export_supports_xlsx(),
        'environment_error' => admin_member_export_runtime_error_message(),
    );
}

function admin_build_member_export_page_request(array $query, array $config, array $tables, array $member_row = array())
{
    $runtime = admin_build_member_export_runtime_context($tables, $member_row);

    return array(
        'runtime' => $runtime,
        'view' => admin_build_member_export_page_view($query, $config, $runtime),
    );
}

function admin_build_member_export_page_view(array $query, array $config, array $runtime)
{
    $params = admin_read_member_export_params($query);
    $total_count = 0;
    $total_error = '';

    try {
        $total_count = admin_count_member_export_members($params, $runtime['member_table']);
    } catch (Exception $e) {
        $total_error = $e->getMessage();
    }

    $filter_state = admin_build_member_export_filter_state($query, $config, $params);
    $links = admin_build_member_export_links();
    $client_config = admin_build_member_export_client_config($links);

    return array(
        'title' => '회원관리파일',
        'admin_container_class' => 'admin-page-member-export',
        'admin_page_subtitle' => '회원 조건을 조합해 내보내기 범위를 좁히고, 대용량 다운로드 진행 상태까지 한 화면에서 확인하세요.',
        'colspan' => 14,
        'form_token' => get_token(),
        'total_count' => $total_count,
        'total_error' => $total_error,
        'sfl_options' => admin_get_member_export_config('sfl_list'),
        'intercept_options' => admin_get_member_export_config('intercept_list'),
        'ad_range_options' => admin_get_member_export_config('ad_range_list'),
        'sfl_option_items' => admin_build_member_export_select_options(admin_get_member_export_config('sfl_list'), $params['sfl']),
        'intercept_option_items' => admin_build_member_export_select_options(admin_get_member_export_config('intercept_list'), $params['intercept']),
        'ad_range_option_items' => admin_build_member_export_select_options(admin_get_member_export_config('ad_range_list'), $params['ad_range_type']),
        'level_start_options' => admin_build_member_export_level_options($params['level_start']),
        'level_end_options' => admin_build_member_export_level_options($params['level_end']),
        'filter_state' => $filter_state,
        'links' => $links,
        'client_config' => $client_config,
        'client_config_attrs' => array(
            'stream-url' => $client_config['stream_url'],
            'popup-progress-title' => $client_config['popup_progress_title'],
            'popup-done-title' => $client_config['popup_done_title'],
            'close-confirm-message' => $client_config['close_confirm_message'],
            'download-stopped-message' => $client_config['download_stopped_message'],
            'download-failed-summary' => $client_config['download_failed_summary'],
            'download-failed-message' => $client_config['download_failed_message'],
            'estimated-seconds-multiplier' => (string) $client_config['estimated_seconds_multiplier'],
            'estimated-seconds-min' => (string) $client_config['estimated_seconds_min'],
        ),
        'environment_ready' => !empty($runtime['environment_ready']),
        'environment_error' => isset($runtime['environment_error']) ? $runtime['environment_error'] : '',
        'intro_items' => array(
            array(
                'html' => '<strong>회원수 ' . number_format(ADMIN_MEMBER_EXPORT_PAGE_SIZE) . '건 초과 시</strong> ' . number_format(ADMIN_MEMBER_EXPORT_PAGE_SIZE) . '건 단위로 분리 저장되며, <strong>엑셀 생성 최대 건수는 ' . number_format(ADMIN_MEMBER_EXPORT_MAX_SIZE) . '건</strong>입니다. 초과 시 조건 추가 설정 후 재시도하시기 바랍니다.',
            ),
            array(
                'html' => '<strong>수신동의 확인 대상은 만료일까지 1달 미만인 회원</strong>을 기준으로 필터링됩니다.',
            ),
            array(
                'html' => '파일 생성 시 서버에 임시 생성된 파일 중 <strong>오늘 날짜를 제외 한 파일은 자동 삭제</strong>되며, 수동 삭제가 필요하면 <a href="' . $links['file_delete_url'] . '" class="font-semibold text-primary hover:underline">회원관리파일 일괄삭제</a>를 이용해 주세요.',
            ),
            array(
                'html' => '회원 정보 수정은 <a href="' . $links['member_list_url'] . '" class="font-semibold text-primary hover:underline">회원 관리</a>에서 진행하실 수 있습니다.',
            ),
        ),
    );
}
