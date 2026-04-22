<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');
include_once(G5_LIB_PATH.'/support/mail.lib.php');

$register_request = member_read_registration_request($w, $_POST, $_SESSION);
member_complete_register_submit_request($w, $register_request, $member, $config, $is_admin, $member_skin_path);
