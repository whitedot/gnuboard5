<?php
if (!defined('_GNUBOARD_')) {
    exit;
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

function member_count_by_email($email)
{
    $member_table = member_get_member_table_name();

    return (int) sql_fetch_value_prepared(
        " select count(*) as cnt from {$member_table} where mb_email = :mb_email ",
        array('mb_email' => $email)
    );
}

function member_find_register_result_member($mb_id)
{
    if (!$mb_id) {
        return array();
    }

    return get_member($mb_id);
}

function member_find_email_certify_candidate($mb_id)
{
    $member_table = member_get_member_table_name();

    return sql_fetch_prepared(
        " select mb_id, mb_email_certify2, mb_leave_date, mb_intercept_date from {$member_table} where mb_id = :mb_id ",
        array('mb_id' => $mb_id)
    );
}

function member_clear_email_certify_token($mb_id)
{
    $member_table = member_get_member_table_name();

    return sql_query_prepared(
        " update {$member_table} set mb_email_certify2 = '' where mb_id = :mb_id ",
        array('mb_id' => $mb_id)
    );
}

function member_mark_email_certified($mb_id)
{
    $member_table = member_get_member_table_name();

    return sql_query_prepared(
        " update {$member_table} set mb_email_certify = :mb_email_certify where mb_id = :mb_id ",
        array(
            'mb_email_certify' => G5_TIME_YMDHIS,
            'mb_id' => $mb_id,
        )
    );
}

function member_find_register_email_member($mb_id)
{
    $member_table = member_get_member_table_name();

    return sql_fetch_prepared(
        " select mb_email, mb_datetime, mb_ip, mb_email_certify, mb_id from {$member_table} where mb_id = :mb_id ",
        array('mb_id' => $mb_id)
    );
}

function member_find_pending_register_email_member($mb_id)
{
    $member_table = member_get_member_table_name();

    return sql_fetch_prepared(
        " select mb_name from {$member_table} where mb_id = :mb_id and substring(mb_email_certify, 1, 1) = '0' ",
        array('mb_id' => $mb_id)
    );
}

function member_count_other_members_by_email($mb_id, $mb_email)
{
    $member_table = member_get_member_table_name();

    return (int) sql_fetch_value_prepared(
        " select count(*) as cnt from {$member_table} where mb_id <> :mb_id and mb_email = :mb_email ",
        array(
            'mb_id' => $mb_id,
            'mb_email' => $mb_email,
        )
    );
}

function member_update_email_address($mb_id, $mb_email)
{
    $member_table = member_get_member_table_name();

    return sql_query_prepared(
        " update {$member_table} set mb_email = :mb_email where mb_id = :mb_id ",
        array(
            'mb_email' => $mb_email,
            'mb_id' => $mb_id,
        )
    );
}
