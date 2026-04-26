<?php
if (!defined('_GNUBOARD_')) {
    exit;
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
