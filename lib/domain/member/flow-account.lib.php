<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_prepare_confirm_url($url)
{
    while (1) {
        $tmp = preg_replace('/&#[^;]+;/', '', $url);
        if ($tmp == $url) {
            break;
        }
        $url = $tmp;
    }

    $url = run_replace('member_confirm_next_url', $url);
    check_url_host($url, '', G5_URL, true);

    if ($url) {
        $url = preg_replace('#^/\\\{1,}#', '/', $url);

        if (preg_match('#^/{3,}#', $url)) {
            $url = preg_replace('#^/{3,}#', '/', $url);
        }

        if (function_exists('safe_filter_url_host')) {
            $url = safe_filter_url_host($url);
        }
    }

    return get_text($url);
}

function member_complete_leave(array $member, $url = '')
{
    $target_url = $url ? $url : G5_URL;
    member_clear_session_keys(array('ss_mb_id'));

    alert(
        $member['mb_nick'] . '님께서는 ' . date('Y년 m월 d일') . '에 회원에서 탈퇴 하셨습니다.',
        $target_url
    );
}

function member_complete_leave_request(array $member, $is_admin, array $request)
{
    member_validate_leave_request($member, $is_admin, $request);

    $leave_state = member_build_leave_state($member);
    member_mark_leave($member['mb_id'], $leave_state['leave_date'], $leave_state['leave_memo']);

    run_event('member_leave', $member);
    member_complete_leave($member, $request['url']);
}

function member_build_leave_state(array $member)
{
    return array(
        'leave_date' => date('Ymd'),
        'leave_memo' => member_build_deleted_account_memo('탈퇴'),
    );
}

function member_prepare_cert_refresh_update(array $request, array $member, array $config)
{
    $mb_hp = hyphen_hp_number($request['mb_hp']);
    $certification_session = member_read_certification_session_state();

    if ($config['cf_cert_use'] && $certification_session['cert_type'] && $certification_session['cert_dupinfo']) {
        $row = member_find_dupinfo_owner($member['mb_id'], $certification_session['cert_dupinfo']);
        member_validate_cert_refresh_dupinfo_conflict(isset($row['mb_id']) ? $row['mb_id'] : '');
    }

    $md5_cert_no = $certification_session['cert_no'];
    $cert_type = $certification_session['cert_type'];
    $update_fields = build_member_certify_fields('', $request['mb_name'], $mb_hp, $cert_type, $md5_cert_no);

    return array(
        'url' => $request['url'],
        'mb_hp' => $mb_hp,
        'md5_cert_no' => $md5_cert_no,
        'cert_type' => $cert_type,
        'update_fields' => $update_fields,
    );
}

function member_complete_cert_refresh_update_request(array $request, $is_member, array $member, array $config)
{
    member_validate_cert_refresh_page_access($is_member, $member, $config);
    member_validate_cert_refresh_request($request);

    $cert_refresh_state = member_prepare_cert_refresh_update($request, $member, $config);
    member_update_cert_refresh_fields(
        $request['mb_id'],
        $cert_refresh_state['update_fields'],
        $request['mb_name'],
        $cert_refresh_state['mb_hp'],
        $cert_refresh_state['cert_type'],
        $cert_refresh_state['md5_cert_no']
    );

    run_event('cert_refresh_update_after', $request['mb_id']);

    empty($cert_refresh_state['url']) ? goto_url(G5_URL) : goto_url($cert_refresh_state['url']);
}

function member_finalize_logout(array $request)
{
    session_unset();
    session_destroy();

    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);

    $link = G5_URL;

    if ($request['url']) {
        $url = $request['url'];

        if (substr($url, 0, 2) == '//') {
            $url = 'http:' . $url;
        }

        if (preg_match('#\\\0#', $url) || preg_match('/^\/{1,}\\\/', $url)) {
            alert('url 에 올바르지 않은 값이 포함되어 있습니다.', G5_URL);
        }

        $p = @parse_url(urldecode(str_replace('\\', '', $url)));
        if (preg_match('/^https?:\/\//i', $url) || !empty($p['scheme']) || !empty($p['host'])) {
            alert('url에 도메인을 지정할 수 없습니다.', G5_URL);
        }

        $link = $url;
    }

    run_event('member_logout', $link);

    goto_url($link);
}

function member_process_email_stop(array $request)
{
    $member_row = member_find_email_stop_member($request['mb_id']);
    member_validate_email_stop_member($member_row);
    member_validate_email_stop_hash($request, $member_row);

    member_disable_mailling($request['mb_id']);

    alert('정보메일을 보내지 않도록 수신거부 하였습니다.', G5_URL);
}
