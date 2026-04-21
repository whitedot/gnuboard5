<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

// 리퍼러 체크
referer_check();

if (!($w == '' || $w == 'u')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == 'u' && $is_admin == 'super') {
    if (file_exists(G5_PATH.'/DEMO'))
        alert('데모 화면에서는 하실(보실) 수 없는 작업입니다.');
}

if (run_replace('register_member_chk_captcha', !chk_captcha(), $w)) {
    alert('자동등록방지 숫자가 틀렸습니다.');
}

$register_request = member_read_registration_request($w, $_POST, $_SESSION);

$mb_id = $register_request['mb_id'];
if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');

$mb_name = $register_request['mb_name'];
$mb_email = $register_request['mb_email'];
$mb_hp = $register_request['mb_hp'];

run_event('register_form_update_before', $mb_id, $w);

if ($w == '' || $w == 'u') {
    $validation_state = member_validate_register_request($w, $register_request, $member, $config);

    $mb_nick = $register_request['mb_nick'];
    $old_email = $validation_state['old_email'];

    run_event('register_form_update_valid', $w, $mb_id, $mb_nick, $mb_email);

    member_validate_register_uniqueness($mb_id, $mb_nick, $mb_email);
}

// 사용자 코드 실행
MemberSkinHookController::includeOptional($member_skin_path, 'register_form_update.head.skin.php');

//===============================================================
//  본인확인
//---------------------------------------------------------------
$mb_hp = hyphen_hp_number($mb_hp);
if($config['cf_cert_use'] && get_session('ss_cert_type') && get_session('ss_cert_dupinfo')) {
    // 중복체크
    $sql = " select mb_id from {$g5['member_table']} where mb_id <> :member_mb_id and mb_dupinfo = :mb_dupinfo ";
    $row = sql_fetch_prepared($sql, array(
        'member_mb_id' => $member['mb_id'],
        'mb_dupinfo' => get_session('ss_cert_dupinfo'),
    ));
    if (!empty($row['mb_id'])) {
        alert("입력하신 본인확인 정보로 가입된 내역이 존재합니다.");
    }
}

$md5_cert_no = get_session('ss_cert_no');
$cert_type = get_session('ss_cert_type');
$sql_certify = build_member_certify_fields($w, $mb_name, $mb_hp, $cert_type, $md5_cert_no);
//===============================================================
if ($w == '') {
    ensure_member_cert_history_table();

    $insert_fields = member_build_register_insert_fields($register_request, $config, $sql_certify);

    if (!member_insert_account_with_history($mb_id, $insert_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no)) {
        alert('회원정보를 저장하는 중 오류가 발생했습니다.');
    }

    if ($config['cf_use_email_certify']) {
        MemberNotificationService::sendRegisterEmailCertify($mb_id, $mb_name, $mb_email);

        // 가입 직후 인증 메일을 이미 발송했으므로 아래 공통 인증 메일 재발송을 생략한다.
        $old_email = $mb_email;
    }

    // 메일인증 사용하지 않는 경우에만 로그인
    if (!$config['cf_use_email_certify']) {
        set_session('ss_mb_id', $mb_id);
        if(function_exists('update_auth_session_token')) update_auth_session_token(G5_TIME_YMDHIS);
    }

    set_session('ss_mb_reg', $mb_id);

} else if ($w == 'u') {
    ensure_member_cert_history_table();

    if (!trim(get_session('ss_mb_id')))
        alert('로그인 되어 있지 않습니다.');

    if (trim($_POST['mb_id']) != $mb_id)
        alert("로그인된 정보와 수정하려는 정보가 틀리므로 수정할 수 없습니다.\\n만약 올바르지 않은 방법을 사용하신다면 바로 중지하여 주십시오.");

    $update_fields = member_build_register_update_fields($register_request, $config, $sql_certify, $old_email);

    if (!member_update_account_with_history($mb_id, $update_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no)) {
        alert('회원정보를 수정하는 중 오류가 발생했습니다.');
    }
}


$msg = "";

// 인증메일 발송
if ($config['cf_use_email_certify'] && $old_email != $mb_email) {
    MemberNotificationService::sendRegisterEmailChange($mb_id, $mb_name, $mb_email, $w);
}


member_clear_certification_session();

MemberRegisterResponseFlow::finishSubmit($mb_id, $member, $w, $old_email, $mb_email, $msg);
