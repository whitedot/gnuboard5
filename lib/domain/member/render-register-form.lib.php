<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberRegisterFormViewDataFactory
{
    protected static function buildCertifyConfig(array $config)
    {
        $hp_cert_url = '';
        $hp_cert_type = '';
        $hp_cert_error_message = '';

        if (!empty($config['cf_cert_hp'])) {
            switch ($config['cf_cert_hp']) {
                case 'kcp':
                    $hp_cert_url = G5_KCPCERT_URL . '/kcpcert_form.php';
                    $hp_cert_type = 'kcp-hp';
                    break;
                default:
                    $hp_cert_error_message = '기본환경설정에서 휴대폰 본인확인 설정을 해주십시오';
                    break;
            }
        }

        return array(
            'use_certify_js' => ($config['cf_cert_use'] && ($config['cf_cert_simple'] || $config['cf_cert_hp'])),
            'certify_script_url' => G5_JS_URL . '/certify.js?v=' . G5_JS_VER,
            'show_certify_section' => !empty($config['cf_cert_use']),
            'show_simple_cert_button' => !empty($config['cf_cert_simple']),
            'show_hp_cert_button' => !empty($config['cf_cert_hp']),
            'simple_cert_url' => G5_INICERT_URL . '/ini_request.php',
            'hp_cert_url' => $hp_cert_url,
            'hp_cert_type' => $hp_cert_type,
            'hp_cert_error_message' => $hp_cert_error_message,
        );
    }

    public static function build(array $request, array $member, array $config)
    {
        $w = isset($request['w']) ? (string) $request['w'] : '';
        $req_nick = !isset($member['mb_nick_date'])
            || (isset($member['mb_nick_date']) && $member['mb_nick_date'] <= date('Y-m-d', G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400)));
        $is_create = ($w === '');
        $is_update = ($w === 'u');
        $email_certify_help_text = '';
        $certify_config = self::buildCertifyConfig($config);
        $show_hp_field = ($config['cf_use_hp'] || ($config['cf_cert_use'] && ($config['cf_cert_hp'] || $config['cf_cert_simple'])));
        $show_old_hp_field = ($config['cf_cert_use'] && ($config['cf_cert_hp'] || $config['cf_cert_simple']));
        $show_open_checkbox = (!isset($member['mb_open_date']) || empty($member['mb_open_date']) || $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)));
        $open_locked_until_text = date(
            "Y년 m월 j일",
            isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00") + $config['cf_open_modify'] * 86400 : G5_SERVER_TIME + $config['cf_open_modify'] * 86400
        );
        $certify_status_text = '';

        if (!empty($member['mb_certify'])) {
            $certify_label = '';
            switch ($member['mb_certify']) {
                case 'simple':
                    $certify_label = '간편인증';
                    break;
                case 'hp':
                    $certify_label = '휴대폰';
                    break;
            }

            $certify_status_text = ($certify_label !== '' ? $certify_label . ' 본인확인' : '본인확인')
                . (!empty($member['mb_adult']) ? ' 및 성인인증' : '')
                . ' 완료';
        }

        if ($config['cf_use_email_certify']) {
            if ($is_create) {
                $email_certify_help_text = 'E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다.';
            } elseif ($is_update) {
                $email_certify_help_text = 'E-mail 주소를 변경하시면 다시 인증하셔야 합니다.';
            }
        }

        return array(
            'form_mode' => $w,
            'is_create' => $is_create,
            'is_update' => $is_update,
            'cert_type_value' => isset($member['mb_certify']) ? $member['mb_certify'] : '',
            'member_id_value' => isset($member['mb_id']) ? $member['mb_id'] : '',
            'member_name_value' => isset($member['mb_name']) ? get_text($member['mb_name']) : '',
            'member_nick_value' => isset($member['mb_nick']) ? get_text($member['mb_nick']) : '',
            'member_email_value' => isset($member['mb_email']) ? $member['mb_email'] : '',
            'member_hp_value' => isset($member['mb_hp']) ? get_text($member['mb_hp']) : '',
            'member_open_value' => isset($member['mb_open']) ? $member['mb_open'] : '',
            'member_marketing_agree_value' => isset($member['mb_marketing_agree']) ? $member['mb_marketing_agree'] : '0',
            'member_mailling_value' => isset($member['mb_mailling']) ? $member['mb_mailling'] : '0',
            'req_nick' => $req_nick,
            'required' => $is_create ? 'required' : '',
            'readonly' => $is_update ? 'readonly' : '',
            'name_readonly' => ($is_update || ($config['cf_cert_use'] && $config['cf_cert_req'])) ? 'readonly' : '',
            'hp_required' => ($config['cf_req_hp'] || (($config['cf_cert_use'] && $config['cf_cert_req']) && ($config['cf_cert_hp'] || $config['cf_cert_simple']))) ? 'required' : '',
            'hp_readonly' => (($config['cf_cert_use'] && $config['cf_cert_req']) && ($config['cf_cert_hp'] || $config['cf_cert_simple'])) ? 'readonly' : '',
            'show_hp_field' => $show_hp_field,
            'show_old_hp_field' => $show_old_hp_field,
            'agree' => isset($request['agree']) ? (string) $request['agree'] : '',
            'agree2' => isset($request['agree2']) ? (string) $request['agree2'] : '',
            'email_certify_help_text' => $email_certify_help_text,
            'show_email_certify_help' => !empty($config['cf_use_email_certify']),
            'desc_name_text' => !empty($config['cf_cert_use']) ? ' 본인확인 시 자동입력' : '',
            'desc_phone_text' => (!empty($config['cf_cert_use']) && ($config['cf_cert_simple'] || $config['cf_cert_hp'])) ? ' 본인확인 시 자동입력' : '',
            'show_certify_required_text' => !empty($config['cf_cert_use']),
            'show_certify_status' => ($certify_status_text !== ''),
            'certify_status_text' => $certify_status_text,
            'nick_tooltip_text' => '공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)<br> 닉네임을 바꾸시면 앞으로 ' . (int) $config['cf_nick_modify'] . '일 이내에는 변경 할 수 없습니다.',
            'show_locked_nick_hidden_fields' => (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))),
            'locked_nick_default_value' => isset($member['mb_nick']) ? get_text($member['mb_nick']) : '',
            'locked_nick_value' => isset($member['mb_nick']) ? get_text($member['mb_nick']) : '',
            'mb_open_checked' => ($is_create || !empty($member['mb_open'])) ? 'checked' : '',
            'show_open_checkbox' => $show_open_checkbox,
            'open_modify_tooltip_text' => '정보공개를 바꾸시면 앞으로 ' . (int) $config['cf_open_modify'] . '일 이내에는 변경이 안됩니다.',
            'open_locked_tooltip_text' => '정보공개는 수정후 ' . (int) $config['cf_open_modify'] . '일 이내, ' . $open_locked_until_text . ' 까지는 변경이 안됩니다.<br>공개 범위가 자주 바뀌면 운영 정책과 사용자 기대가 어긋날 수 있어 일정 기간 동안 변경을 제한합니다.',
            'show_promotion_section' => ((int) $config['cf_use_promotion'] === 1),
            'marketing_agree_checked' => !empty($member['mb_marketing_agree']) ? 'checked' : '',
            'marketing_agree_date_text' => (!empty($member['mb_marketing_agree']) && isset($member['mb_marketing_date']) && $member['mb_marketing_date'] != "0000-00-00 00:00:00")
                ? '(동의일자: ' . $member['mb_marketing_date'] . ')'
                : '',
            'marketing_template_phone_text' => $show_hp_field ? ', 휴대폰 번호' : '',
            'mailling_checked' => !empty($member['mb_mailling']) ? 'checked' : '',
            'mailling_agree_date_text' => ($is_update && isset($member['mb_mailling']) && $member['mb_mailling'] == 1 && isset($member['mb_mailling_date']) && $member['mb_mailling_date'] != "0000-00-00 00:00:00")
                ? ' (동의일자: ' . $member['mb_mailling_date'] . ')'
                : '',
            'submit_label' => $is_create ? '회원가입' : '정보수정',
            'show_leave_link' => $is_update,
            'cancel_url' => G5_URL,
            'leave_url' => G5_MEMBER_URL . '/member_confirm.php?url=member_leave.php',
            'require_certification_on_submit' => ($is_create && $config['cf_cert_use'] && $config['cf_cert_req']),
            'require_hp_validation_on_submit' => (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']),
            'show_hidden_mb_sex' => isset($member['mb_sex']),
            'hidden_mb_sex_value' => isset($member['mb_sex']) ? $member['mb_sex'] : '',
            'register_page_type' => 'register',
            'certify_prompt_message' => '회원가입을 위해서는 본인확인을 해주셔야 합니다.',
            'password_length_message' => '비밀번호를 3글자 이상 입력하십시오.',
            'password_mismatch_message' => '비밀번호가 같지 않습니다.',
            'name_required_message' => '이름을 입력하십시오.',
            'consent_marketing_description' => '마케팅 목적의 개인정보 수집·이용에 대한 안내입니다. 자세히보기를 눌러 전문을 확인할 수 있습니다.',
            'consent_promotion_description' => '광고성 정보(이메일) 수신 동의의 상위 항목입니다. 자세히보기를 눌러 전문을 확인할 수 있습니다.',
            'register_form_script_url' => G5_JS_URL . '/register_form.js',
        ) + $certify_config + array(
        );
    }
}
