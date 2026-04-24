<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

member_validate_cert_refresh_page_access($is_member, $member, $config);
$member_request_context = member_get_runtime_request_context();
$member_query_state = $member_request_context['query_state'];
$member_cert_refresh_request = array(
    'w' => member_read_request_w(array(), $member_query_state),
);
$page_view = member_build_cert_refresh_page_view($member_cert_refresh_request, $member, $config, member_read_current_url_encoded($member_query_state));

MemberPageController::renderPage('member_cert_refresh', $page_view);
