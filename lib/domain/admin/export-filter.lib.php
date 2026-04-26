<?php
if (!defined('_GNUBOARD_')) {
    exit;
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
