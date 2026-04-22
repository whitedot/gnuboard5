<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

member_validate_cert_refresh_page_access($is_member, $member, $config);
$page_view = member_build_cert_refresh_page_view();

MemberPageController::render($page_view['title'], 'member_cert_refresh.skin.php', $page_view['data']);
