<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

if (!defined('ADMIN_MEMBER_EXPORT_PAGE_SIZE')) {
    define('ADMIN_MEMBER_EXPORT_PAGE_SIZE', 10000);
}
if (!defined('ADMIN_MEMBER_EXPORT_MAX_SIZE')) {
    define('ADMIN_MEMBER_EXPORT_MAX_SIZE', 300000);
}
if (!defined('ADMIN_MEMBER_EXPORT_BASE_DIR')) {
    define('ADMIN_MEMBER_EXPORT_BASE_DIR', 'member_list');
}
if (!defined('ADMIN_MEMBER_EXPORT_BASE_DATE')) {
    define('ADMIN_MEMBER_EXPORT_BASE_DATE', date('YmdHis'));
}
if (!defined('ADMIN_MEMBER_EXPORT_DIR')) {
    define('ADMIN_MEMBER_EXPORT_DIR', G5_DATA_PATH . '/' . ADMIN_MEMBER_EXPORT_BASE_DIR . '/' . ADMIN_MEMBER_EXPORT_BASE_DATE);
}
if (!defined('ADMIN_MEMBER_EXPORT_LOG_DIR')) {
    define('ADMIN_MEMBER_EXPORT_LOG_DIR', G5_DATA_PATH . '/' . ADMIN_MEMBER_EXPORT_BASE_DIR . '/log');
}

function admin_get_member_export_config($type = null)
{
    $config = array(
        'sfl_list' => array(
            'mb_id' => '아이디',
            'mb_name' => '이름',
            'mb_nick' => '닉네임',
            'mb_email' => '이메일',
            'mb_hp' => '휴대폰번호',
        ),
        'intercept_list' => array(
            'exclude' => '차단회원 제외',
            'only' => '차단회원만',
        ),
        'ad_range_list' => array(
            'all' => '수신동의 회원 전체',
            'mailling_only' => '이메일 수신동의 회원만',
            'month_confirm' => date('m월') . ' 수신동의 확인 대상만',
            'custom_period' => '수신동의 기간 직접 입력',
        ),
    );

    return $type ? (isset($config[$type]) ? $config[$type] : array()) : $config;
}

function admin_build_member_export_vars(array $query)
{
    $field_array = array_map('trim', explode(',', isset($query['fields']) ? $query['fields'] : ''));
    $vars = array();

    foreach ($field_array as $index => $field) {
        if ($field === '') {
            continue;
        }

        $vars['var' . ($index + 1)] = $field;
    }

    return $vars;
}

function admin_read_member_export_params(array $query)
{
    $params = array(
        'page' => 1,
        'formatType' => (int) (isset($query['formatType']) ? $query['formatType'] : 1),
        'use_stx' => isset($query['use_stx']) ? $query['use_stx'] : 0,
        'stx_cond' => clean_xss_tags(isset($query['stx_cond']) ? $query['stx_cond'] : 'like'),
        'sfl' => clean_xss_tags(isset($query['sfl']) ? $query['sfl'] : ''),
        'stx' => clean_xss_tags(isset($query['stx']) ? $query['stx'] : ''),
        'use_level' => isset($query['use_level']) ? $query['use_level'] : 0,
        'level_start' => (int) (isset($query['level_start']) ? $query['level_start'] : 1),
        'level_end' => (int) (isset($query['level_end']) ? $query['level_end'] : 10),
        'use_date' => isset($query['use_date']) ? $query['use_date'] : 0,
        'date_start' => clean_xss_tags(isset($query['date_start']) ? $query['date_start'] : ''),
        'date_end' => clean_xss_tags(isset($query['date_end']) ? $query['date_end'] : ''),
        'use_hp_exist' => isset($query['use_hp_exist']) ? $query['use_hp_exist'] : 0,
        'ad_range_only' => isset($query['ad_range_only']) ? $query['ad_range_only'] : 0,
        'ad_range_type' => clean_xss_tags(isset($query['ad_range_type']) ? $query['ad_range_type'] : 'all'),
        'ad_mailling' => isset($query['ad_mailling']) ? $query['ad_mailling'] : 0,
        'agree_date_start' => clean_xss_tags(isset($query['agree_date_start']) ? $query['agree_date_start'] : ''),
        'agree_date_end' => clean_xss_tags(isset($query['agree_date_end']) ? $query['agree_date_end'] : ''),
        'use_intercept' => isset($query['use_intercept']) ? $query['use_intercept'] : 0,
        'intercept' => clean_xss_tags(isset($query['intercept']) ? $query['intercept'] : 'exclude'),
        'vars' => admin_build_member_export_vars($query),
    );

    if ($params['level_start'] > $params['level_end']) {
        $tmp = $params['level_start'];
        $params['level_start'] = $params['level_end'];
        $params['level_end'] = $tmp;
    }

    if ($params['use_date'] && $params['date_start'] && $params['date_end'] && $params['date_start'] > $params['date_end']) {
        $tmp = $params['date_start'];
        $params['date_start'] = $params['date_end'];
        $params['date_end'] = $tmp;
    }

    if ($params['ad_range_type'] === 'custom_period' && $params['agree_date_start'] && $params['agree_date_end'] && $params['agree_date_start'] > $params['agree_date_end']) {
        $tmp = $params['agree_date_start'];
        $params['agree_date_start'] = $params['agree_date_end'];
        $params['agree_date_end'] = $tmp;
    }

    return $params;
}

function admin_count_member_export_members(array $params, $member_table)
{
    $where_data = admin_build_member_export_where($params);
    $sql = "SELECT COUNT(*) as cnt FROM {$member_table} {$where_data['clause']}";

    $result = sql_query_prepared($sql, $where_data['params']);
    if (!$result) {
        throw new Exception('데이터 조회에 실패하였습니다. 다시 시도해주세요.');
    }

    $row = sql_fetch_array($result);

    return (int) $row['cnt'];
}

function admin_build_member_export_date_condition($column_name, $date_start, $date_end, $param_prefix)
{
    if ($date_start && $date_end) {
        return array(
            'condition' => "{$column_name} BETWEEN :{$param_prefix}_start_range AND :{$param_prefix}_end_range",
            'params' => array(
                $param_prefix . '_start_range' => $date_start . ' 00:00:00',
                $param_prefix . '_end_range' => $date_end . ' 23:59:59',
            ),
        );
    }

    if ($date_start) {
        return array(
            'condition' => "{$column_name} >= :{$param_prefix}_start_only",
            'params' => array(
                $param_prefix . '_start_only' => $date_start . ' 00:00:00',
            ),
        );
    }

    if ($date_end) {
        return array(
            'condition' => "{$column_name} <= :{$param_prefix}_end_only",
            'params' => array(
                $param_prefix . '_end_only' => $date_end . ' 23:59:59',
            ),
        );
    }

    return array(
        'condition' => '',
        'params' => array(),
    );
}

function admin_build_member_export_where(array $params)
{
    $conditions = array();
    $query_params = array();

    $conditions[] = "mb_leave_date = ''";

    if (!empty($params['use_stx']) && $params['use_stx'] === '1') {
        $sfl_list = admin_get_member_export_config('sfl_list');
        $sfl = in_array($params['sfl'], array_keys($sfl_list), true) ? $params['sfl'] : '';
        $stx = isset($params['stx']) ? trim((string) $params['stx']) : '';

        if ($sfl !== '' && $stx !== '') {
            if ($params['stx_cond'] === 'like') {
                $conditions[] = "{$sfl} LIKE :search_like";
                $query_params['search_like'] = '%' . $stx . '%';
            } else {
                $conditions[] = "{$sfl} = :search_exact";
                $query_params['search_exact'] = $stx;
            }
        }
    }

    if (!empty($params['use_level']) && $params['use_level'] === '1') {
        $conditions[] = '(mb_level BETWEEN :level_start AND :level_end)';
        $query_params['level_start'] = max(1, (int) $params['level_start']);
        $query_params['level_end'] = min(10, (int) $params['level_end']);
    }

    if (!empty($params['use_date']) && $params['use_date'] === '1') {
        $date_condition = admin_build_member_export_date_condition(
            'mb_datetime',
            isset($params['date_start']) ? trim((string) $params['date_start']) : '',
            isset($params['date_end']) ? trim((string) $params['date_end']) : '',
            'date'
        );

        if ($date_condition['condition'] !== '') {
            $conditions[] = $date_condition['condition'];
            $query_params = array_merge($query_params, $date_condition['params']);
        }
    }

    if (!empty($params['use_hp_exist']) && $params['use_hp_exist'] === '1') {
        $conditions[] = "(mb_hp is not null and mb_hp != '')";
    }

    if (!empty($params['ad_range_only']) && $params['ad_range_only'] === '1') {
        $range = isset($params['ad_range_type']) ? $params['ad_range_type'] : '';
        $base_marketing = 'mb_marketing_agree = 1';

        if ($range === 'all' || $range === 'mailling_only') {
            $conditions[] = "({$base_marketing} AND mb_mailling = 1)";
        } elseif ($range === 'month_confirm' || $range === 'custom_period') {
            $use_email = !empty($params['ad_mailling']);

            if ($range === 'month_confirm') {
                $email_date_condition = array(
                    'condition' => 'mb_mailling_date BETWEEN :mailling_month_start AND :mailling_month_end',
                    'params' => array(
                        'mailling_month_start' => date('Y-m-01 00:00:00', strtotime('-23 months')),
                        'mailling_month_end' => date('Y-m-t 23:59:59', strtotime('-23 months')),
                    ),
                );
            } else {
                $email_date_condition = admin_build_member_export_date_condition(
                    'mb_mailling_date',
                    isset($params['agree_date_start']) ? (string) $params['agree_date_start'] : '',
                    isset($params['agree_date_end']) ? (string) $params['agree_date_end'] : '',
                    'agree_date'
                );

                if ($email_date_condition['condition'] === '') {
                    $email_date_condition = array(
                        'condition' => "mb_mailling_date <> '0000-00-00 00:00:00'",
                        'params' => array(),
                    );
                }
            }

            if (!$use_email) {
                $conditions[] = '0=1';
            } else {
                $conditions[] = '(mb_mailling = 1 AND ' . $email_date_condition['condition'] . ')';
                $query_params = array_merge($query_params, $email_date_condition['params']);
            }
        }
    }

    if (!empty($params['use_intercept']) && $params['use_intercept'] === '1') {
        if ($params['intercept'] === 'exclude') {
            $conditions[] = "mb_intercept_date = ''";
        } elseif ($params['intercept'] === 'only') {
            $conditions[] = "mb_intercept_date != ''";
        }
    }

    return array(
        'clause' => empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions),
        'params' => $query_params,
    );
}
