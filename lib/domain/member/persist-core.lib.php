<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_insert_cert_history_if_verified($mb_id, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    if (!member_certify_hash_matches($cert_type, $mb_name, $mb_hp, $md5_cert_no)) {
        return;
    }

    insert_member_cert_history(
        $mb_id,
        $mb_name,
        $mb_hp,
        get_session('ss_cert_birth'),
        get_session('ss_cert_type')
    );
}

function build_member_agree_log_entry($prefix, array $agree_items)
{
    if (empty($agree_items)) {
        return '';
    }

    return '[' . G5_TIME_YMDHIS . ', ' . $prefix . '] ' . implode(' | ', $agree_items) . "\n";
}

function append_member_agree_log($prefix, array $agree_items, $existing_log = '')
{
    $entry = build_member_agree_log_entry($prefix, $agree_items);

    if ($entry === '') {
        return $existing_log;
    }

    return $entry . (string) $existing_log;
}

function member_insert_account_with_history($mb_id, array $insert_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    $insert_parts = array();
    foreach ($insert_fields as $field => $value) {
        $insert_parts[] = $field . ' = :' . $field;
    }

    if (!sql_begin_transaction()) {
        return false;
    }

    $sql = " insert into {$GLOBALS['g5']['member_table']} set " . implode(', ', $insert_parts);
    if (!sql_query_prepared($sql, $insert_fields, false)) {
        sql_rollback();
        return false;
    }

    member_insert_cert_history_if_verified($mb_id, $mb_name, $mb_hp, $cert_type, $md5_cert_no);

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    return true;
}

function member_update_account_with_history($mb_id, array $update_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    $update_parts = array();
    foreach ($update_fields as $field => $value) {
        $update_parts[] = $field . ' = :' . $field;
    }

    $update_fields['mb_id'] = $mb_id;
    $sql = " update {$GLOBALS['g5']['member_table']}
                set " . implode(",\n                    ", $update_parts) . "
              where mb_id = :mb_id ";

    if (!sql_begin_transaction()) {
        return false;
    }

    if (!sql_query_prepared($sql, $update_fields, false)) {
        sql_rollback();
        return false;
    }

    member_insert_cert_history_if_verified($mb_id, $mb_name, $mb_hp, $cert_type, $md5_cert_no);

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    return true;
}
