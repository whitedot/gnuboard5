<?php
if (!defined('_GNUBOARD_')) {
    exit;
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

        $row = sql_fetch_prepared(" select mb_id, mb_name, mb_nick, mb_email from {$GLOBALS['g5']['member_table']} where mb_nick = :mb_nick ", array('mb_nick' => $mb_nick));
        if (isset($row['mb_id']) && $row['mb_id']) {
            alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
        }

        $row = sql_fetch_prepared(" select mb_id, mb_name, mb_nick, mb_email from {$GLOBALS['g5']['member_table']} where mb_email = :mb_email ", array('mb_email' => $mb_email));
        if (isset($row['mb_id']) && $row['mb_id']) {
            alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
        }

        return;
    }

    $row = sql_fetch_prepared(" select mb_id, mb_name, mb_nick, mb_email from {$GLOBALS['g5']['member_table']} where mb_nick = :mb_nick and mb_id <> :mb_id ", array('mb_nick' => $mb_nick, 'mb_id' => $mb_id));
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }

    $row = sql_fetch_prepared(" select mb_id, mb_name, mb_nick, mb_email from {$GLOBALS['g5']['member_table']} where mb_email = :mb_email and mb_id <> :mb_id ", array('mb_email' => $mb_email, 'mb_id' => $mb_id));
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }
}

function member_validate_confirm_access($is_guest)
{
    if ($is_guest) {
        alert('로그인 한 회원만 접근하실 수 있습니다.', G5_MEMBER_URL . '/login.php');
    }
}

function member_validate_leave_request(array $member, $is_admin, array $request)
{
    if (!$member['mb_id']) {
        alert('회원만 접근하실 수 있습니다.');
    }

    if ($is_admin == 'super') {
        alert('최고 관리자는 탈퇴할 수 없습니다');
    }

    if (!($request['mb_password'] && check_password($request['mb_password'], $member['mb_password']))) {
        alert('비밀번호가 틀립니다.');
    }
}

function member_validate_cert_refresh_page_access($is_member, array $member, array $config)
{
    if (!$is_member) {
        alert('잘못된 접근입니다.', G5_URL);
    }

    if (!empty($member['mb_certify']) && strlen($member['mb_dupinfo']) != 64) {
        alert('잘못된 접근입니다.', G5_URL);
    }

    if ($config['cf_cert_use'] == 0) {
        alert('본인인증을 이용 할 수 없습니다. 관리자에게 문의 하십시오.', G5_URL);
    }
}

function member_validate_cert_refresh_request(array $request)
{
    if (!($request['w'] === '' || $request['w'] === 'u')) {
        alert('w 값이 제대로 넘어오지 않았습니다.');
    }

    if ($request['w'] !== '') {
        alert('잘못된 접근입니다', G5_URL);
    }

    if (!$request['mb_id']) {
        alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');
    }
}

function member_validate_cert_refresh_dupinfo_conflict($mb_id)
{
    if (!empty($mb_id)) {
        alert('입력하신 본인확인 정보로 가입된 내역이 존재합니다.');
    }
}

function member_validate_email_stop_member(array $member_row)
{
    if (empty($member_row['mb_id'])) {
        alert('존재하는 회원이 아닙니다.', G5_URL);
    }
}

function member_validate_email_stop_hash(array $request, array $member_row)
{
    if ($request['mb_md5']) {
        $tmp_md5 = md5($member_row['mb_id'] . $member_row['mb_email'] . $member_row['mb_datetime']);
        if ($request['mb_md5'] == $tmp_md5) {
            return;
        }
    }

    alert('제대로 된 값이 넘어오지 않았습니다.', G5_URL);
}
