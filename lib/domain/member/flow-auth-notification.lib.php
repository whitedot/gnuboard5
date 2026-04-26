<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberNotificationService
{
    public static function sendPasswordLostMail($email, array $mb, $change_password, $mb_nonce, $mb_lost_certify)
    {
        $config = member_get_runtime_config();

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
