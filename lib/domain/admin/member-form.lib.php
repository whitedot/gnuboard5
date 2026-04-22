<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_read_member_form_request(array $request, $w)
{
    return array(
        'w' => $w,
        'mb_id' => isset($request['mb_id']) && !is_array($request['mb_id']) ? trim((string) $request['mb_id']) : '',
    );
}

function admin_get_member_form_defaults()
{
    return array(
        'mb_certify' => null,
        'mb_adult' => null,
        'mb_intercept_date' => null,
        'mb_id' => null,
        'mb_name' => null,
        'mb_nick' => null,
        'mb_email' => null,
        'mb_hp' => null,
        'mb_memo' => null,
        'mb_leave_date' => null,
    );
}

function admin_build_member_form_view(array $request, array $member, $is_admin, array $config)
{
    global $g5;

    $w = $request['w'];
    $mb_id = $request['mb_id'];
    $mb = admin_get_member_form_defaults();
    $sound_only = '';
    $required_mb_id = '';
    $required_mb_id_class = '';
    $required_mb_password = '';
    $html_title = '';

    if ($w == '') {
        $required_mb_id = 'required';
        $required_mb_id_class = 'required alnum_';
        $required_mb_password = 'required';
        $sound_only = '<strong class="caption-sr-only">필수</strong>';
        $mb['mb_mailling'] = 1;
        $mb['mb_open'] = 1;
        $mb['mb_level'] = $config['cf_register_level'];
        $mb['mb_marketing_agree'] = 0;
        $html_title = '추가';
    } elseif ($w == 'u') {
        $mb = get_member($mb_id);
        if (!$mb['mb_id']) {
            alert('존재하지 않는 회원자료입니다.');
        }

        if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
            alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');
        }

        $required_mb_id = 'readonly';
        $html_title = '수정';
        $mb['mb_name'] = get_text($mb['mb_name']);
        $mb['mb_nick'] = get_text($mb['mb_nick']);
        $mb['mb_email'] = get_text($mb['mb_email']);
        $mb['mb_birth'] = get_text($mb['mb_birth']);
        $mb['mb_hp'] = get_text($mb['mb_hp']);
    } else {
        alert('제대로 된 값이 넘어오지 않았습니다.');
    }

    switch ($mb['mb_certify']) {
        case 'simple':
            $mb_certify_case = '간편인증';
            $mb_certify_val = 'simple';
            break;
        case 'hp':
            $mb_certify_case = '휴대폰';
            $mb_certify_val = 'hp';
            break;
        case 'admin':
            $mb_certify_case = '관리자 수정';
            $mb_certify_val = 'admin';
            break;
        default:
            $mb_certify_case = '';
            $mb_certify_val = 'admin';
            break;
    }

    $mb_cert_history = '';
    if ($mb_id) {
        $sql = "select * from {$g5['member_cert_history_table']} where mb_id = :mb_id order by ch_id asc";
        $mb_cert_history = sql_query_prepared($sql, array('mb_id' => $mb_id));
    }

    $title_prefix = $mb['mb_intercept_date'] ? '차단된 ' : '';

    return array(
        'mb' => $mb,
        'display_mb_id' => member_get_display_id($mb),
        'mask_preserved_id' => member_should_mask_preserved_id($mb),
        'sound_only' => $sound_only,
        'required_mb_id' => $required_mb_id,
        'required_mb_id_class' => $required_mb_id_class,
        'required_mb_password' => $required_mb_password,
        'html_title' => $html_title,
        'mb_certify_case' => $mb_certify_case,
        'mb_certify_val' => $mb_certify_val,
        'mb_certify_yes' => $mb['mb_certify'] ? 'checked="checked"' : '',
        'mb_certify_no' => !$mb['mb_certify'] ? 'checked="checked"' : '',
        'mb_adult_yes' => $mb['mb_adult'] ? 'checked="checked"' : '',
        'mb_adult_no' => !$mb['mb_adult'] ? 'checked="checked"' : '',
        'mb_mailling_yes' => $mb['mb_mailling'] ? 'checked="checked"' : '',
        'mb_mailling_no' => !$mb['mb_mailling'] ? 'checked="checked"' : '',
        'mb_open_yes' => $mb['mb_open'] ? 'checked="checked"' : '',
        'mb_open_no' => !$mb['mb_open'] ? 'checked="checked"' : '',
        'mb_marketing_agree_yes' => $mb['mb_marketing_agree'] ? 'checked="checked"' : '',
        'mb_marketing_agree_no' => !$mb['mb_marketing_agree'] ? 'checked="checked"' : '',
        'mb_cert_history' => $mb_cert_history,
        'title' => $title_prefix . '회원 ' . $html_title,
    );
}

function admin_build_member_form_page_view(array $member_form_view)
{
    return array(
        'title' => $member_form_view['title'],
        'admin_container_class' => 'admin-page-member-form',
        'admin_page_subtitle' => '기본정보, 인증 연락처, 동의 상태, 활동 이력을 탭에서 빠르게 관리하세요.',
        'pg_anchor_menu' => admin_build_anchor_menu(admin_member_form_tabs(), array(
            'nav_id' => 'member_tabs_nav',
            'nav_class' => 'tab-nav-justified',
            'nav_aria_label' => '회원 등록/수정 탭',
            'link_class' => 'tab-trigger-underline-justified js-member-tab-link',
            'active_class' => 'active',
            'as_tabs' => true,
            'link_id_prefix' => 'member_tab_',
        )),
    );
}

function admin_member_form_tabs()
{
    return array(
        array('id' => 'anc_mb_basic', 'label' => '기본 정보'),
        array('id' => 'anc_mb_contact', 'label' => '인증 연락처'),
        array('id' => 'anc_mb_consent', 'label' => '수신 및 공개 설정'),
        array('id' => 'anc_mb_profile', 'label' => '관리 메모'),
        array('id' => 'anc_mb_history', 'label' => '인증 및 활동 내역'),
    );
}

function admin_read_member_delete_request(array $post, $url = '')
{
    return array(
        'mb_id' => isset($post['mb_id']) ? trim((string) $post['mb_id']) : '',
        'url' => $url,
    );
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

function admin_build_member_delete_redirect($url, $qstr, $mb_id)
{
    return "./member_list.php?{$qstr}";
}

function admin_complete_member_delete_request(array $request, array $member, $auth, $sub_menu, $qstr)
{
    auth_check_menu($auth, $sub_menu, 'd');

    $mb = admin_validate_member_delete_request($request, $member);
    check_admin_token();

    member_delete($mb['mb_id']);

    goto_url(admin_build_member_delete_redirect($request['url'], $qstr, $mb['mb_id']));
}

function admin_complete_member_form_update_request($w, array $request, array $member, $is_admin, $auth, $sub_menu, $qstr)
{
    if ($w == 'u') {
        check_demo();
    }

    auth_check_menu($auth, $sub_menu, 'w');
    check_admin_token();

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
    } elseif ($w == 'u') {
        $update_params = member_build_admin_update_fields($request, $existing_member);

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
}
