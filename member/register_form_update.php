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

if($w == 'u')
    $mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';
else if($w == '')
    $mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
else
    alert('잘못된 접근입니다', G5_URL);

if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');

$mb_password    = isset($_POST['mb_password']) ? trim($_POST['mb_password']) : '';
$mb_password_re = isset($_POST['mb_password_re']) ? trim($_POST['mb_password_re']) : '';
$mb_name        = isset($_POST['mb_name']) ? trim($_POST['mb_name']) : '';
$mb_nick        = isset($_POST['mb_nick']) ? trim($_POST['mb_nick']) : '';
$mb_email       = isset($_POST['mb_email']) ? trim($_POST['mb_email']) : '';
$mb_sex         = isset($_POST['mb_sex'])           ? trim($_POST['mb_sex'])         : "";
$mb_birth       = isset($_POST['mb_birth'])         ? trim($_POST['mb_birth'])       : "";
$mb_hp          = isset($_POST['mb_hp'])            ? trim($_POST['mb_hp'])          : "";
$mb_mailling    = isset($_POST['mb_mailling'])      ? trim($_POST['mb_mailling'])    : "0";
$mb_open        = isset($_POST['mb_open'])          ? trim($_POST['mb_open'])        : "0";
$mb_name        = clean_xss_tags($mb_name, 1, 1);
$mb_email       = get_email_address($mb_email);

$mb_marketing_agree     = isset($_POST['mb_marketing_agree'])   ? trim($_POST['mb_marketing_agree'])    : "0";

run_event('register_form_update_before', $mb_id, $w);

if ($w == '' || $w == 'u') {

    if ($msg = empty_mb_id($mb_id))         alert($msg, "", true, true); // alert($msg, $url, $error, $post);
    if ($msg = valid_mb_id($mb_id))         alert($msg, "", true, true);
    if ($msg = count_mb_id($mb_id))         alert($msg, "", true, true);

    // 이름, 닉네임에 utf-8 이외의 문자가 포함됐다면 오류
    // 서버환경에 따라 정상적으로 체크되지 않을 수 있음.
    $tmp_mb_name = iconv('UTF-8', 'UTF-8//IGNORE', $mb_name);
    if($tmp_mb_name != $mb_name) {
        alert('이름을 올바르게 입력해 주십시오.');
    }
    $tmp_mb_nick = iconv('UTF-8', 'UTF-8//IGNORE', $mb_nick);
    if($tmp_mb_nick != $mb_nick) {
        alert('닉네임을 올바르게 입력해 주십시오.');
    }

    // 비밀번호를 체크하는 상태의 기본값은 true이며, 비밀번호를 체크하지 않으려면 hook 을 통해 false 값으로 바꿔야 합니다.
    $is_check_password = run_replace('register_member_password_check', true, $mb_id, $mb_nick, $mb_email, $w);

    if ($is_check_password){
        if ($w == '' && !$mb_password)
            alert('비밀번호가 넘어오지 않았습니다.');
        if ($w == '' && $mb_password != $mb_password_re)
            alert('비밀번호가 일치하지 않습니다.');
    }

    if ($msg = empty_mb_name($mb_name))       alert($msg, "", true, true);
    if ($msg = empty_mb_nick($mb_nick))     alert($msg, "", true, true);
    if ($msg = empty_mb_email($mb_email))   alert($msg, "", true, true);
    if ($msg = reserve_mb_id($mb_id))       alert($msg, "", true, true);
    if ($msg = reserve_mb_nick($mb_nick))   alert($msg, "", true, true);
    // 이름에 한글명 체크를 하지 않는다.
    //if ($msg = valid_mb_name($mb_name))     alert($msg, "", true, true);
    if ($msg = valid_mb_nick($mb_nick))     alert($msg, "", true, true);
    if ($msg = valid_mb_email($mb_email))   alert($msg, "", true, true);
    if ($msg = prohibit_mb_email($mb_email))alert($msg, "", true, true);

    // 휴대폰 필수입력일 경우 휴대폰번호 유효성 체크
    if (($config['cf_use_hp'] || $config['cf_cert_hp'] || $config['cf_cert_simple']) && $config['cf_req_hp']) {
        if ($msg = valid_mb_hp($mb_hp))     alert($msg, "", true, true);
    }

    if ($w=='') {
        if ($msg = exist_mb_id($mb_id))     alert($msg);

        if (get_session('ss_check_mb_id') != $mb_id || get_session('ss_check_mb_nick') != $mb_nick || get_session('ss_check_mb_email') != $mb_email) {
            set_session('ss_check_mb_id', '');
            set_session('ss_check_mb_nick', '');
            set_session('ss_check_mb_email', '');

            alert('올바른 방법으로 이용해 주십시오.');
        }

        // 본인확인 체크
        if($config['cf_cert_use'] && $config['cf_cert_req']) {
            $post_cert_no = isset($_POST['cert_no']) ? trim($_POST['cert_no']) : '';
            if($post_cert_no !== get_session('ss_cert_no') || ! get_session('ss_cert_no'))
                alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
        }

    } else {
        // 자바스크립트로 정보변경이 가능한 버그 수정
        // 닉네임수정일이 지나지 않았다면
        if ($member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400)))
            $mb_nick = $member['mb_nick'];
        // 회원정보의 메일을 이전 메일로 옮기고 아래에서 비교함
        $old_email = $member['mb_email'];
    }

    run_event('register_form_update_valid', $w, $mb_id, $mb_nick, $mb_email);

    if ($msg = exist_mb_nick($mb_nick, $mb_id))     alert($msg, "", true, true);
    if ($msg = exist_mb_email($mb_email, $mb_id))   alert($msg, "", true, true);
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

    $insert_fields = array(
        'mb_id' => $mb_id,
        'mb_password' => get_encrypt_string($mb_password),
        'mb_name' => $mb_name,
        'mb_nick' => $mb_nick,
        'mb_nick_date' => G5_TIME_YMD,
        'mb_email' => $mb_email,
        'mb_today_login' => G5_TIME_YMDHIS,
        'mb_datetime' => G5_TIME_YMDHIS,
        'mb_ip' => $_SERVER['REMOTE_ADDR'],
        'mb_level' => $config['cf_register_level'],
        'mb_login_ip' => $_SERVER['REMOTE_ADDR'],
        'mb_mailling' => $mb_mailling,
        'mb_open' => $mb_open,
        'mb_open_date' => G5_TIME_YMD,
        'mb_marketing_agree' => $mb_marketing_agree,
    );

    foreach ($sql_certify as $field => $value) {
        $insert_fields[$field] = $value;
    }

    if (!$config['cf_use_email_certify']) {
        $insert_fields['mb_email_certify'] = G5_TIME_YMDHIS;
    }

    member_build_create_agree_updates($insert_fields, $mb_marketing_agree, $mb_mailling);

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

    $update_fields = array(
        'mb_nick' => $mb_nick,
        'mb_mailling' => $mb_mailling,
        'mb_open' => $mb_open,
        'mb_email' => $mb_email,
        'mb_marketing_agree' => $mb_marketing_agree,
    );

    if ($mb_password) {
        $update_fields['mb_password'] = get_encrypt_string($mb_password);
    }

    if ($mb_nick_default != $mb_nick) {
        $update_fields['mb_nick_date'] = G5_TIME_YMD;
    }

    if (isset($mb_open_default) && $mb_open_default != $mb_open) {
        $update_fields['mb_open_date'] = G5_TIME_YMD;
    }

    if ($old_email != $mb_email && $config['cf_use_email_certify']) {
        $update_fields['mb_email_certify'] = '';
    }

    foreach ($sql_certify as $field => $value) {
        $update_fields[$field] = $value;
    }

    member_build_update_agree_updates($update_fields, $mb_id, $mb_marketing_agree_default, $mb_marketing_agree, $mb_mailling_default, $mb_mailling);

    if (!member_update_account_with_history($mb_id, $update_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no)) {
        alert('회원정보를 수정하는 중 오류가 발생했습니다.');
    }
}


$msg = "";

// 인증메일 발송
if ($config['cf_use_email_certify'] && $old_email != $mb_email) {
    MemberNotificationService::sendRegisterEmailChange($mb_id, $mb_name, $mb_email, $w);
}


if(isset($_SESSION['ss_cert_type'])) unset($_SESSION['ss_cert_type']);
if(isset($_SESSION['ss_cert_no'])) unset($_SESSION['ss_cert_no']);
if(isset($_SESSION['ss_cert_hash'])) unset($_SESSION['ss_cert_hash']);
if(isset($_SESSION['ss_cert_birth'])) unset($_SESSION['ss_cert_birth']);
if(isset($_SESSION['ss_cert_adult'])) unset($_SESSION['ss_cert_adult']);

MemberRegisterResponseFlow::finishSubmit($mb_id, $member, $w, $old_email, $mb_email, $msg);
