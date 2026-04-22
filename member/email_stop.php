<?php
include_once('./_common.php');

$request = member_read_email_stop_request($_REQUEST, $mb_md5);
member_process_email_stop($request);
