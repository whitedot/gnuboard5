<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberNotificationService
{
    public static function sendPasswordLostMail($email, array $mb, $change_password, $mb_nonce, $mb_lost_certify)
    {
        global $config;

        $subject = '[' . $config['cf_title'] . '] 요청하신 회원정보 찾기 안내 메일입니다.';
        $href = G5_MEMBER_URL . '/password_lost_certify.php?mb_no=' . $mb['mb_no'] . '&amp;mb_nonce=' . $mb_nonce;
        $content = MemberMailRenderer::capture('password_lost.mail.php', array(
            'change_password' => $change_password,
            'href' => $href,
            'mb' => $mb,
        ));

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb['mb_email'], $subject, $content, 1);
        run_event('password_lost2_after', $mb, $mb_nonce, $mb_lost_certify);
    }

    public static function sendRegisterEmailCertify($mb_id, $mb_name, $mb_email)
    {
        MemberRegisterNotificationService::sendRegisterEmailCertify($mb_id, $mb_name, $mb_email);
    }

    public static function sendRegisterEmailChange($mb_id, $mb_name, $mb_email, $w = 'u')
    {
        MemberRegisterNotificationService::sendRegisterEmailChange($mb_id, $mb_name, $mb_email, $w);
    }
}

function member_issue_password_lost_mail($email, array $mb)
{
    $change_password = rand(100000, 999999);
    $mb_lost_certify = get_encrypt_string($change_password);
    $mb_nonce = md5(pack('V*', rand(), rand(), rand(), rand()));

    member_store_password_lost_certify($mb['mb_id'], $mb_nonce . ' ' . $mb_lost_certify);
    MemberNotificationService::sendPasswordLostMail($email, $mb, $change_password, $mb_nonce, $mb_lost_certify);
}

function member_complete_password_lost_request(array $request, $is_member)
{
    member_validate_password_lost_submit_access($is_member);
    member_validate_password_lost_request($request);

    $email = $request['mb_email'];
    $count = member_count_by_email($email);
    member_validate_password_lost_candidate_count($count);

    $mb = member_find_password_lost_candidate($email);
    member_validate_password_lost_member($mb);

    member_issue_password_lost_mail($email, $mb);

    alert_close($email . ' 메일로 회원아이디와 비밀번호를 인증할 수 있는 메일이 발송 되었습니다.\\n\\n메일을 확인하여 주십시오.');
}

function member_finish_password_reset(array $request)
{
    member_update_password_by_dupinfo(
        $request['mb_id'],
        $request['mb_dupinfo'],
        get_encrypt_string($request['mb_password'])
    );

    set_session('ss_cert_mb_id', '');
    set_session('ss_cert_dupinfo', '');
}

function member_complete_password_reset_request(array $request)
{
    member_validate_password_reset_request($request);

    $mb_check = member_find_by_id_and_dupinfo($request['mb_id'], $request['mb_dupinfo']);
    member_validate_password_reset_member($mb_check);

    member_finish_password_reset($request);

    goto_url(G5_MEMBER_URL . '/login.php');
}

function member_begin_login_session(array $mb, $member_skin_path)
{
    run_event('login_session_before', $mb, false);

    MemberSkinHookController::includeOptional($member_skin_path, 'login_check.skin.php');

    if (!(defined('SKIP_SESSION_REGENERATE_ID') && SKIP_SESSION_REGENERATE_ID)) {
        session_regenerate_id(false);
        if (function_exists('session_start_samesite')) {
            session_start_samesite();
        }
    }

    set_session('ss_mb_id', $mb['mb_id']);
    generate_mb_key($mb);

    if (function_exists('update_auth_session_token')) {
        update_auth_session_token($mb['mb_datetime']);
    }
}

function member_apply_login_auto_cookie(array $mb, $use_auto_login)
{
    if ($use_auto_login) {
        $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password']);
        set_cookie('ck_mb_id', $mb['mb_id'], 86400 * 31);
        set_cookie('ck_auto', $key, 86400 * 31);
        return;
    }

    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);
}

function member_build_login_redirect_url($url, array $post)
{
    if (!$url) {
        return G5_URL;
    }

    check_url_host($url, '', G5_URL, true);

    $link = urldecode($url);
    $split = preg_match("/\?/", $link) ? '&amp;' : '?';
    $post_check_keys = array('mb_id', 'mb_password', 'x', 'y', 'url');
    $post_check_keys = run_replace('login_check_post_check_keys', $post_check_keys, $link, false);

    foreach ($post as $key => $value) {
        if ($key && !in_array($key, $post_check_keys)) {
            $link .= $split . $key . '=' . $value;
            $split = '&amp;';
        }
    }

    return $link;
}

function member_validate_admin_login_environment(array $mb, $link)
{
    if (!is_admin($mb['mb_id']) || !is_dir(G5_DATA_PATH . '/tmp/')) {
        return;
    }

    $tmp_data_file = G5_DATA_PATH . '/tmp/tmp-write-test-' . time();
    $tmp_data_check = @fopen($tmp_data_file, 'w');
    if ($tmp_data_check && !@fwrite($tmp_data_check, G5_URL)) {
        $tmp_data_check = false;
    }
    if (is_resource($tmp_data_check)) {
        @fclose($tmp_data_check);
    }
    @unlink($tmp_data_file);

    if (!$tmp_data_check) {
        alert("data 폴더에 쓰기권한이 없거나 또는 웹하드 용량이 없는 경우\\n로그인을 못할수도 있으니, 용량 체크 및 쓰기 권한을 확인해 주세요.", $link);
    }
}

function member_redirect_if_logged_in($is_member, $url = '')
{
    if (!$is_member) {
        return;
    }

    goto_url($url ? $url : G5_URL);
}

function member_build_login_page_view($member_skin_path, $url)
{
    $login_skin_path = $member_skin_path;
    $login_file = $login_skin_path . '/login.skin.php';

    if (!file_exists($login_file)) {
        $login_skin_path = G5_SKIN_PATH . '/member/basic';
    }

    return array(
        'login_url' => login_url($url),
        'login_action_url' => G5_HTTPS_MEMBER_URL . '/login_check.php',
        'skin_path' => $login_skin_path,
    );
}

function member_process_password_lost_certify(array $request)
{
    run_event('password_lost_certify_before');

    $mb = member_find_password_lost_certify_row($request['mb_no']);
    member_validate_password_lost_certify_row($mb);
    member_validate_password_lost_certify_nonce($request, $mb);

    $new_password_hash = substr($mb['mb_lost_certify'], 33);
    member_confirm_password_lost_certify($request['mb_no'], $mb['mb_lost_certify'], $new_password_hash);

    run_event('password_lost_certify_after', $mb, $request['mb_nonce']);
}

function member_complete_password_lost_certify_request(array $request)
{
    member_guard_mail_link_bot();
    member_process_password_lost_certify($request);

    alert('비밀번호가 변경됐습니다.\\n\\n회원아이디와 변경된 비밀번호로 로그인 하시기 바랍니다.', G5_MEMBER_URL . '/login.php');
}

function member_complete_login(array $request, array $mb, $member_skin_path, array $post)
{
    $link = member_build_login_redirect_url($request['url'], $post);

    member_begin_login_session($mb, $member_skin_path);
    member_apply_login_auto_cookie($mb, $request['auto_login']);

    run_event('member_login_check', $mb, $link, false);
    member_validate_admin_login_environment($mb, $link);

    goto_url($link);
}

function member_complete_login_request(array $request, $member_skin_path, array $post)
{
    member_validate_login_request($request);

    $mb = member_find_login_member($request['mb_id']);
    member_validate_login_credentials($request, $mb);
    member_validate_login_status($mb);
    member_validate_login_email_certify($mb, $request['mb_id']);

    member_complete_login($request, $mb, $member_skin_path, $post);
}
