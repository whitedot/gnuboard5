<?php
include_once('./_common.php');

$g5['title'] = "로그인 검사";

$request = member_read_login_request($_POST, $url);
member_complete_login_request($request, $member_view_path, $_POST);
