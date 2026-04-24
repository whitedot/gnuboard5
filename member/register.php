<?php
include_once('./_common.php');

member_validate_register_page_access($is_member);
member_reset_registration_progress();

$page_view = member_build_register_intro_page_view($config);
MemberPageController::renderPage('register', $page_view);
