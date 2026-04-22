<?php
if (!defined('_GNUBOARD_')) exit;

// 본인확인내역 기록
function insert_cert_history($mb_id, $company, $method)
{
    global $g5;

    $cert_history_table = sql_quote_identifier($g5['cert_history_table']);
    if (!$cert_history_table) {
        return;
    }

    $sql = " insert into {$cert_history_table}
                set mb_id = :mb_id,
                    cr_company = :cr_company,
                    cr_method = :cr_method,
                    cr_ip = :cr_ip,
                    cr_date = :cr_date,
                    cr_time = :cr_time ";
    sql_query_prepared($sql, array(
        'mb_id' => $mb_id,
        'cr_company' => $company,
        'cr_method' => $method,
        'cr_ip' => $_SERVER['REMOTE_ADDR'],
        'cr_date' => G5_TIME_YMD,
        'cr_time' => G5_TIME_HIS,
    ));
}

// 본인확인 변경내역 기록
function ensure_member_cert_history_table()
{
    global $g5;

    if (!isset($g5['member_cert_history_table'])) {
        $g5['member_cert_history_table'] = G5_TABLE_PREFIX.'member_cert_history';
    }

    $member_cert_history_table = isset($g5['member_cert_history_table']) ? sql_quote_identifier($g5['member_cert_history_table']) : '';
    if ($member_cert_history_table && !sql_table_exists($g5['member_cert_history_table'])) {
        sql_query(" CREATE TABLE IF NOT EXISTS {$member_cert_history_table} (
                        `ch_id` int(11) NOT NULL auto_increment,
                        `mb_id` varchar(20) NOT NULL DEFAULT '',
                        `ch_name` varchar(255) NOT NULL DEFAULT '',
                        `ch_hp` varchar(255) NOT NULL DEFAULT '',
                        `ch_birth` varchar(255) NOT NULL DEFAULT '',
                        `ch_type` varchar(20) NOT NULL DEFAULT '',
                        `ch_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
                        PRIMARY KEY (`ch_id`),
                        KEY `mb_id` (`mb_id`)
                    ) ", true);
    }

    return $member_cert_history_table;
}

// 본인확인 변경내역 기록
function insert_member_cert_history($mb_id, $name, $hp, $birth, $type)
{
    $member_cert_history_table = ensure_member_cert_history_table();

    if (!$member_cert_history_table) {
        return;
    }

    $sql = " insert into {$member_cert_history_table}
                set mb_id = :mb_id,
                    ch_name = :ch_name,
                    ch_hp = :ch_hp,
                    ch_birth = :ch_birth,
                    ch_type = :ch_type,
                    ch_datetime = :ch_datetime";
    sql_query_prepared($sql, array(
        'mb_id' => $mb_id,
        'ch_name' => $name,
        'ch_hp' => $hp,
        'ch_birth' => $birth,
        'ch_type' => $type,
        'ch_datetime' => G5_TIME_YMD . ' ' . G5_TIME_HIS,
    ));
}

// 인증시도회수 체크
function certify_count_check($mb_id, $type)
{
    global $g5, $config;

    if ($config['cf_cert_use'] != 2) {
        return;
    }

    if ($config['cf_cert_limit'] == 0) {
        return;
    }

    $sql = " select count(*) as cnt from {$g5['cert_history_table']} ";
    $params = array(
        'cr_method' => $type,
        'cr_date' => G5_TIME_YMD,
    );

    if ($mb_id) {
        $sql .= " where mb_id = :mb_id ";
        $params['mb_id'] = $mb_id;
    } else {
        $sql .= " where cr_ip = :cr_ip ";
        $params['cr_ip'] = $_SERVER['REMOTE_ADDR'];
    }

    $sql .= " and cr_method = :cr_method and cr_date = :cr_date ";

    $row = sql_fetch_prepared($sql, $params);

    switch ($type) {
        case 'simple':
            $cert = '간편인증';
            break;
        case 'hp':
            $cert = '휴대폰';
            break;
        default:
            $cert = '';
            break;
    }

    if ((int) $row['cnt'] >= (int) $config['cf_cert_limit']) {
        alert_close('오늘 '.$cert.' 본인확인을 '.$row['cnt'].'회 이용하셔서 더 이상 이용할 수 없습니다.');
    }
}
