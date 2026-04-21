<?php
if (!defined('_GNUBOARD_')) exit;

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

function member_build_create_agree_updates(array &$insert_fields, $mb_marketing_agree, $mb_mailling)
{
    $agree_items = array();

    if ($mb_marketing_agree == 1) {
        $insert_fields['mb_marketing_date'] = G5_TIME_YMDHIS;
        $agree_items[] = '마케팅 목적의 개인정보 수집 및 이용(동의)';
    }

    if ($mb_mailling == 1) {
        $insert_fields['mb_mailling_date'] = G5_TIME_YMDHIS;
        $agree_items[] = '광고성 이메일 수신(동의)';
    }

    if (!empty($agree_items)) {
        $insert_fields['mb_agree_log'] = build_member_agree_log_entry('회원가입', $agree_items);
    }
}

function member_build_update_agree_updates(array &$update_fields, $mb_id, $mb_marketing_agree_default, $mb_marketing_agree, $mb_mailling_default, $mb_mailling)
{
    $agree_items = array();

    if ($mb_marketing_agree_default !== $mb_marketing_agree) {
        $update_fields['mb_marketing_date'] = G5_TIME_YMDHIS;
        $agree_items[] = '마케팅 목적의 개인정보 수집 및 이용(' . ($mb_marketing_agree == 1 ? '동의' : '철회') . ')';
    }

    if ($mb_mailling_default !== $mb_mailling) {
        $update_fields['mb_mailling_date'] = G5_TIME_YMDHIS;
        $agree_items[] = '광고성 이메일 수신(' . ($mb_mailling == 1 ? '동의' : '철회') . ')';
    }

    if (!empty($agree_items)) {
        $member_agree_row = get_member($mb_id, 'mb_agree_log');
        $existing_agree_log = isset($member_agree_row['mb_agree_log']) ? $member_agree_row['mb_agree_log'] : '';
        $update_fields['mb_agree_log'] = append_member_agree_log('회원 정보 수정', $agree_items, $existing_agree_log);
    }
}

function member_build_register_insert_fields(array $request, array $config, array $sql_certify)
{
    $insert_fields = array(
        'mb_id' => $request['mb_id'],
        'mb_password' => get_encrypt_string($request['mb_password']),
        'mb_name' => $request['mb_name'],
        'mb_nick' => $request['mb_nick'],
        'mb_nick_date' => G5_TIME_YMD,
        'mb_email' => $request['mb_email'],
        'mb_today_login' => G5_TIME_YMDHIS,
        'mb_datetime' => G5_TIME_YMDHIS,
        'mb_ip' => $_SERVER['REMOTE_ADDR'],
        'mb_level' => $config['cf_register_level'],
        'mb_login_ip' => $_SERVER['REMOTE_ADDR'],
        'mb_mailling' => $request['mb_mailling'],
        'mb_open' => $request['mb_open'],
        'mb_open_date' => G5_TIME_YMD,
        'mb_marketing_agree' => $request['mb_marketing_agree'],
    );

    foreach ($sql_certify as $field => $value) {
        $insert_fields[$field] = $value;
    }

    if (!$config['cf_use_email_certify']) {
        $insert_fields['mb_email_certify'] = G5_TIME_YMDHIS;
    }

    member_build_create_agree_updates($insert_fields, $request['mb_marketing_agree'], $request['mb_mailling']);

    return $insert_fields;
}

function member_build_register_update_fields(array $request, array $config, array $sql_certify, $old_email)
{
    $update_fields = array(
        'mb_nick' => $request['mb_nick'],
        'mb_mailling' => $request['mb_mailling'],
        'mb_open' => $request['mb_open'],
        'mb_email' => $request['mb_email'],
        'mb_marketing_agree' => $request['mb_marketing_agree'],
    );

    if ($request['mb_password']) {
        $update_fields['mb_password'] = get_encrypt_string($request['mb_password']);
    }

    if ($request['mb_nick_default'] != $request['mb_nick']) {
        $update_fields['mb_nick_date'] = G5_TIME_YMD;
    }

    if (isset($request['mb_open_default']) && $request['mb_open_default'] != $request['mb_open']) {
        $update_fields['mb_open_date'] = G5_TIME_YMD;
    }

    if ($old_email != $request['mb_email'] && $config['cf_use_email_certify']) {
        $update_fields['mb_email_certify'] = '';
    }

    foreach ($sql_certify as $field => $value) {
        $update_fields[$field] = $value;
    }

    member_build_update_agree_updates(
        $update_fields,
        $request['mb_id'],
        $request['mb_marketing_agree_default'],
        $request['mb_marketing_agree'],
        $request['mb_mailling_default'],
        $request['mb_mailling']
    );

    return $update_fields;
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

function member_insert_admin_account(array $insert_fields)
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

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    return true;
}

function member_update_admin_account($mb_id, array $update_fields)
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

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    return true;
}
