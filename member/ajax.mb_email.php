<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$request = member_read_ajax_identity_request($_POST);
member_process_ajax_mb_email($request);
