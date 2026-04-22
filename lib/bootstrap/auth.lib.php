<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function g5_refresh_member_login(array $member)
{
    global $g5;

    if (substr($member['mb_today_login'], 0, 10) == G5_TIME_YMD) {
        return;
    }

    sql_query_prepared(" update {$g5['member_table']} set mb_today_login = :mb_today_login, mb_login_ip = :mb_login_ip where mb_id = :mb_id ", array(
        'mb_today_login' => G5_TIME_YMDHIS,
        'mb_login_ip' => $_SERVER['REMOTE_ADDR'],
        'mb_id' => $member['mb_id'],
    ));
}

function g5_restore_logged_in_member()
{
    $session_mb_id = isset($_SESSION['ss_mb_id']) ? $_SESSION['ss_mb_id'] : '';
    if (!$session_mb_id) {
        return null;
    }

    $member = get_member($session_mb_id);
    $is_invalid_member =
        ($member['mb_intercept_date'] && $member['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) ||
        ($member['mb_leave_date'] && $member['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) ||
        (function_exists('check_auth_session_token') && !check_auth_session_token($member['mb_datetime']));

    if ($is_invalid_member) {
        set_session('ss_mb_id', '');
        return array();
    }

    g5_refresh_member_login($member);

    return $member;
}

function g5_try_restore_auto_login()
{
    global $config, $g5;

    $tmp_mb_id = get_cookie('ck_mb_id');
    if (!$tmp_mb_id) {
        return false;
    }

    $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
    if (strtolower($tmp_mb_id) === strtolower($config['cf_admin'])) {
        return false;
    }

    $sql = " select mb_password, mb_intercept_date, mb_leave_date, mb_email_certify, mb_datetime from {$g5['member_table']} where mb_id = :mb_id ";
    $row = sql_fetch_prepared($sql, array(
        'mb_id' => $tmp_mb_id,
    ));

    if (empty($row['mb_password'])) {
        return false;
    }

    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $row['mb_password']);
    $tmp_key = get_cookie('ck_auto');
    if ($tmp_key !== $key || !$tmp_key) {
        return false;
    }

    if ($row['mb_intercept_date'] != '' || $row['mb_leave_date'] != '') {
        return false;
    }

    if ($config['cf_use_email_certify'] && !preg_match('/[1-9]/', $row['mb_email_certify'])) {
        return false;
    }

    set_session('ss_mb_id', $tmp_mb_id);
    if (function_exists('update_auth_session_token')) {
        update_auth_session_token($row['mb_datetime']);
    }

    echo "<script type='text/javascript'> window.location.reload(); </script>";
    exit;
}

function g5_match_ip_pattern_list($patterns_text, $ip)
{
    $patterns = explode("\n", trim((string) $patterns_text));

    for ($i = 0; $i < count($patterns); $i++) {
        $pattern = trim($patterns[$i]);
        if ($pattern === '') {
            continue;
        }

        $pattern = str_replace(".", "\.", $pattern);
        $pattern = str_replace("+", "[0-9\.]+", $pattern);
        if (preg_match("/^{$pattern}$/", $ip)) {
            return true;
        }
    }

    return false;
}

function g5_enforce_ip_access_policy(array $member, array $config)
{
    if (isset($member['mb_id']) && $config['cf_admin'] === $member['mb_id']) {
        return;
    }

    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $cf_possible_ip = trim($config['cf_possible_ip']);
    if ($cf_possible_ip && !g5_match_ip_pattern_list($cf_possible_ip, $remote_addr)) {
        die("<meta charset=utf-8>접근이 가능하지 않습니다.");
    }

    if (g5_match_ip_pattern_list($config['cf_intercept_ip'], $remote_addr)) {
        die("<meta charset=utf-8>접근 불가합니다.");
    }
}
