<?php
include_once('./_common.php');

$request = member_read_password_reset_request($_POST, $_SESSION);
member_complete_password_reset_request($request);
