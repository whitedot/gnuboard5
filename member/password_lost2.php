<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$request = member_read_password_lost_request($_POST);
member_complete_password_lost_request($request, $is_member);
