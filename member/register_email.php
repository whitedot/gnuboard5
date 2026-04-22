<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

$request = member_read_register_email_request($_GET);
$mb = member_prepare_register_email_page($request);
$page_view = member_build_register_email_page_view($mb, $request['mb_id']);

MemberPageController::render($page_view['title'], 'register_email.skin.php', $page_view['data']);
