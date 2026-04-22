<?php
include_once('./_common.php');

$request = member_read_email_certify_request($_GET);
member_complete_email_certify_request($request);
