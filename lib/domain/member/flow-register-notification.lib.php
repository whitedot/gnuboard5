<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberRegisterNotificationService
{
    public static function sendRegisterEmailCertify($mb_id, $mb_name, $mb_email)
    {
        $config = member_get_runtime_config();
        $member_table = member_get_member_table_name();

        $subject = '[' . $config['cf_title'] . '] 인증확인 메일입니다.';
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
        sql_query_prepared(" update {$member_table} set mb_email_certify2 = :mb_email_certify2 where mb_id = :mb_id ", array(
            'mb_email_certify2' => $mb_md5,
            'mb_id' => $mb_id,
        ));

        $certify_href = G5_MEMBER_URL . '/email_certify.php?mb_id=' . $mb_id . '&amp;mb_md5=' . $mb_md5;
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
        $config = member_get_runtime_config();
        $member_table = member_get_member_table_name();

        $subject = '[' . $config['cf_title'] . '] 인증확인 메일입니다.';
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
        sql_query_prepared(" update {$member_table} set mb_email_certify2 = :mb_email_certify2 where mb_id = :mb_id ", array(
            'mb_email_certify2' => $mb_md5,
            'mb_id' => $mb_id,
        ));

        $certify_href = G5_MEMBER_URL . '/email_certify.php?mb_id=' . $mb_id . '&amp;mb_md5=' . $mb_md5;
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
        $config = member_get_runtime_config();
        $member_table = member_get_member_table_name();

        if ($msg) {
            MemberResponseRenderer::alertScript($msg);
        }

        run_event('register_form_update_after', $mb_id, $w);

        if ($w == '') {
            goto_url(G5_HTTP_MEMBER_URL . '/register_result.php');
        }

        if ($w != 'u') {
            return;
        }

        $tmp_password = sql_fetch_value_prepared(" select mb_password from {$member_table} where mb_id = :mb_id ", array(
            'mb_id' => $member['mb_id'],
        ));

        if ($old_email != $mb_email && $config['cf_use_email_certify']) {
            set_session('ss_mb_id', '');
            alert('회원 정보가 수정 되었습니다.\n\nE-mail 주소가 변경되었으므로 다시 인증하셔야 합니다.', G5_URL);
        }

        MemberResponseRenderer::autoPost(
            G5_HTTP_MEMBER_URL . '/register_form.php',
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
