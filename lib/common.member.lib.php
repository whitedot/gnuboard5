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

function member_delete($mb_id)
{
    global $config;
    global $g5;

    $sql = " select mb_name, mb_nick, mb_ip, mb_memo, mb_level from {$g5['member_table']} where mb_id = :mb_id ";
    $mb = sql_fetch_prepared($sql, array(
        'mb_id' => $mb_id,
    ));

    if (preg_match('#^[0-9]{8}.*삭제함#', $mb['mb_memo'])) {
        return;
    }

    $delete_memo = date('Ymd', G5_SERVER_TIME) . " 삭제함\n" . $mb['mb_memo'];
    sql_query_prepared(" update {$g5['member_table']} set mb_password = '', mb_level = 1, mb_email = '', mb_hp = '', mb_birth = '', mb_sex = '', mb_memo = :mb_memo, mb_certify = '', mb_adult = 0, mb_dupinfo = '' where mb_id = :mb_id ", array(
        'mb_memo' => $delete_memo,
        'mb_id' => $mb_id,
    ));

    sql_query_prepared(" delete from {$g5['auth_table']} where mb_id = :mb_id ", array(
        'mb_id' => $mb_id,
    ));

    @unlink(G5_DATA_PATH . '/member/' . substr($mb_id, 0, 2) . '/' . $mb_id . '.gif');

    run_event('member_delete_after', $mb_id);
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
