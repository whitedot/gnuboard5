<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

if ($is_member) {
    alert_close('이미 로그인중입니다.', G5_URL);
}

if (!chk_captcha()) {
    alert('자동등록방지 숫자가 틀렸습니다.');
}

$email = get_email_address(trim($_POST['mb_email']));

if (!$email)
    alert_close('메일주소 오류입니다.');

$sql = " select count(*) as cnt from {$g5['member_table']} where mb_email = :mb_email ";
$row = sql_fetch_prepared($sql, array(
    'mb_email' => $email,
));
if ($row['cnt'] > 1)
    alert('동일한 메일주소가 2개 이상 존재합니다.\\n\\n관리자에게 문의하여 주십시오.');

$sql = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime, mb_leave_date from {$g5['member_table']} where mb_email = :mb_email ";
$mb = sql_fetch_prepared($sql, array(
    'mb_email' => $email,
));
if (empty($mb['mb_id']) || $mb['mb_leave_date']) {
    alert('존재하지 않는 회원입니다.');
} elseif (is_admin($mb['mb_id'])) {
    alert('관리자 아이디는 접근 불가합니다.');
}

// 임시비밀번호 발급
$change_password = rand(100000, 999999);
$mb_lost_certify = get_encrypt_string($change_password);

// 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
$mb_nonce = md5(pack('V*', rand(), rand(), rand(), rand()));

// 임시비밀번호와 난수를 mb_lost_certify 필드에 저장
sql_query_prepared(" update {$g5['member_table']} set mb_lost_certify = :mb_lost_certify where mb_id = :mb_id ", array(
    'mb_lost_certify' => $mb_nonce . ' ' . $mb_lost_certify,
    'mb_id' => $mb['mb_id'],
));

MemberNotificationService::sendPasswordLostMail($email, $mb, $change_password, $mb_nonce, $mb_lost_certify);

alert_close($email.' 메일로 회원아이디와 비밀번호를 인증할 수 있는 메일이 발송 되었습니다.\\n\\n메일을 확인하여 주십시오.');
