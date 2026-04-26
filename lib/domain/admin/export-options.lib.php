<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_get_member_export_config($type = null)
{
    $config = array(
        'sfl_list' => array(
            'mb_id' => '아이디',
            'mb_name' => '이름',
            'mb_nick' => '닉네임',
            'mb_email' => '이메일',
            'mb_hp' => '휴대폰번호',
        ),
        'intercept_list' => array(
            'exclude' => '차단회원 제외',
            'only' => '차단회원만',
        ),
        'ad_range_list' => array(
            'all' => '수신동의 회원 전체',
            'mailling_only' => '이메일 수신동의 회원만',
            'month_confirm' => date('m월') . ' 수신동의 확인 대상만',
            'custom_period' => '수신동의 기간 직접 입력',
        ),
    );

    return $type ? (isset($config[$type]) ? $config[$type] : array()) : $config;
}
