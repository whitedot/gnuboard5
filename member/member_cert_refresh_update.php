<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$request = member_read_cert_refresh_request($w, $_POST, $url);
member_complete_cert_refresh_update_request($request, $is_member, $member, $config);
