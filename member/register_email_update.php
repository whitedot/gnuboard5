<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$mb_id = isset($_POST['mb_id']) ? substr(clean_xss_tags($_POST['mb_id']), 0, 20) : '';
$mb_email = isset($_POST['mb_email']) ? get_email_address(trim($_POST['mb_email'])) : '';

if(!$mb_id || !$mb_email)
    alert('올바른 방법으로 이용해 주십시오.', G5_URL);

$sql = " select mb_name from {$g5['member_table']} where mb_id = :mb_id and substring(mb_email_certify, 1, 1) = '0' ";
$mb = sql_fetch_prepared($sql, array(
    'mb_id' => $mb_id,
));
if (!$mb) {
    alert("이미 메일인증 하신 회원입니다.", G5_URL);
}

if (!chk_captcha()) {
    alert('자동등록방지 숫자가 틀렸습니다.');
}

$sql = " select count(*) as cnt from {$g5['member_table']} where mb_id <> :mb_id and mb_email = :mb_email ";
$count = (int) sql_fetch_value_prepared($sql, array(
    'mb_id' => $mb_id,
    'mb_email' => $mb_email,
));
if ($count) {
    alert("{$mb_email} 메일은 이미 존재하는 메일주소 입니다.\\n\\n다른 메일주소를 입력해 주십시오.");
}

$mb_name = $mb['mb_name'];
MemberNotificationService::sendRegisterEmailChange($mb_id, $mb_name, $mb_email, 'u');

sql_query_prepared(" update {$g5['member_table']} set mb_email = :mb_email where mb_id = :mb_id ", array(
    'mb_email' => $mb_email,
    'mb_id' => $mb_id,
));

alert("인증메일을 {$mb_email} 메일로 다시 보내 드렸습니다.\\n\\n잠시후 {$mb_email} 메일을 확인하여 주십시오.", G5_URL);
