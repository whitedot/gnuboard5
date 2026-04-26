<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_validate_member_delete_request(array $request, array $member)
{
    $mb = $request['mb_id'] ? get_member($request['mb_id']) : array();

    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        alert('회원자료가 존재하지 않습니다.');
    } elseif ($member['mb_id'] == $mb['mb_id']) {
        alert('로그인 중인 관리자는 삭제 할 수 없습니다.');
    } elseif (is_admin($mb['mb_id']) == 'super') {
        alert('최고 관리자는 삭제할 수 없습니다.');
    } elseif ($mb['mb_level'] >= $member['mb_level']) {
        alert('자신보다 권한이 높거나 같은 회원은 삭제할 수 없습니다.');
    }

    return $mb;
}

function admin_build_member_delete_redirect($qstr)
{
    return "./member_list.php?{$qstr}";
}

function admin_validate_member_delete_action()
{
    check_demo();
}

function admin_complete_member_delete_request(array $delete_action_request, array $member, $auth, $sub_menu)
{
    admin_validate_member_delete_action();

    auth_check_menu($auth, $sub_menu, 'd');

    $request = $delete_action_request['delete'];
    $mb = admin_validate_member_delete_request($request, $member);
    check_admin_token();

    member_delete($mb['mb_id']);

    goto_url(admin_build_member_delete_redirect($delete_action_request['list_qstr']));
}

function admin_validate_member_form_update_action($w)
{
    if ($w == 'u') {
        check_demo();
    }
}

function admin_persist_member_form_request($w, array $request, array $member, $is_admin)
{
    $mb_id = $request['mb_id'];
    $mb_email = $request['mb_email'];
    $mb_nick = $request['mb_nick'];

    $existing_member = member_validate_admin_member_request($request, $member, $is_admin, $w);
    member_validate_admin_uniqueness($mb_id, $mb_nick, $mb_email, $w);

    if ($w == '') {
        $insert_params = member_build_admin_insert_fields($request);

        if (!member_insert_admin_account($insert_params)) {
            alert('회원정보를 저장하는 중 오류가 발생했습니다.');
        }

        return $mb_id;
    }

    if ($w == 'u') {
        $update_params = member_build_admin_update_fields($request, $existing_member);

        if (!member_update_admin_account($mb_id, $update_params)) {
            alert('회원정보를 수정하는 중 오류가 발생했습니다.');
        }

        return $mb_id;
    }

    alert('제대로 된 값이 넘어오지 않았습니다.');

    return '';
}

function admin_build_member_form_update_redirect($qstr, $mb_id)
{
    return './member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $mb_id;
}

function admin_complete_member_form_update_request(array $update_request, array $member, $is_admin, $auth, $sub_menu)
{
    $w = $update_request['form']['w'];
    $request = $update_request['member'];

    admin_validate_member_form_update_action($w);

    auth_check_menu($auth, $sub_menu, 'w');
    check_admin_token();

    $mb_id = admin_persist_member_form_request($w, $request, $member, $is_admin);

    if (function_exists('get_admin_captcha_by')) {
        get_admin_captcha_by('remove');
    }

    run_event('admin_member_form_update', $w, $mb_id);
    goto_url(admin_build_member_form_update_redirect($update_request['list_qstr'], $mb_id), false);
}
