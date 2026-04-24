<?php
include_once('./_common.php');

$member_request_context = member_get_runtime_request_context();
$request = member_read_register_result_request($member_request_context['session']);
$mb = member_find_register_result_member($request['mb_id']);

// 회원정보가 없다면 초기 페이지로 이동
if (!(isset($mb['mb_id']) && $mb['mb_id']))
    goto_url(G5_URL);

$page_view = member_build_register_result_page_view($mb);
MemberPageController::renderPage('register_result', $page_view);
