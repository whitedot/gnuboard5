<?php
if (!defined('_GNUBOARD_')) exit;

class MemberNotificationService
{
    public static function sendPasswordLostMail($email, array $mb, $change_password, $mb_nonce, $mb_lost_certify)
    {
        global $config;

        $subject = '['.$config['cf_title'].'] 요청하신 회원정보 찾기 안내 메일입니다.';
        $href = G5_MEMBER_URL.'/password_lost_certify.php?mb_no='.$mb['mb_no'].'&amp;mb_nonce='.$mb_nonce;
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
        global $config;

        $subject = '['.$config['cf_title'].'] 인증확인 메일입니다.';
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
        sql_query_prepared(" update {$GLOBALS['g5']['member_table']} set mb_email_certify2 = :mb_email_certify2 where mb_id = :mb_id ", array(
            'mb_email_certify2' => $mb_md5,
            'mb_id' => $mb_id,
        ));

        $certify_href = G5_MEMBER_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;
        $content = MemberMailRenderer::capture('register_email_certify.mail.php', array(
            'certify_href' => $certify_href,
            'mb_name' => $mb_name,
        ));
        $content = run_replace('register_form_update_mail_mb_content', $content, $mb_id);

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
        run_event('register_form_update_send_mb_mail', $config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content);
    }

    public static function sendRegisterEmailChange($mb_id, $mb_name, $mb_email, $w = 'u')
    {
        global $config;

        $subject = '['.$config['cf_title'].'] 인증확인 메일입니다.';
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
        sql_query_prepared(" update {$GLOBALS['g5']['member_table']} set mb_email_certify2 = :mb_email_certify2 where mb_id = :mb_id ", array(
            'mb_email_certify2' => $mb_md5,
            'mb_id' => $mb_id,
        ));

        $certify_href = G5_MEMBER_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;
        $content = MemberMailRenderer::capture('register_email_change.mail.php', array(
            'certify_href' => $certify_href,
            'mb_name' => $mb_name,
            'w' => $w,
        ));
        $content = run_replace('register_form_update_mail_certify_content', $content, $mb_id);

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
        run_event('register_form_update_send_certify_mail', $config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content);
    }
}

class MemberRegisterResponseFlow
{
    public static function finishSubmit($mb_id, array $member, $w, $old_email, $mb_email, $msg = '')
    {
        global $config;

        if ($msg) {
            MemberResponseRenderer::alertScript($msg);
        }

        run_event('register_form_update_after', $mb_id, $w);

        if ($w == '') {
            goto_url(G5_HTTP_MEMBER_URL.'/register_result.php');
        }

        if ($w != 'u') {
            return;
        }

        $tmp_password = sql_fetch_value_prepared(" select mb_password from {$GLOBALS['g5']['member_table']} where mb_id = :mb_id ", array(
            'mb_id' => $member['mb_id'],
        ));

        if ($old_email != $mb_email && $config['cf_use_email_certify']) {
            set_session('ss_mb_id', '');
            alert('회원 정보가 수정 되었습니다.\n\nE-mail 주소가 변경되었으므로 다시 인증하셔야 합니다.', G5_URL);
        }

        MemberResponseRenderer::autoPost(
            G5_HTTP_MEMBER_URL.'/register_form.php',
            array(
                'w' => 'u',
                'mb_id' => $mb_id,
                'mb_password' => $tmp_password,
                'is_update' => '1',
            ),
            '회원 정보가 수정 되었습니다.',
            '회원정보수정'
        );
    }
}

function member_certify_hash_matches($cert_type, $mb_name, $mb_hp, $md5_cert_no)
{
    if (!$cert_type || !$md5_cert_no) {
        return false;
    }

    $cert_birth = get_session('ss_cert_birth');
    $cert_hash = get_session('ss_cert_hash');

    if ($cert_type === 'ipin') {
        return $cert_hash === md5($mb_name . $cert_type . $cert_birth . $md5_cert_no);
    }

    return $cert_hash === md5($mb_name . $cert_type . $cert_birth . $mb_hp . $md5_cert_no);
}

function build_member_certify_fields($w, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    global $config;

    $sql_certify = array();

    if ($config['cf_cert_use'] && $cert_type && $md5_cert_no) {
        if (!member_certify_hash_matches($cert_type, $mb_name, $mb_hp, $md5_cert_no)) {
            alert('본인인증된 정보와 입력된 회원정보가 일치하지않습니다. 다시시도 해주세요');
        }

        $sql_certify['mb_hp'] = $mb_hp;
        $sql_certify['mb_certify'] = $cert_type;
        $sql_certify['mb_adult'] = get_session('ss_cert_adult');
        $sql_certify['mb_birth'] = get_session('ss_cert_birth');
        $sql_certify['mb_sex'] = get_session('ss_cert_sex');
        $sql_certify['mb_dupinfo'] = get_session('ss_cert_dupinfo');

        if ($w == 'u') {
            $sql_certify['mb_name'] = $mb_name;
        }

        return $sql_certify;
    }

    if (get_session('ss_reg_mb_name') != $mb_name || get_session('ss_reg_mb_hp') != $mb_hp) {
        $sql_certify['mb_hp'] = $mb_hp;
        $sql_certify['mb_certify'] = '';
        $sql_certify['mb_adult'] = 0;
        $sql_certify['mb_birth'] = '';
        $sql_certify['mb_sex'] = '';
    }

    return $sql_certify;
}
