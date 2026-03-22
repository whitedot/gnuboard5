<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

$mb_id = isset($_GET['mb_id']) ? substr(clean_xss_tags($_GET['mb_id']), 0, 20) : '';
$sql = " select mb_email, mb_datetime, mb_ip, mb_email_certify, mb_id from {$g5['member_table']} where mb_id = '{$mb_id}' ";
$mb = sql_fetch($sql);

if(! (isset($mb['mb_id']) && $mb['mb_id'])){
    alert("해당 회원이 존재하지 않습니다.", G5_URL);
}

if (substr($mb['mb_email_certify'],0,1)!=0) {
    alert("이미 메일인증 하신 회원입니다.", G5_URL);
}

$ckey = isset($_GET['ckey']) ? trim($_GET['ckey']) : '';
$key  = md5($mb['mb_ip'].$mb['mb_datetime']);

if(!$ckey || $ckey != $key)
    alert('올바른 방법으로 이용해 주십시오.', G5_URL);
MemberPageController::render('메일인증 메일주소 변경', 'register_email.skin.php', array(
    'mb' => $mb,
    'mb_id' => $mb_id,
));
