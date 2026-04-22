<?php
include_once('./_common.php');

member_validate_confirm_access($is_guest);

$request = member_read_confirm_request($url);
$url = member_prepare_confirm_url($request['url']);
$page_view = member_build_confirm_page_view($url);

MemberPageController::render($page_view['title'], 'member_confirm.skin.php', $page_view['data'], $page_view['options']);
