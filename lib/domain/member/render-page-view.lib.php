<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_build_member_certify_client_config(array $config, $page_type)
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
        'show_certify_options' => ($config['cf_cert_use'] != 0 && $page_type !== 'find') || ($config['cf_cert_use'] != 0 && $config['cf_cert_find'] != 0),
        'show_simple_cert_button' => !empty($config['cf_cert_simple']),
        'show_hp_cert_button' => !empty($config['cf_cert_hp']),
        'simple_cert_url' => G5_INICERT_URL . '/ini_request.php',
        'hp_cert_url' => $hp_cert_url,
        'hp_cert_type' => $hp_cert_type,
        'hp_cert_error_message' => $hp_cert_error_message,
        'page_type' => $page_type,
    );
}

function member_build_password_lost_page_view(array $config)
{
    $certify_ui = member_build_member_certify_client_config($config, 'find');

    return array(
        'title' => '회원정보 찾기',
        'data' => array_merge(array(
            'action_url' => G5_HTTPS_MEMBER_URL . '/password_lost2.php',
            'email_description_text' => "회원가입 시 등록하신 이메일 주소를 입력해 주세요.\n해당 이메일로 아이디와 비밀번호 정보를 보내드립니다.",
            'email_submit_label' => '인증메일 보내기',
        ), $certify_ui),
    );
}

function member_build_login_render_page_view(array $login_view, $url)
{
    return array(
        'title' => '로그인',
        'data' => array(
            'page_title' => '로그인',
            'register_url' => G5_MEMBER_URL . '/register.php',
            'password_lost_url' => G5_MEMBER_URL . '/password_lost.php',
            'login_action_url' => $login_view['login_action_url'],
            'login_url' => $login_view['login_url'],
            'auto_login_confirm_message' => "자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?",
        ),
        'options' => array(
            'sub' => true,
            'view_path' => $login_view['view_path'],
            'after_event' => 'member_login_tail',
            'after_args' => array(
                $login_view['login_url'],
                $login_view['login_action_url'],
                $login_view['view_path'],
                $url,
            ),
        ),
    );
}

function member_build_password_reset_page_view(array $request)
{
    return array(
        'title' => '패스워드 변경',
        'data' => array(
            'action_url' => G5_HTTPS_MEMBER_URL . '/password_reset_update.php',
            'mb_id' => isset($request['mb_id']) ? $request['mb_id'] : '',
            'member_id_text' => isset($request['mb_id']) ? get_text($request['mb_id']) : '',
            'page_description_text' => '새로운 비밀번호를 입력해주세요.',
            'submit_label' => '확인',
            'password_reset_success_message' => '비밀번호 변경되었습니다. 다시 로그인해 주세요.',
            'password_reset_mismatch_message' => '새 비밀번호와 비밀번호 확인이 일치하지 않습니다.',
        ),
    );
}

function member_build_register_intro_page_view(array $config)
{
    $private_info_item_text = $config['cf_cert_use']
        ? '아이디, 이름, 비밀번호, 생년월일, 휴대폰 번호(본인확인 시), 암호화된 개인식별부호(CI)'
        : '아이디, 이름, 비밀번호';

    return array(
        'title' => '회원가입약관',
        'data' => array(
            'register_action_url' => G5_MEMBER_URL . '/register_form.php',
            'page_notice_text' => '회원가입약관 및 개인정보 수집 및 이용의 내용에 동의하셔야 회원가입 하실 수 있습니다.',
            'stipulation_text' => get_text($config['cf_stipulation']),
            'private_info_item_text' => $private_info_item_text,
            'cancel_url' => G5_URL,
            'agree_alert_message' => '회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.',
            'agree2_alert_message' => '개인정보 수집 및 이용의 내용에 동의하셔야 회원가입 하실 수 있습니다.',
        ),
    );
}

function member_build_register_email_page_view(array $mb, $mb_id)
{
    return array(
        'title' => '메일인증 메일주소 변경',
        'data' => array(
            'register_email_action_url' => G5_HTTPS_MEMBER_URL . '/register_email_update.php',
            'member_id' => $mb_id,
            'member_email' => isset($mb['mb_email']) ? $mb['mb_email'] : '',
            'cancel_url' => G5_URL,
            'submit_label' => '인증메일변경',
        ),
    );
}

function member_build_confirm_page_view($url, array $member)
{
    $is_leave_confirm = ($url === 'member_leave.php');

    return array(
        'title' => '회원 비밀번호 확인',
        'data' => array(
            'page_title' => '회원 비밀번호 확인',
            'confirm_action_url' => $url,
            'member_id' => isset($member['mb_id']) ? $member['mb_id'] : '',
            'confirm_description_text' => $is_leave_confirm
                ? '비밀번호를 입력하시면 회원탈퇴가 완료됩니다.'
                : '회원님의 정보를 안전하게 보호하기 위해 비밀번호를 한번 더 확인합니다.',
            'submit_label' => '확인',
        ),
        'options' => array(
            'sub' => true,
        ),
    );
}

function member_build_cert_refresh_page_view(array $request, array $member, array $config, $current_url_encoded = '')
{
    $certify_ui = member_build_member_certify_client_config($config, 'register');
    $privacy_fields_text = empty($member['mb_dupinfo'])
        ? '생년월일, 휴대폰 번호, 암호화된 개인식별부호(CI)'
        : '생년월일, 암호화된 개인식별부호(CI)';

    return array(
        'title' => '본인인증을 다시 해주세요.',
        'data' => array_merge(array(
            'action_url' => G5_HTTPS_MEMBER_URL . '/member_cert_refresh_update.php',
            'form_mode' => isset($request['w']) ? $request['w'] : '',
            'return_url_encoded' => $current_url_encoded,
            'cert_type_value' => isset($member['mb_certify']) ? $member['mb_certify'] : '',
            'member_id_value' => isset($member['mb_id']) ? $member['mb_id'] : '',
            'member_hp_value' => isset($member['mb_hp']) ? $member['mb_hp'] : '',
            'member_name_value' => isset($member['mb_name']) ? $member['mb_name'] : '',
            'privacy_fields_text' => $privacy_fields_text,
            'privacy_agree_required_message' => '추가 개인정보처리방침에 동의하셔야 인증을 진행하실 수 있습니다.',
        ), $certify_ui),
    );
}

function member_build_register_result_page_view(array $mb)
{
    $show_email_certify_notice = is_use_email_certify();

    return array(
        'title' => '회원가입 완료',
        'data' => array(
            'member_name' => isset($mb['mb_name']) ? get_text($mb['mb_name']) : '',
            'member_id' => isset($mb['mb_id']) ? $mb['mb_id'] : '',
            'member_email' => isset($mb['mb_email']) ? $mb['mb_email'] : '',
            'show_email_certify_notice' => $show_email_certify_notice,
            'home_url' => G5_URL . '/',
        ),
    );
}

function member_build_register_form_page_view(array $request, array $member, array $config, $current_url_encoded = '')
{
    $w = isset($request['w']) ? $request['w'] : '';

    if ($request['w'] === '') {
        $register_form_state = member_prepare_register_form_create_state($member, $request);
    } else {
        $register_form_state = member_prepare_register_form_update_state($member);
    }

    $member = member_apply_register_form_defaults($register_form_state['member']);
    $register_form_view = MemberRegisterFormViewDataFactory::build($request, $member, $config);

    return array(
        'title' => $register_form_state['title'],
        'data' => array_merge(
            array(
                'register_action_url' => G5_HTTPS_MEMBER_URL . '/register_form_update.php',
                'urlencode' => $current_url_encoded,
            ),
            $register_form_view
        ),
        'options' => array(
            'after_event' => 'register_form_after',
            'after_args' => array($w, $register_form_view['agree'], $register_form_view['agree2']),
        ),
    );
}
