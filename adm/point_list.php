<?php
$sub_menu = "200200";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

alert('회원 전용 모드에서는 포인트 기능을 제공하지 않습니다.', './member_list.php');
