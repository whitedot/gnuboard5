<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_validate_utf8_identity_fields($mb_name, $mb_nick)
{
    $tmp_mb_name = iconv('UTF-8', 'UTF-8//IGNORE', $mb_name);
    if ($tmp_mb_name != $mb_name) {
        alert('이름을 올바르게 입력해 주십시오.');
    }

    $tmp_mb_nick = iconv('UTF-8', 'UTF-8//IGNORE', $mb_nick);
    if ($tmp_mb_nick != $mb_nick) {
        alert('닉네임을 올바르게 입력해 주십시오.');
    }
}

function member_validate_register_submit_request($w, array $request, $is_admin)
{
    referer_check();

    if (!($w == '' || $w == 'u')) {
        alert('w 값이 제대로 넘어오지 않았습니다.');
    }

    if ($w == 'u' && $is_admin == 'super' && file_exists(G5_PATH . '/DEMO')) {
        alert('데모 화면에서는 하실(보실) 수 없는 작업입니다.');
    }

    if (run_replace('register_member_chk_captcha', !chk_captcha(), $w)) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }

    if (!$request['mb_id']) {
        alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');
    }
}

function member_validate_register_request($w, array &$request, array $member, array $config)
{
    $mb_id = $request['mb_id'];
    $mb_password = $request['mb_password'];
    $mb_password_re = $request['mb_password_re'];
    $mb_name = $request['mb_name'];
    $mb_nick = $request['mb_nick'];
    $mb_email = $request['mb_email'];
    $mb_hp = $request['mb_hp'];

    if ($msg = empty_mb_id($mb_id)) alert($msg, "", true, true);
    if ($msg = valid_mb_id($mb_id)) alert($msg, "", true, true);
    if ($msg = count_mb_id($mb_id)) alert($msg, "", true, true);

    member_validate_utf8_identity_fields($mb_name, $mb_nick);

    $is_check_password = run_replace('register_member_password_check', true, $mb_id, $mb_nick, $mb_email, $w);
    if ($is_check_password) {
        if ($w == '' && !$mb_password) {
            alert('비밀번호가 넘어오지 않았습니다.');
        }
        if ($w == '' && $mb_password != $mb_password_re) {
            alert('비밀번호가 일치하지 않습니다.');
        }
    }

    if ($msg = empty_mb_name($mb_name)) alert($msg, "", true, true);
    if ($msg = empty_mb_nick($mb_nick)) alert($msg, "", true, true);
    if ($msg = empty_mb_email($mb_email)) alert($msg, "", true, true);
    if ($msg = reserve_mb_id($mb_id)) alert($msg, "", true, true);
    if ($msg = reserve_mb_nick($mb_nick)) alert($msg, "", true, true);
    if ($msg = valid_mb_nick($mb_nick)) alert($msg, "", true, true);
    if ($msg = valid_mb_email($mb_email)) alert($msg, "", true, true);
    if ($msg = prohibit_mb_email($mb_email)) alert($msg, "", true, true);

    if (($config['cf_use_hp'] || $config['cf_cert_hp'] || $config['cf_cert_simple']) && $config['cf_req_hp']) {
        if ($msg = valid_mb_hp($mb_hp)) alert($msg, "", true, true);
    }

    if ($w == '') {
        if ($msg = exist_mb_id($mb_id)) alert($msg);

        if (get_session('ss_check_mb_id') != $mb_id || get_session('ss_check_mb_nick') != $mb_nick || get_session('ss_check_mb_email') != $mb_email) {
            set_session('ss_check_mb_id', '');
            set_session('ss_check_mb_nick', '');
            set_session('ss_check_mb_email', '');
            alert('올바른 방법으로 이용해 주십시오.');
        }

        if ($config['cf_cert_use'] && $config['cf_cert_req']) {
            if ($request['cert_no'] !== get_session('ss_cert_no') || !get_session('ss_cert_no')) {
                alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
            }
        }

        return array('old_email' => '');
    }

    $old_email = isset($member['mb_email']) ? $member['mb_email'] : '';
    if ($member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) {
        $request['mb_nick'] = $member['mb_nick'];
    }

    return array('old_email' => $old_email);
}

function member_validate_register_uniqueness($mb_id, $mb_nick, $mb_email)
{
    if ($msg = exist_mb_nick($mb_nick, $mb_id)) alert($msg, "", true, true);
    if ($msg = exist_mb_email($mb_email, $mb_id)) alert($msg, "", true, true);
}

function member_validate_register_update_submission(array $request)
{
    if (!trim(get_session('ss_mb_id'))) {
        alert('로그인 되어 있지 않습니다.');
    }

    if ($request['posted_mb_id'] != $request['mb_id']) {
        alert("로그인된 정보와 수정하려는 정보가 틀리므로 수정할 수 없습니다.\\n만약 올바르지 않은 방법을 사용하신다면 바로 중지하여 주십시오.");
    }
}

function member_validate_email_certify_member(array $member_row)
{
    if (empty($member_row['mb_id'])) {
        alert('존재하는 회원이 아닙니다.', G5_URL);
    }

    if ($member_row['mb_leave_date'] || $member_row['mb_intercept_date']) {
        alert('탈퇴 또는 차단된 회원입니다.', G5_URL);
    }
}

function member_validate_email_certify_hash($mb_md5, $stored_hash, $mb_id)
{
    if (!$mb_md5) {
        alert('제대로 된 값이 넘어오지 않았습니다.', G5_URL);
    }

    if ($mb_md5 !== $stored_hash) {
        alert('메일인증 요청 정보가 올바르지 않습니다.', G5_URL);
    }

    return "메일인증 처리를 완료 하였습니다.\\n\\n지금부터 {$mb_id} 아이디로 로그인 가능합니다.";
}

function member_validate_register_email_member(array $mb)
{
    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        alert('해당 회원이 존재하지 않습니다.', G5_URL);
    }

    if (substr($mb['mb_email_certify'], 0, 1) != 0) {
        alert('이미 메일인증 하신 회원입니다.', G5_URL);
    }
}

function member_validate_register_email_key($ckey, $key)
{
    if (!$ckey || $ckey != $key) {
        alert('올바른 방법으로 이용해 주십시오.', G5_URL);
    }
}

function member_validate_register_email_update_request(array $request)
{
    if (!$request['mb_id'] || !$request['mb_email']) {
        alert('올바른 방법으로 이용해 주십시오.', G5_URL);
    }
}

function member_validate_register_email_update_captcha()
{
    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

function member_validate_register_email_pending_member($mb)
{
    if (!$mb) {
        alert('이미 메일인증 하신 회원입니다.', G5_URL);
    }
}

function member_validate_register_email_uniqueness($count, $mb_email)
{
    if ($count) {
        alert("{$mb_email} 메일은 이미 존재하는 메일주소 입니다.\\n\\n다른 메일주소를 입력해 주십시오.");
    }
}

function member_validate_register_page_access($is_member)
{
    if ($is_member) {
        goto_url(G5_URL);
    }
}

function member_validate_register_form_request(array $request, array $member, $is_member, $is_admin)
{
    if (!($request['w'] === '' || $request['w'] === 'u')) {
        alert('w 값이 제대로 넘어오지 않았습니다.');
    }

    if ($request['w'] === '') {
        if ($is_member) {
            goto_url(G5_URL);
        }

        referer_check();
        if (!$request['agree']) {
            alert('회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.', G5_MEMBER_URL . '/register.php');
        }

        if (!$request['agree2']) {
            alert('개인정보 수집 및 이용의 내용에 동의하셔야 회원가입 하실 수 있습니다.', G5_MEMBER_URL . '/register.php');
        }

        return;
    }

    if ($is_admin == 'super') {
        alert('관리자의 회원정보는 관리자 화면에서 수정해 주십시오.', G5_URL);
    }

    if (!$is_member) {
        alert('로그인 후 이용하여 주십시오.', G5_URL);
    }

    if ($member['mb_id'] != $request['mb_id']) {
        alert('로그인된 회원과 넘어온 정보가 서로 다릅니다.');
    }

    if ($request['mb_id'] && !$request['mb_password']) {
        alert('비밀번호를 입력해 주세요.');
    }

    if ($request['mb_password']) {
        $pass_check = $request['is_update']
            ? ($member['mb_password'] === $request['mb_password'])
            : check_password($request['mb_password'], $member['mb_password']);

        if (!$pass_check) {
            alert('비밀번호가 틀립니다.');
        }
    }
}
