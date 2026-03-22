<?php
if (!defined('_GNUBOARD_')) exit;

class MemberTemplateRenderer
{
    public static function capture($template_path, array $data = array())
    {
        global $g5, $config, $member, $is_member, $is_admin;

        if (!is_file($template_path)) {
            return '';
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $template_path;
        return ob_get_clean();
    }

    public static function display($template_path, array $data = array())
    {
        echo self::capture($template_path, $data);
    }
}

class MemberSkinRenderer
{
    public static function capture($skin_path, $template_name, array $data = array())
    {
        return MemberTemplateRenderer::capture(rtrim($skin_path, '/\\').'/'.$template_name, $data);
    }

    public static function display($skin_path, $template_name, array $data = array())
    {
        echo self::capture($skin_path, $template_name, $data);
    }
}

class MemberMailRenderer
{
    public static function capture($template_name, array $data = array())
    {
        return MemberTemplateRenderer::capture(G5_SKIN_PATH.'/member/mail/'.$template_name, $data);
    }
}

class MemberResponseRenderer
{
    public static function alertScript($message)
    {
        if ($message === null || $message === '') {
            return;
        }

        echo '<script>alert('.json_encode((string) $message).');</script>';
    }

    public static function autoPost($action, array $fields, $message = '', $title = '처리중')
    {
        echo MemberTemplateRenderer::capture(
            G5_SKIN_PATH.'/member/basic/auto_post_form.skin.php',
            array(
                'action' => $action,
                'fields' => $fields,
                'message' => $message,
                'title' => $title,
            )
        );
        exit;
    }
}

class MemberRegisterFormViewDataFactory
{
    public static function build($w, array $member, array $config)
    {
        $req_nick = !isset($member['mb_nick_date'])
            || (isset($member['mb_nick_date']) && $member['mb_nick_date'] <= date('Y-m-d', G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400)));

        return array(
            'req_nick' => $req_nick,
            'required' => ($w == '') ? 'required' : '',
            'readonly' => ($w == 'u') ? 'readonly' : '',
            'name_readonly' => ($w == 'u' || ($config['cf_cert_use'] && $config['cf_cert_req'])) ? 'readonly' : '',
            'hp_required' => ($config['cf_req_hp'] || (($config['cf_cert_use'] && $config['cf_cert_req']) && ($config['cf_cert_hp'] || $config['cf_cert_simple']) && $member['mb_certify'] != 'ipin')) ? 'required' : '',
            'hp_readonly' => (($config['cf_cert_use'] && $config['cf_cert_req']) && ($config['cf_cert_hp'] || $config['cf_cert_simple']) && $member['mb_certify'] != 'ipin') ? 'readonly' : '',
            'agree' => isset($_REQUEST['agree']) ? preg_replace('#[^0-9]#', '', $_REQUEST['agree']) : '',
            'agree2' => isset($_REQUEST['agree2']) ? preg_replace('#[^0-9]#', '', $_REQUEST['agree2']) : '',
        );
    }
}
