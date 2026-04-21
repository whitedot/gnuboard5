<?php
if (!defined('_GNUBOARD_')) exit;

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

        return array(
            'old_email' => '',
        );
    }

    $old_email = isset($member['mb_email']) ? $member['mb_email'] : '';

    if ($member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) {
        $request['mb_nick'] = $member['mb_nick'];
    }

    return array(
        'old_email' => $old_email,
    );
}

function member_validate_register_uniqueness($mb_id, $mb_nick, $mb_email)
{
    if ($msg = exist_mb_nick($mb_nick, $mb_id)) alert($msg, "", true, true);
    if ($msg = exist_mb_email($mb_email, $mb_id)) alert($msg, "", true, true);
}

function member_validate_admin_member_request(array $request, array $member, $is_admin, $w)
{
    $mb_id = $request['mb_id'];
    $mb_password = $request['mb_password'];
    $mb_hp = $request['mb_hp'];
    $mb_nick = $request['mb_nick'];
    $posts = $request['posts'];

    if ($mb_password) {
        include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');
        if (!chk_captcha()) {
            alert('자동등록방지 숫자가 틀렸습니다.');
        }
    }

    if ($mb_hp) {
        $result = exist_mb_hp($mb_hp, $mb_id);
        if ($result) {
            alert($result);
        }
    }

    if ($msg = valid_mb_nick($mb_nick)) {
        alert($msg, "", true, true);
    }

    if ($w !== 'u') {
        return array();
    }

    $mb = get_member($mb_id);
    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        alert('존재하지 않는 회원자료입니다.');
    }

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');
    }

    if ($is_admin !== 'super' && is_admin($mb['mb_id']) === 'super') {
        alert('최고관리자의 비밀번호를 수정할수 없습니다.');
    }

    if ($mb_id === $member['mb_id'] && $posts['mb_level'] != $mb['mb_level']) {
        alert($mb['mb_id'] . ' : 로그인 중인 관리자 레벨은 수정할 수 없습니다.');
    }

    if (($posts['mb_leave_date'] || $posts['mb_intercept_date']) && ($member['mb_id'] === $mb['mb_id'] || is_admin($mb['mb_id']) === 'super')) {
        alert('해당 관리자의 탈퇴 일자 또는 접근 차단 일자를 수정할 수 없습니다.');
    }

    return $mb;
}

function member_validate_admin_uniqueness($mb_id, $mb_nick, $mb_email, $w)
{
    if ($w == '') {
        $mb = get_member($mb_id);
        if (isset($mb['mb_id']) && $mb['mb_id']) {
            alert('이미 존재하는 회원아이디입니다.\\nＩＤ : ' . $mb['mb_id'] . '\\n이름 : ' . $mb['mb_name'] . '\\n닉네임 : ' . $mb['mb_nick'] . '\\n메일 : ' . $mb['mb_email']);
        }

        $row = sql_fetch_prepared(" select mb_id, mb_name, mb_nick, mb_email from {$GLOBALS['g5']['member_table']} where mb_nick = :mb_nick ", array(
            'mb_nick' => $mb_nick,
        ));
        if (isset($row['mb_id']) && $row['mb_id']) {
            alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
        }

        $row = sql_fetch_prepared(" select mb_id, mb_name, mb_nick, mb_email from {$GLOBALS['g5']['member_table']} where mb_email = :mb_email ", array(
            'mb_email' => $mb_email,
        ));
        if (isset($row['mb_id']) && $row['mb_id']) {
            alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
        }

        return;
    }

    $row = sql_fetch_prepared(" select mb_id, mb_name, mb_nick, mb_email from {$GLOBALS['g5']['member_table']} where mb_nick = :mb_nick and mb_id <> :mb_id ", array(
        'mb_nick' => $mb_nick,
        'mb_id' => $mb_id,
    ));
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }

    $row = sql_fetch_prepared(" select mb_id, mb_name, mb_nick, mb_email from {$GLOBALS['g5']['member_table']} where mb_email = :mb_email and mb_id <> :mb_id ", array(
        'mb_email' => $mb_email,
        'mb_id' => $mb_id,
    ));
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }
}
