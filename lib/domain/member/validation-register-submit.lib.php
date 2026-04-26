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
    $register_validation_session = member_read_register_validation_session_state();
    $certification_session = member_read_certification_session_state();
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

        if ($register_validation_session['checked_mb_id'] != $mb_id || $register_validation_session['checked_mb_nick'] != $mb_nick || $register_validation_session['checked_mb_email'] != $mb_email) {
            set_session('ss_check_mb_id', '');
            set_session('ss_check_mb_nick', '');
            set_session('ss_check_mb_email', '');
            alert('올바른 방법으로 이용해 주십시오.');
        }

        if ($config['cf_cert_use'] && $config['cf_cert_req']) {
            if ($request['cert_no'] !== $certification_session['cert_no'] || !$certification_session['cert_no']) {
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
    $register_validation_session = member_read_register_validation_session_state();

    if (!$register_validation_session['current_mb_id']) {
        alert('로그인 되어 있지 않습니다.');
    }

    if ($request['posted_mb_id'] != $request['mb_id']) {
        alert("로그인된 정보와 수정하려는 정보가 틀리므로 수정할 수 없습니다.\\n만약 올바르지 않은 방법을 사용하신다면 바로 중지하여 주십시오.");
    }
}
