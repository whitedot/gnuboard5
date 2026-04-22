<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_certify_hash_matches($cert_type, $mb_name, $mb_hp, $md5_cert_no)
{
    if (!$cert_type || !$md5_cert_no) {
        return false;
    }

    $cert_birth = get_session('ss_cert_birth');
    $cert_hash = get_session('ss_cert_hash');

    if ($cert_type === 'ipin') {
        return $cert_hash === md5($mb_name . $cert_type . $cert_birth . $md5_cert_no);
    }

    return $cert_hash === md5($mb_name . $cert_type . $cert_birth . $mb_hp . $md5_cert_no);
}

function build_member_certify_fields($w, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    global $config;

    $sql_certify = array();

    if ($config['cf_cert_use'] && $cert_type && $md5_cert_no) {
        if (!member_certify_hash_matches($cert_type, $mb_name, $mb_hp, $md5_cert_no)) {
            alert('본인인증된 정보와 입력된 회원정보가 일치하지않습니다. 다시시도 해주세요');
        }

        $sql_certify['mb_hp'] = $mb_hp;
        $sql_certify['mb_certify'] = $cert_type;
        $sql_certify['mb_adult'] = get_session('ss_cert_adult');
        $sql_certify['mb_birth'] = get_session('ss_cert_birth');
        $sql_certify['mb_sex'] = get_session('ss_cert_sex');
        $sql_certify['mb_dupinfo'] = get_session('ss_cert_dupinfo');

        if ($w == 'u') {
            $sql_certify['mb_name'] = $mb_name;
        }

        return $sql_certify;
    }

    if (get_session('ss_reg_mb_name') != $mb_name || get_session('ss_reg_mb_hp') != $mb_hp) {
        $sql_certify['mb_hp'] = $mb_hp;
        $sql_certify['mb_certify'] = '';
        $sql_certify['mb_adult'] = 0;
        $sql_certify['mb_birth'] = '';
        $sql_certify['mb_sex'] = '';
    }

    return $sql_certify;
}

function member_guard_mail_link_bot()
{
    if (function_exists('check_mail_bot')) {
        check_mail_bot($_SERVER['REMOTE_ADDR']);
    }
}
