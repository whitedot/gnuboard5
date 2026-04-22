<?php
if (!defined('_GNUBOARD_')) {
    exit;
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
