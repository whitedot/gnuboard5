<?php
$sub_menu = "200100";
require_once "./_common.php";
require_once G5_LIB_PATH . "/register.lib.php";

if ($w == 'u') {
    check_demo();
}

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$mb_id          = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
$mb_password    = isset($_POST['mb_password']) ? trim($_POST['mb_password']) : '';
$mb_certify_case = isset($_POST['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_certify_case']) : '';
$mb_certify     = isset($_POST['mb_certify']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_certify']) : '';

// 광고성 정보 수신
$mb_marketing_agree         = isset($_POST['mb_marketing_agree']) ? clean_xss_tags($_POST['mb_marketing_agree'], 1, 1) : '0';

// 관리자가 자동등록방지를 사용해야 할 경우 ( 회원의 비밀번호 변경시 캡챠를 체크한다 )
if ($mb_password) {
    include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');

    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

// 휴대폰번호 체크
$mb_hp = hyphen_hp_number($_POST['mb_hp']);
if ($mb_hp) {
    $result = exist_mb_hp($mb_hp, $mb_id);
    if ($result) {
        alert($result);
    }
}

// 인증정보처리
if ($mb_certify_case && $mb_certify) {
    $mb_certify = isset($_POST['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', (string)$_POST['mb_certify_case']) : '';
    $mb_adult = isset($_POST['mb_adult']) ? preg_replace('/[^0-9a-z_]/i', '', (string)$_POST['mb_adult']) : '';
} else {
    $mb_certify = '';
    $mb_adult = 0;
}

$mb_email = isset($_POST['mb_email']) ? get_email_address(trim($_POST['mb_email'])) : '';
$mb_nick = isset($_POST['mb_nick']) ? trim(strip_tags($_POST['mb_nick'])) : '';

if ($msg = valid_mb_nick($mb_nick)) {
    alert($msg, "", true, true);
}

$posts = array();
$check_keys = array(
    'mb_name',
    'mb_leave_date',
    'mb_intercept_date',
    'mb_mailling',
    'mb_open',
    'mb_level'
);

for ($i = 1; $i <= 10; $i++) {
    $check_keys[] = 'mb_' . $i;
}

foreach ($check_keys as $key) {
    $posts[$key] = isset($_POST[$key]) ? clean_xss_tags($_POST[$key], 1, 1) : '';
}

$sql_common = "  mb_name = '{$posts['mb_name']}',
                 mb_nick = '{$mb_nick}',
                 mb_email = '{$mb_email}',
                 mb_hp = '{$mb_hp}',
                 mb_certify = '{$mb_certify}',
                 mb_adult = '{$mb_adult}',
                 mb_leave_date = '{$posts['mb_leave_date']}',
                 mb_intercept_date='{$posts['mb_intercept_date']}',
                 mb_memo = '{$mb_memo}',
                 mb_mailling = '{$posts['mb_mailling']}',
                 mb_open = '{$posts['mb_open']}',
                 mb_open_date = '".G5_TIME_YMDHIS."',
                 mb_level = '{$posts['mb_level']}',
                 mb_marketing_agree = '{$mb_marketing_agree}' ";

if ($w == '') {
    $mb = get_member($mb_id);
    if (isset($mb['mb_id']) && $mb['mb_id']) {
        alert('이미 존재하는 회원아이디입니다.\\nＩＤ : ' . $mb['mb_id'] . '\\n이름 : ' . $mb['mb_name'] . '\\n닉네임 : ' . $mb['mb_nick'] . '\\n메일 : ' . $mb['mb_email']);
    }

    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$mb_nick}' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$mb_email}' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }

    $agree_items = [];
    // 마케팅 목적의 개인정보 수집 및 이용
    if ($mb_marketing_agree == 1) {
        $sql_common .=  " , mb_marketing_date = '".G5_TIME_YMDHIS."' ";
        $agree_items[] = "마케팅 목적의 개인정보 수집 및 이용(동의)";
    }

    // 광고성 이메일 수신
    if ($mb_mailling == 1) {
        $sql_common .=  " , mb_mailling_date = '".G5_TIME_YMDHIS."' ";
        $agree_items[] = "광고성 이메일 수신(동의)";
    }

    // 동의 로그 추가
    if (!empty($agree_items)) {
        $agree_log = "[".G5_TIME_YMDHIS.", 관리자 회원추가] " . implode(' | ', $agree_items) . "\n";
        $sql_common .= " , mb_agree_log = CONCAT('{$agree_log}', IFNULL(mb_agree_log, ''))";
    }
    sql_query(" insert into {$g5['member_table']} set mb_id = '{$mb_id}', mb_password = '" . get_encrypt_string($mb_password) . "', mb_datetime = '" . G5_TIME_YMDHIS . "', mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_email_certify = '" . G5_TIME_YMDHIS . "', {$sql_common} ");
} elseif ($w == 'u') {
    $mb = get_member($mb_id);
    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        alert('존재하지 않는 회원자료입니다.');
    }

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');
    }

    if ($is_admin !== 'super' && is_admin($mb['mb_id']) === 'super') {
        alert('최고관리자의 비밀번호를 수정할수 없습니다.');
    }

    if ($mb_id === $member['mb_id'] && $_POST['mb_level'] != $mb['mb_level']) {
        alert($mb['mb_id'] . ' : 로그인 중인 관리자 레벨은 수정할 수 없습니다.');
    }

    if ($posts['mb_leave_date'] || $posts['mb_intercept_date']){
        if ($member['mb_id'] === $mb['mb_id'] || is_admin($mb['mb_id']) === 'super'){
            alert('해당 관리자의 탈퇴 일자 또는 접근 차단 일자를 수정할 수 없습니다.');
        }
    }

    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$mb_nick}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$mb_email}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }

    if ($mb_password) {
        $sql_password = " , mb_password = '" . get_encrypt_string($mb_password) . "' ";
    } else {
        $sql_password = "";
    }

    if (isset($passive_certify) && $passive_certify) {
        $sql_certify = " , mb_email_certify = '" . G5_TIME_YMDHIS . "' ";
    } else {
        $sql_certify = "";
    }

    // 현재 데이터 조회
    $row = sql_fetch("select * from {$g5['member_table']} where mb_id = '{$mb_id}' ");
    $agree_items = [];
        
    // 마케팅 목적의 개인정보 수집 및 이용
    $sql_marketing_date = "";
    if ($row['mb_marketing_agree'] !== $mb_marketing_agree) {
        $sql_marketing_date .= " , mb_marketing_date = '".G5_TIME_YMDHIS."' ";
        $agree_items[] = "마케팅 목적의 개인정보 수집 및 이용(" . ($mb_marketing_agree == 1 ? "동의" : "철회") . ")";
    }

    // 광고성 이메일 수신
    $sql_mailling_date = "";
    if ((string) $row['mb_mailling'] !== (string) $posts['mb_mailling']) {
        $sql_mailling_date .= " , mb_mailling_date = '".G5_TIME_YMDHIS."' ";
        $agree_items[] = "광고성 이메일 수신(" . ($posts['mb_mailling'] == 1 ? "동의" : "철회") . ")";
    }
    
    // 동의 로그 추가
    $sql_agree_log = "";
    if (!empty($agree_items)) {
        $agree_log = "[".G5_TIME_YMDHIS.", 관리자 회원수정] " . implode(' | ', $agree_items) . "\n";
        $sql_agree_log .= " , mb_agree_log = CONCAT('{$agree_log}', IFNULL(mb_agree_log, ''))";
    }
    
    $sql = " update {$g5['member_table']}
                set {$sql_common}
                     {$sql_password}
                     {$sql_certify}
                     {$sql_mailling_date}
                     {$sql_marketing_date}
                     {$sql_agree_log}
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

if (function_exists('get_admin_captcha_by')) {
    get_admin_captcha_by('remove');
}

run_event('admin_member_form_update', $w, $mb_id);

goto_url('./member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $mb_id, false);
