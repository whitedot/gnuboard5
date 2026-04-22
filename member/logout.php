<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$request = member_read_logout_request($url);
member_finalize_logout($request);
