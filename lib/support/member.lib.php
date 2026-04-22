<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function clean_relative_paths($path)
{
    $path_len = strlen($path);

    $i = 0;
    while ($i <= $path_len) {
        $result = str_replace('../', '', str_replace('\\', '/', $path));

        if ((string) $result === (string) $path) {
            break;
        }

        $path = $result;
        $i++;
    }

    return $path;
}

function member_build_deleted_account_nick($mb_no)
{
    return substr('deleted_' . (int) $mb_no, 0, 255);
}

function member_build_deleted_account_memo($action_label)
{
    return date('Ymd', G5_SERVER_TIME) . ' ' . $action_label . " 계정 비식별 처리";
}

function member_should_mask_preserved_id(array $member_row)
{
    return !empty($member_row['mb_leave_date']);
}

function member_mask_preserved_id($mb_id)
{
    $mb_id = (string) $mb_id;
    $length = strlen($mb_id);

    if ($length <= 0) {
        return '';
    }

    if ($length <= 3) {
        return str_repeat('*', $length);
    }

    return substr($mb_id, 0, 3) . str_repeat('*', $length - 3);
}

function member_get_display_id(array $member_row)
{
    $mb_id = isset($member_row['mb_id']) ? (string) $member_row['mb_id'] : '';

    if (!member_should_mask_preserved_id($member_row)) {
        return $mb_id;
    }

    return member_mask_preserved_id($mb_id);
}

function member_delete_related_personal_history($mb_id)
{
    global $g5;

    if (isset($g5['auth_table']) && !sql_query_prepared(" delete from {$g5['auth_table']} where mb_id = :mb_id ", array(
        'mb_id' => $mb_id,
    ), false)) {
        return false;
    }

    if (isset($g5['cert_history_table']) && sql_table_exists($g5['cert_history_table'])) {
        if (!sql_query_prepared(" delete from {$g5['cert_history_table']} where mb_id = :mb_id ", array(
            'mb_id' => $mb_id,
        ), false)) {
            return false;
        }
    }

    if (isset($g5['member_cert_history_table']) && sql_table_exists($g5['member_cert_history_table'])) {
        if (!sql_query_prepared(" delete from {$g5['member_cert_history_table']} where mb_id = :mb_id ", array(
            'mb_id' => $mb_id,
        ), false)) {
            return false;
        }
    }

    return true;
}

function member_anonymize_account($mb_id, $leave_date = '', $action_label = '삭제')
{
    global $g5;

    $mb = sql_fetch_prepared(
        " select mb_no, mb_id, mb_leave_date from {$g5['member_table']} where mb_id = :mb_id ",
        array('mb_id' => $mb_id)
    );

    if (!(isset($mb['mb_no']) && $mb['mb_no'])) {
        return false;
    }

    $profile_path = G5_DATA_PATH . '/member/' . substr($mb_id, 0, 2) . '/' . $mb_id . '.gif';
    $update_params = array(
        'mb_password' => '',
        'mb_name' => '삭제된 회원',
        'mb_nick' => member_build_deleted_account_nick($mb['mb_no']),
        'mb_nick_date' => '0000-00-00',
        'mb_email' => '',
        'mb_level' => 1,
        'mb_sex' => '',
        'mb_birth' => '',
        'mb_hp' => '',
        'mb_certify' => '',
        'mb_adult' => 0,
        'mb_dupinfo' => '',
        'mb_today_login' => '0000-00-00 00:00:00',
        'mb_login_ip' => '',
        'mb_ip' => '',
        'mb_leave_date' => $leave_date,
        'mb_intercept_date' => '',
        'mb_email_certify' => '0000-00-00 00:00:00',
        'mb_email_certify2' => '',
        'mb_memo' => member_build_deleted_account_memo($action_label),
        'mb_lost_certify' => '',
        'mb_mailling' => 0,
        'mb_mailling_date' => '0000-00-00 00:00:00',
        'mb_open' => 0,
        'mb_open_date' => '0000-00-00',
        'mb_marketing_agree' => 0,
        'mb_marketing_date' => '0000-00-00 00:00:00',
        'mb_agree_log' => '',
        'mb_id' => $mb_id,
    );

    if (!sql_begin_transaction()) {
        return false;
    }

    if (!member_delete_related_personal_history($mb_id)) {
        sql_rollback();
        return false;
    }

    if (!sql_query_prepared(
        " update {$g5['member_table']}
              set mb_password = :mb_password,
                  mb_name = :mb_name,
                  mb_nick = :mb_nick,
                  mb_nick_date = :mb_nick_date,
                  mb_email = :mb_email,
                  mb_level = :mb_level,
                  mb_sex = :mb_sex,
                  mb_birth = :mb_birth,
                  mb_hp = :mb_hp,
                  mb_certify = :mb_certify,
                  mb_adult = :mb_adult,
                  mb_dupinfo = :mb_dupinfo,
                  mb_today_login = :mb_today_login,
                  mb_login_ip = :mb_login_ip,
                  mb_ip = :mb_ip,
                  mb_leave_date = :mb_leave_date,
                  mb_intercept_date = :mb_intercept_date,
                  mb_email_certify = :mb_email_certify,
                  mb_email_certify2 = :mb_email_certify2,
                  mb_memo = :mb_memo,
                  mb_lost_certify = :mb_lost_certify,
                  mb_mailling = :mb_mailling,
                  mb_mailling_date = :mb_mailling_date,
                  mb_open = :mb_open,
                  mb_open_date = :mb_open_date,
                  mb_marketing_agree = :mb_marketing_agree,
                  mb_marketing_date = :mb_marketing_date,
                  mb_agree_log = :mb_agree_log
            where mb_id = :mb_id ",
        $update_params,
        false
    )) {
        sql_rollback();
        return false;
    }

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    @unlink($profile_path);

    return true;
}

function member_delete($mb_id)
{
    if (!member_anonymize_account($mb_id, date('Ymd', G5_SERVER_TIME), '삭제')) {
        return false;
    }

    run_event('member_delete_after', $mb_id);

    return true;
}

function get_email_address($email)
{
    preg_match("/[0-9a-z._-]+@[a-z0-9._-]{4,}/i", $email, $matches);

    return isset($matches[0]) ? $matches[0] : '';
}

function is_use_email_certify()
{
    global $config;

    return $config['cf_use_email_certify'];
}

// 회원 사이드뷰 UI 조각을 생성한다.
function get_sideview($mb_id, $name, $email = '')
{
    $mb_id = clean_xss_tags($mb_id);
    $email = base64_encode($email);

    $name = clean_xss_tags($name);
    if (!$mb_id) {
        return $name;
    }

    $tmp_name = "<span class=\"sv_wrap hs-dropdown [--placement:bottom-left]\">";
    $tmp_name .= "<button type=\"button\" class=\"sv_member hs-dropdown-toggle\" aria-haspopup=\"menu\" aria-expanded=\"false\">";
    $tmp_name .= $name;
    $tmp_name .= "<span class=\"sr-only\"> 메뉴 열기</span>";
    $tmp_name .= "</button>";
    $tmp_name .= "<div class=\"sv hs-dropdown-menu\" role=\"menu\" aria-orientation=\"vertical\">";

    if (is_admin($mb_id)) {
        $tmp_name .= "<a href=\"".G5_ADMIN_URL."/member_form.php?w=u&mb_id=".$mb_id."\" target=\"_blank\" rel=\"noopener\">회원정보변경</a>";
    }

    $tmp_name .= "</div></span>";

    return $tmp_name;
}
