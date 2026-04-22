<?php
include_once('./_common.php');

$request = member_read_leave_request($_POST, $url);
member_complete_leave_request($member, $is_admin, $request);
