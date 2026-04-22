<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$request = member_read_register_form_request($w, $_POST);
member_prepare_register_form_entry();
member_validate_register_form_request($request, $member, $is_member, $is_admin);

$page_view = member_build_register_form_page_view($w, $request, $member, $config);
MemberPageController::render($page_view['title'], 'register_form.skin.php', $page_view['data'], $page_view['options']);
