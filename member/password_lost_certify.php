<?php
include_once('./_common.php');

$request = member_read_password_lost_certify_request($_GET);
member_complete_password_lost_certify_request($request);
