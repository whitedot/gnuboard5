<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_build_admin_insert_fields(array $request)
{
    $posts = $request['posts'];
    $agree_items = array();

    if ($request['mb_marketing_agree'] == 1) {
        $agree_items[] = '마케팅 목적의 개인정보 수집 및 이용(동의)';
    }

    if ($posts['mb_mailling'] == 1) {
        $agree_items[] = '광고성 이메일 수신(동의)';
    }

    $insert_fields = array(
        'mb_id' => $request['mb_id'],
        'mb_password' => get_encrypt_string($request['mb_password']),
        'mb_datetime' => G5_TIME_YMDHIS,
        'mb_ip' => $_SERVER['REMOTE_ADDR'],
        'mb_email_certify' => G5_TIME_YMDHIS,
        'mb_name' => $posts['mb_name'],
        'mb_nick' => $request['mb_nick'],
        'mb_email' => $request['mb_email'],
        'mb_hp' => $request['mb_hp'],
        'mb_certify' => $request['mb_certify'],
        'mb_adult' => $request['mb_adult'],
        'mb_leave_date' => $posts['mb_leave_date'],
        'mb_intercept_date' => $posts['mb_intercept_date'],
        'mb_memo' => $request['mb_memo'],
        'mb_mailling' => $posts['mb_mailling'],
        'mb_open' => $posts['mb_open'],
        'mb_open_date' => G5_TIME_YMDHIS,
        'mb_level' => $posts['mb_level'],
        'mb_marketing_agree' => $request['mb_marketing_agree'],
    );

    if ($request['mb_marketing_agree'] == 1) {
        $insert_fields['mb_marketing_date'] = G5_TIME_YMDHIS;
    }

    if ($posts['mb_mailling'] == 1) {
        $insert_fields['mb_mailling_date'] = G5_TIME_YMDHIS;
    }

    if (!empty($agree_items)) {
        $insert_fields['mb_agree_log'] = build_member_agree_log_entry('관리자 회원추가', $agree_items);
    }

    return $insert_fields;
}

function member_build_admin_update_fields(array $request, array $existing_member)
{
    $posts = $request['posts'];
    $mb_mailling = $posts['mb_mailling'];
    $agree_items = array();

    if ($existing_member['mb_marketing_agree'] !== $request['mb_marketing_agree']) {
        $agree_items[] = '마케팅 목적의 개인정보 수집 및 이용(' . ($request['mb_marketing_agree'] == 1 ? '동의' : '철회') . ')';
    }

    if ((string) $existing_member['mb_mailling'] !== (string) $mb_mailling) {
        $agree_items[] = '광고성 이메일 수신(' . ($mb_mailling == 1 ? '동의' : '철회') . ')';
    }

    $update_fields = array(
        'mb_name' => $posts['mb_name'],
        'mb_nick' => $request['mb_nick'],
        'mb_email' => $request['mb_email'],
        'mb_hp' => $request['mb_hp'],
        'mb_certify' => $request['mb_certify'],
        'mb_adult' => $request['mb_adult'],
        'mb_leave_date' => $posts['mb_leave_date'],
        'mb_intercept_date' => $posts['mb_intercept_date'],
        'mb_memo' => $request['mb_memo'],
        'mb_mailling' => $mb_mailling,
        'mb_open' => $posts['mb_open'],
        'mb_open_date' => G5_TIME_YMDHIS,
        'mb_level' => $posts['mb_level'],
        'mb_marketing_agree' => $request['mb_marketing_agree'],
    );

    if ($request['mb_password']) {
        $update_fields['mb_password'] = get_encrypt_string($request['mb_password']);
    }

    if (!empty($request['passive_certify'])) {
        $update_fields['mb_email_certify'] = G5_TIME_YMDHIS;
    }

    if ($existing_member['mb_marketing_agree'] !== $request['mb_marketing_agree']) {
        $update_fields['mb_marketing_date'] = G5_TIME_YMDHIS;
    }

    if ((string) $existing_member['mb_mailling'] !== (string) $mb_mailling) {
        $update_fields['mb_mailling_date'] = G5_TIME_YMDHIS;
    }

    if (!empty($agree_items)) {
        $update_fields['mb_agree_log'] = append_member_agree_log(
            '관리자 회원수정',
            $agree_items,
            isset($existing_member['mb_agree_log']) ? $existing_member['mb_agree_log'] : ''
        );
    }

    return $update_fields;
}

function member_insert_admin_account(array $insert_fields)
{
    $member_table = member_get_member_table_name();
    $insert_parts = array();
    foreach ($insert_fields as $field => $value) {
        $insert_parts[] = $field . ' = :' . $field;
    }

    if (!sql_begin_transaction()) {
        return false;
    }

    $sql = " insert into {$member_table} set " . implode(', ', $insert_parts);
    if (!sql_query_prepared($sql, $insert_fields, false)) {
        sql_rollback();
        return false;
    }

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    return true;
}

function member_update_admin_account($mb_id, array $update_fields)
{
    $member_table = member_get_member_table_name();
    $update_parts = array();
    foreach ($update_fields as $field => $value) {
        $update_parts[] = $field . ' = :' . $field;
    }

    $update_fields['mb_id'] = $mb_id;
    $sql = " update {$member_table}
                set " . implode(",\n                    ", $update_parts) . "
              where mb_id = :mb_id ";

    if (!sql_begin_transaction()) {
        return false;
    }

    if (!sql_query_prepared($sql, $update_fields, false)) {
        sql_rollback();
        return false;
    }

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    return true;
}

function member_mark_leave($mb_id, $leave_date, $leave_memo)
{
    return member_anonymize_account($mb_id, $leave_date, '탈퇴');
}

function member_find_dupinfo_owner($member_mb_id, $mb_dupinfo)
{
    $member_table = member_get_member_table_name();

    return sql_fetch_prepared(
        " select mb_id from {$member_table} where mb_id <> :member_mb_id and mb_dupinfo = :mb_dupinfo ",
        array(
            'member_mb_id' => $member_mb_id,
            'mb_dupinfo' => $mb_dupinfo,
        )
    );
}

function member_update_cert_refresh_fields($mb_id, array $update_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    if (empty($update_fields)) {
        return true;
    }

    return member_update_account_with_history($mb_id, $update_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no);
}

function member_find_email_stop_member($mb_id)
{
    $member_table = member_get_member_table_name();

    return sql_fetch_prepared(
        " select mb_id, mb_email, mb_datetime from {$member_table} where mb_id = :mb_id ",
        array('mb_id' => $mb_id)
    );
}

function member_disable_mailling($mb_id)
{
    $member_table = member_get_member_table_name();

    return sql_query_prepared(
        " update {$member_table} set mb_mailling = 0 where mb_id = :mb_id ",
        array('mb_id' => $mb_id)
    );
}
