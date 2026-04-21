<?php
$sub_menu = "200100";
require_once "./_common.php";
require_once G5_LIB_PATH . "/register.lib.php";

if ($w == 'u') {
    check_demo();
}

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$member_request = member_read_admin_member_request($_POST);
$mb_id = $member_request['mb_id'];
$mb_email = $member_request['mb_email'];
$mb_nick = $member_request['mb_nick'];

$existing_member = member_validate_admin_member_request($member_request, $member, $is_admin, $w);
member_validate_admin_uniqueness($mb_id, $mb_nick, $mb_email, $w);

if ($w == '') {
    $insert_params = member_build_admin_insert_fields($member_request);

    if (!member_insert_admin_account($insert_params)) {
        alert('회원정보를 저장하는 중 오류가 발생했습니다.');
    }
} elseif ($w == 'u') {
    $update_params = member_build_admin_update_fields($member_request, $existing_member);

    if (!member_update_admin_account($mb_id, $update_params)) {
        alert('회원정보를 수정하는 중 오류가 발생했습니다.');
    }
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

if (function_exists('get_admin_captcha_by')) {
    get_admin_captcha_by('remove');
}

run_event('admin_member_form_update', $w, $mb_id);

goto_url('./member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $mb_id, false);
