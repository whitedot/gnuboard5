<?php
include_once('./_common.php');

$request = member_read_register_result_request($_SESSION);
$mb = member_find_register_result_member($request['mb_id']);

// 회원정보가 없다면 초기 페이지로 이동
if (!(isset($mb['mb_id']) && $mb['mb_id']))
    goto_url(G5_URL);

$page_view = member_build_register_result_page_view($mb);
MemberPageController::render($page_view['title'], 'register_result.skin.php', $page_view['data']);
