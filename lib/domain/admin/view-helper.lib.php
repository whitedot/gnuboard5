<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_build_member_level_options($start_id = 0, $end_id = 10, $selected = '')
{
    $options = array();

    for ($i = $start_id; $i <= $end_id; $i++) {
        $options[] = array(
            'value' => (string) $i,
            'label' => (string) $i,
            'selected' => ((string) $i === (string) $selected),
        );
    }

    return $options;
}

function admin_read_member_id_options($level, $selected = '')
{
    global $g5;

    $options = array(
        array(
            'value' => '',
            'label' => '선택안함',
            'selected' => ((string) $selected === ''),
        ),
    );

    $sql = " select mb_id from {$g5['member_table']} where mb_level >= :mb_level ";
    $result = sql_query_prepared($sql, array(
        'mb_level' => (int) $level,
    ));

    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $mb_id = (string) $row['mb_id'];
        $options[] = array(
            'value' => $mb_id,
            'label' => $mb_id,
            'selected' => ($mb_id === (string) $selected),
        );
    }

    return $options;
}
