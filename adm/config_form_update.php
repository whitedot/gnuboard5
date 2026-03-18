<?php
$sub_menu = "100100";
require_once './_common.php';

check_demo();

auth_check_menu($auth, $sub_menu, 'w');

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

$sql = " select * from {$g5['config_table']} limit 1";
$ori_config = sql_fetch($sql);

$cf_title = isset($_POST['cf_title']) ? strip_tags(clean_xss_attributes($_POST['cf_title'])) : '';
$cf_admin = isset($_POST['cf_admin']) ? clean_xss_tags($_POST['cf_admin'], 1, 1) : '';

$mb = get_member($cf_admin);

if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
    alert('최고관리자 회원아이디가 존재하지 않습니다.');
}

check_admin_token();

$check_keys = array('cf_cert_kcp_cd', 'cf_cert_kcp_enckey', 'cf_recaptcha_site_key', 'cf_recaptcha_secret_key', 'cf_cert_kg_cd', 'cf_cert_kg_mid');

foreach ($check_keys as $key) {
    if (isset($_POST[$key]) && $_POST[$key]) {
        $_POST[$key] = preg_replace('/[^a-z0-9_\-\.]/i', '', $_POST[$key]);
    }
}

if (isset($_POST['cf_intercept_ip']) && $_POST['cf_intercept_ip']) {
    $pattern = explode("\n", trim($_POST['cf_intercept_ip']));
    for ($i = 0; $i < count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if (empty($pattern[$i])) {
            continue;
        }

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
        $pat = "/^{$pattern[$i]}$/";

        if (preg_match($pat, $_SERVER['REMOTE_ADDR'])) {
            alert("현재 접속 IP : " . $_SERVER['REMOTE_ADDR'] . " 가 차단될수 있기 때문에, 다른 IP를 입력해 주세요.");
        }
    }
}

$check_keys = array(
    'cf_use_email_certify' => 'int',
    'cf_use_hp' => 'int',
    'cf_req_hp' => 'int',
    'cf_register_level' => 'int',
    'cf_leave_day' => 'int',
    'cf_email_use' => 'int',
    'cf_prohibit_id' => 'text',
    'cf_prohibit_email' => 'text',
    'cf_page_rows' => 'int',
    'cf_cert_req' => 'int',
    'cf_cert_use' => 'int',
    'cf_cert_find' => 'int',
    'cf_cert_ipin' => 'char',
    'cf_cert_hp' => 'char',
    'cf_cert_simple' => 'char',
    'cf_cert_use_seed' => 'int',
    'cf_admin_email' => 'char',
    'cf_admin_email_name' => 'char',
    'cf_cut_name' => 'int',
    'cf_nick_modify' => 'int',
    'cf_possible_ip' => 'text',
    'cf_image_extension' => 'char',
    'cf_flash_extension' => 'char',
    'cf_movie_extension' => 'char',
    'cf_stipulation' => 'text',
    'cf_privacy' => 'text',
    'cf_use_promotion' => 'int',
    'cf_open_modify' => 'int',
    'cf_captcha_mp3' => 'char',
    'cf_cert_limit' => 'int',
    'cf_captcha' => 'char'
);

for ($i = 1; $i <= 10; $i++) {
    $check_keys['cf_' . $i . '_subj'] = isset($_POST['cf_' . $i . '_subj']) ? $_POST['cf_' . $i . '_subj'] : '';
    $check_keys['cf_' . $i] = isset($_POST['cf_' . $i]) ? $_POST['cf_' . $i] : '';
}

foreach ($check_keys as $k => $v) {
    if ($v === 'int') {
        $_POST[$k] = isset($_POST[$k]) ? (int) $_POST[$k] : 0;
    } else {
        if (in_array($k, array('cf_stipulation', 'cf_privacy'))) {
            $_POST[$k] = isset($_POST[$k]) ? $_POST[$k] : '';
        } else {
            $_POST[$k] = isset($_POST[$k]) ? strip_tags(clean_xss_attributes($_POST[$k])) : '';
        }
    }
}

$preserve_keys = array(
    'cf_image_extension',
    'cf_flash_extension',
    'cf_movie_extension'
);

foreach ($preserve_keys as $key) {
    if (!isset($_POST[$key])) {
        $_POST[$key] = isset($ori_config[$key]) ? $ori_config[$key] : '';
    }
}

// 본인확인을 사용할 경우 휴대폰인증 또는 간편인증 중 하나는 선택되어야 함
if ($_POST['cf_cert_use'] && !$_POST['cf_cert_ipin'] && !$_POST['cf_cert_hp'] && !$_POST['cf_cert_simple']) {
    alert('본인확인을 위해 휴대폰 본인확인 또는 KG이니시스 간편인증 서비스 중 하나 이상 선택해 주십시오.');
}

if (!$_POST['cf_cert_use']) {
    $_POST['cf_cert_ipin'] = '';
    $_POST['cf_cert_hp'] = '';
   $_POST['cf_cert_simple'] = '';
}

if ($_POST['cf_cert_ipin'] === 'kcb') {
    $_POST['cf_cert_ipin'] = '';
}
if ($_POST['cf_cert_hp'] === 'kcb') {
    $_POST['cf_cert_hp'] = '';
}

// 관리자가 자동등록방지를 사용해야 할 경우 ( 기본환경설정에서 최고관리자 변경시 )
$check_captcha = 0;

if ($cf_admin && $ori_config['cf_admin'] !== $cf_admin) {
    $check_captcha = 1;
}

if ($check_captcha) {
    include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');

    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

$sql = " update {$g5['config_table']}
            set cf_title = '{$cf_title}',
                cf_admin = '{$cf_admin}',
                cf_admin_email = '{$_POST['cf_admin_email']}',
                cf_admin_email_name = '{$_POST['cf_admin_email_name']}',
                cf_use_email_certify = '{$_POST['cf_use_email_certify']}',
                cf_cut_name = '{$_POST['cf_cut_name']}',
                cf_nick_modify = '{$_POST['cf_nick_modify']}',
                cf_possible_ip = '" . trim($_POST['cf_possible_ip']) . "',
                cf_intercept_ip = '" . trim($_POST['cf_intercept_ip']) . "',
                cf_use_hp = '{$_POST['cf_use_hp']}',
                cf_req_hp = '{$_POST['cf_req_hp']}',
                cf_register_level = '{$_POST['cf_register_level']}',
                cf_leave_day = '{$_POST['cf_leave_day']}',
                cf_email_use = '{$_POST['cf_email_use']}',
                cf_prohibit_id = '{$_POST['cf_prohibit_id']}',
                cf_prohibit_email = '{$_POST['cf_prohibit_email']}',
                cf_image_extension = '{$_POST['cf_image_extension']}',
                cf_flash_extension = '{$_POST['cf_flash_extension']}',
                cf_movie_extension = '{$_POST['cf_movie_extension']}',
                cf_page_rows = '{$_POST['cf_page_rows']}',
                cf_stipulation = '{$_POST['cf_stipulation']}',
                cf_privacy = '{$_POST['cf_privacy']}',
                cf_use_promotion = '{$_POST['cf_use_promotion']}',
                cf_open_modify = '{$_POST['cf_open_modify']}',
                cf_captcha_mp3 = '{$_POST['cf_captcha_mp3']}',
                cf_cert_use = '{$_POST['cf_cert_use']}',
                cf_cert_find = '{$_POST['cf_cert_find']}',
                cf_cert_ipin = '{$_POST['cf_cert_ipin']}',
                cf_cert_hp = '{$_POST['cf_cert_hp']}',
                cf_cert_simple = '{$_POST['cf_cert_simple']}',
                cf_cert_use_seed = '".(int)$_POST['cf_cert_use_seed']."',
                cf_cert_kg_cd = '{$_POST['cf_cert_kg_cd']}',
                cf_cert_kg_mid = '" . trim($_POST['cf_cert_kg_mid']) . "',
                cf_cert_kcp_cd = '{$_POST['cf_cert_kcp_cd']}',
                cf_cert_kcp_enckey = '{$_POST['cf_cert_kcp_enckey']}',
                cf_cert_limit = '{$_POST['cf_cert_limit']}',
                cf_cert_req = '{$_POST['cf_cert_req']}',
                cf_captcha = '{$_POST['cf_captcha']}',
                cf_recaptcha_site_key = '{$_POST['cf_recaptcha_site_key']}',
                cf_recaptcha_secret_key   =   '{$_POST['cf_recaptcha_secret_key']}' ";
sql_query($sql);

//sql_query(" OPTIMIZE TABLE `$g5[config_table]` ");

g5_delete_all_cache();

if (function_exists('get_admin_captcha_by')) {
    get_admin_captcha_by('remove');
}

run_event('admin_config_form_update');

update_rewrite_rules();

goto_url('./config_form.php', false);
