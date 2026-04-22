<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_build_password_lost_page_view()
{
    return array(
        'title' => '회원정보 찾기',
        'data' => array(
            'action_url' => G5_HTTPS_MEMBER_URL . '/password_lost2.php',
        ),
    );
}

function member_build_login_render_page_view(array $login_view, $url)
{
    return array(
        'title' => '로그인',
        'data' => array(
            'login_action_url' => $login_view['login_action_url'],
            'login_url' => $login_view['login_url'],
        ),
        'options' => array(
            'sub' => true,
            'skin_path' => $login_view['skin_path'],
            'after_event' => 'member_login_tail',
            'after_args' => array(
                $login_view['login_url'],
                $login_view['login_action_url'],
                $login_view['skin_path'],
                $url,
            ),
        ),
    );
}

function member_build_password_reset_page_view()
{
    return array(
        'title' => '패스워드 변경',
        'data' => array(
            'action_url' => G5_HTTPS_MEMBER_URL . '/password_reset_update.php',
        ),
    );
}

function member_build_register_intro_page_view()
{
    return array(
        'title' => '회원가입약관',
        'data' => array(
            'register_action_url' => G5_MEMBER_URL . '/register_form.php',
        ),
    );
}

function member_build_register_email_page_view(array $mb, $mb_id)
{
    return array(
        'title' => '메일인증 메일주소 변경',
        'data' => array(
            'mb' => $mb,
            'mb_id' => $mb_id,
        ),
    );
}

function member_build_confirm_page_view($url)
{
    return array(
        'title' => '회원 비밀번호 확인',
        'data' => array(
            'url' => $url,
        ),
        'options' => array(
            'sub' => true,
        ),
    );
}

function member_build_cert_refresh_page_view()
{
    return array(
        'title' => '본인인증을 다시 해주세요.',
        'data' => array(
            'action_url' => G5_HTTPS_MEMBER_URL . '/member_cert_refresh_update.php',
        ),
    );
}

function member_build_register_result_page_view(array $mb)
{
    return array(
        'title' => '회원가입 완료',
        'data' => array(
            'mb' => $mb,
        ),
    );
}

function member_build_register_form_page_view($w, array $request, array $member, array $config)
{
    if ($request['w'] === '') {
        $register_form_state = member_prepare_register_form_create_state($member, $request);
    } else {
        $register_form_state = member_prepare_register_form_update_state($member);
    }

    $member = member_apply_register_form_defaults($register_form_state['member']);
    $register_form_view = MemberRegisterFormViewDataFactory::build($w, $member, $config);

    return array(
        'title' => $register_form_state['title'],
        'data' => array_merge(
            array(
                'register_action_url' => G5_HTTPS_MEMBER_URL . '/register_form_update.php',
                'w' => $w,
            ),
            $register_form_view
        ),
        'options' => array(
            'after_event' => 'register_form_after',
            'after_args' => array($w, $register_form_view['agree'], $register_form_view['agree2']),
        ),
    );
}
