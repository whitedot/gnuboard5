<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$request = member_read_register_email_update_request($_POST);
member_validate_register_email_update_request($request);
member_resend_register_email_change($request);

alert("인증메일을 {$request['mb_email']} 메일로 다시 보내 드렸습니다.\\n\\n잠시후 {$request['mb_email']} 메일을 확인하여 주십시오.", G5_URL);
