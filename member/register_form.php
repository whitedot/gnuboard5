<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');

run_event('register_form_before');

// 불법접근을 막도록 토큰생성
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);
set_session("ss_cert_no",   "");
set_session("ss_cert_hash", "");
set_session("ss_cert_type", "");

if ($w == "") {

    // 회원 로그인을 한 경우 회원가입 할 수 없다
    // 경고창이 뜨는것을 막기위해 아래의 코드로 대체
    // alert("이미 로그인중이므로 회원 가입 하실 수 없습니다.", "./");
    if ($is_member) {
        goto_url(G5_URL);
    }

    // 리퍼러 체크
    referer_check();

    if (!isset($_POST['agree']) || !$_POST['agree']) {
        alert('회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.', G5_MEMBER_URL.'/register.php');
    }

    if (!isset($_POST['agree2']) || !$_POST['agree2']) {
        alert('개인정보 수집 및 이용의 내용에 동의하셔야 회원가입 하실 수 있습니다.', G5_MEMBER_URL.'/register.php');
    }

    $agree  = preg_replace('#[^0-9]#', '', $_POST['agree']);
    $agree2 = preg_replace('#[^0-9]#', '', $_POST['agree2']);

    $member['mb_birth'] = '';
    $member['mb_sex']   = '';
    $member['mb_name']  = '';
    if (isset($_POST['birth'])) {
        $member['mb_birth'] = $_POST['birth'];
    }
    if (isset($_POST['sex'])) {
        $member['mb_sex']   = $_POST['sex'];
    }
    if (isset($_POST['mb_name'])) {
        $member['mb_name']  = $_POST['mb_name'];
    }

    $g5['title'] = '회원 가입';

} else if ($w == 'u') {

    if ($is_admin == 'super')
        alert('관리자의 회원정보는 관리자 화면에서 수정해 주십시오.', G5_URL);

    if (!$is_member)
        alert('로그인 후 이용하여 주십시오.', G5_URL);

    if ($member['mb_id'] != $_POST['mb_id'])
        alert('로그인된 회원과 넘어온 정보가 서로 다릅니다.');

    /*
    if (!($member[mb_password] == sql_password($_POST[mb_password]) && $_POST[mb_password]))
        alert("비밀번호가 틀립니다.");

    // 수정 후 다시 이 폼으로 돌아오기 위해 임시로 저장해 놓음
    set_session("ss_tmp_password", $_POST[mb_password]);
    */
    
    if($_POST['mb_id'] && ! (isset($_POST['mb_password']) && $_POST['mb_password'])){
        alert('비밀번호를 입력해 주세요.');
    }

    if (isset($_POST['mb_password'])) {
        // 수정된 정보를 업데이트후 되돌아 온것이라면 비밀번호가 암호화 된채로 넘어온것임
        if (isset($_POST['is_update']) && $_POST['is_update']) {
            $tmp_password = $_POST['mb_password'];
            $pass_check = ($member['mb_password'] === $tmp_password);
        } else {
            $pass_check = check_password($_POST['mb_password'], $member['mb_password']);
        }

        if (!$pass_check)
            alert('비밀번호가 틀립니다.');
    }

    $g5['title'] = '회원 정보 수정';

    set_session("ss_reg_mb_name", $member['mb_name']);
    set_session("ss_reg_mb_hp", $member['mb_hp']);

    $member['mb_email']       = get_text($member['mb_email']);
    $member['mb_birth']       = get_text($member['mb_birth']);
    $member['mb_hp']          = get_text($member['mb_hp']);
} else {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

$member_defaults = array(
    'mb_marketing_agree' => '0',
    'mb_marketing_date' => '0000-00-00 00:00:00',
    'mb_mailling' => '0',
    'mb_mailling_date' => '0000-00-00 00:00:00',
);

foreach ($member_defaults as $member_key => $member_default_value) {
    if (!isset($member[$member_key])) {
        $member[$member_key] = $member_default_value;
    }
}

$register_action_url = G5_HTTPS_MEMBER_URL.'/register_form_update.php';
$register_form_view = MemberRegisterFormViewDataFactory::build($w, $member, $config);
MemberPageController::render(
    $g5['title'],
    'register_form.skin.php',
    array_merge(
        array(
            'register_action_url' => $register_action_url,
            'w' => $w,
        ),
        $register_form_view
    ),
    array(
        'after_event' => 'register_form_after',
        'after_args' => array($w, $register_form_view['agree'], $register_form_view['agree2']),
    )
);
