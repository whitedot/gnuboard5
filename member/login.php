<?php
include_once('./_common.php');

$request = member_read_login_page_request($url);
member_validate_login_page_request($request);
member_redirect_if_logged_in($is_member, $request['url']);

$login_view = member_build_login_page_view($member_view_path, $request['url']);
$page_view = member_build_login_render_page_view($login_view, $request['url']);
MemberPageController::render($page_view['title'], 'login.skin.php', $page_view['data'], $page_view['options']);
