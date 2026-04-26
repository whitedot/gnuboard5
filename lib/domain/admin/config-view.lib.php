<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_require_super_admin($is_admin)
{
    if ($is_admin != 'super') {
        alert('최고관리자만 접근 가능합니다.');
    }
}

function admin_read_config_row()
{
    global $g5;

    return sql_fetch_prepared(" select * from {$g5['config_table']} limit 1", array());
}

function admin_config_form_tabs()
{
    return array(
        array('id' => 'anc_cf_basic', 'label' => '기본'),
        array('id' => 'anc_cf_join', 'label' => '회원'),
        array('id' => 'anc_cf_cert', 'label' => '본인확인'),
        array('id' => 'anc_cf_mail', 'label' => '메일'),
    );
}

function admin_config_value(array $config, $key, $default = '')
{
    return isset($config[$key]) ? $config[$key] : $default;
}

function admin_build_config_captcha_mp3_options($selected)
{
    $options = array();
    $captcha_mp3_path = str_replace(array('recaptcha_inv', 'recaptcha'), 'kcaptcha', G5_CAPTCHA_PATH) . '/mp3';
    $directories = get_subdirectory_names($captcha_mp3_path);

    $options[] = array(
        'value' => '',
        'label' => '선택',
        'selected' => (string) $selected === '',
    );

    for ($i = 0; $i < count($directories); $i++) {
        $value = (string) $directories[$i];
        $options[] = array(
            'value' => $value,
            'label' => $value,
            'selected' => (string) $selected === $value,
        );
    }

    return $options;
}

function admin_build_config_basic_view(array $config)
{
    $admin_email = (string) admin_config_value($config, 'cf_admin_email', '');
    $domain_mail_host = function_exists('domain_mail_host') ? domain_mail_host() : '';

    return array(
        'cf_title' => get_sanitize_input(admin_config_value($config, 'cf_title', '')),
        'cf_admin_email' => get_sanitize_input($admin_email),
        'cf_admin_email_name' => get_sanitize_input(admin_config_value($config, 'cf_admin_email_name', '')),
        'cf_cut_name' => (int) admin_config_value($config, 'cf_cut_name', 0),
        'cf_nick_modify' => (int) admin_config_value($config, 'cf_nick_modify', 0),
        'cf_open_modify' => (int) admin_config_value($config, 'cf_open_modify', 0),
        'cf_page_rows' => (int) admin_config_value($config, 'cf_page_rows', 0),
        'cf_captcha' => (string) admin_config_value($config, 'cf_captcha', ''),
        'cf_captcha_mp3_hint' => str_replace(array('recaptcha_inv', 'recaptcha'), 'kcaptcha', G5_CAPTCHA_URL) . '/mp3',
        'cf_captcha_mp3_options' => admin_build_config_captcha_mp3_options(admin_config_value($config, 'cf_captcha_mp3', '')),
        'cf_recaptcha_site_key' => get_sanitize_input(admin_config_value($config, 'cf_recaptcha_site_key', '')),
        'cf_recaptcha_secret_key' => get_sanitize_input(admin_config_value($config, 'cf_recaptcha_secret_key', '')),
        'cf_possible_ip' => get_sanitize_input(admin_config_value($config, 'cf_possible_ip', '')),
        'cf_intercept_ip' => get_sanitize_input(admin_config_value($config, 'cf_intercept_ip', '')),
        'admin_member_options' => admin_read_member_id_options(10, admin_config_value($config, 'cf_admin', '')),
        'show_admin_email_domain_warning' => ($domain_mail_host && $admin_email && stripos($admin_email, $domain_mail_host) === false),
        'domain_mail_host' => $domain_mail_host,
    );
}

function admin_build_config_join_view(array $config)
{
    return array(
        'cf_use_hp_checked' => admin_config_value($config, 'cf_use_hp', 0) ? ' checked' : '',
        'cf_req_hp_checked' => admin_config_value($config, 'cf_req_hp', 0) ? ' checked' : '',
        'cf_register_level_options' => admin_build_member_level_options(1, 9, admin_config_value($config, 'cf_register_level', '')),
        'cf_leave_day' => (int) admin_config_value($config, 'cf_leave_day', 0),
        'cf_prohibit_id' => get_sanitize_input(admin_config_value($config, 'cf_prohibit_id', '')),
        'cf_prohibit_email' => get_sanitize_input(admin_config_value($config, 'cf_prohibit_email', '')),
        'cf_stipulation' => html_purifier(admin_config_value($config, 'cf_stipulation', '')),
        'cf_privacy' => html_purifier(admin_config_value($config, 'cf_privacy', '')),
        'cf_use_promotion_checked' => admin_config_value($config, 'cf_use_promotion', 0) ? ' checked' : '',
    );
}

function admin_build_config_mail_view(array $config)
{
    return array(
        'cf_email_use_checked' => admin_config_value($config, 'cf_email_use', 0) ? ' checked' : '',
        'cf_use_email_certify_checked' => admin_config_value($config, 'cf_use_email_certify', 0) ? ' checked' : '',
    );
}

function admin_build_config_select_options($selected, array $labels)
{
    $options = array();

    foreach ($labels as $value => $label) {
        $options[] = array(
            'value' => (string) $value,
            'label' => $label,
            'selected' => (string) $selected === (string) $value,
        );
    }

    return $options;
}

function admin_build_config_cert_module_check_html(array $config)
{
    if (!admin_config_value($config, 'cf_cert_use', 0) || admin_config_value($config, 'cf_cert_hp', '') !== 'kcp') {
        return '';
    }

    $bin_path = ((int) admin_config_value($config, 'cf_cert_use', 0) === 2 && !admin_config_value($config, 'cf_cert_kcp_enckey', '')) ? 'bin_old' : 'bin';

    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        $exe = PHP_INT_MAX == 2147483647
            ? G5_KCPCERT_PATH . '/' . $bin_path . '/ct_cli'
            : G5_KCPCERT_PATH . '/' . $bin_path . '/ct_cli_x64';
    } else {
        $exe = G5_KCPCERT_PATH . '/' . $bin_path . '/ct_cli_exe.exe';
    }

    return module_exec_check($exe, 'ct_cli');
}

function admin_build_config_cert_view(array $config)
{
    return array(
        'cf_cert_use_options' => admin_build_config_select_options(admin_config_value($config, 'cf_cert_use', 0), array(
            '0' => '사용안함',
            '1' => '테스트',
            '2' => '실서비스',
        )),
        'cf_cert_find_checked' => (int) admin_config_value($config, 'cf_cert_find', 0) === 1 ? ' checked' : '',
        'cf_cert_simple_options' => admin_build_config_select_options(admin_config_value($config, 'cf_cert_simple', ''), array(
            '' => '사용안함',
            'inicis' => 'KG이니시스 통합인증(간편인증)',
        )),
        'cf_cert_use_seed_options' => admin_build_config_select_options(admin_config_value($config, 'cf_cert_use_seed', 0), array(
            '0' => '사용안함',
            '1' => '사용함',
        )),
        'cf_cert_hp_options' => admin_build_config_select_options(admin_config_value($config, 'cf_cert_hp', ''), array(
            '' => '사용안함',
            'kcp' => 'NHN KCP 휴대폰 본인확인',
        )),
        'cf_cert_kg_mid' => get_sanitize_input(admin_config_value($config, 'cf_cert_kg_mid', '')),
        'cf_cert_kg_cd' => get_sanitize_input(admin_config_value($config, 'cf_cert_kg_cd', '')),
        'cf_cert_kcp_cd' => get_sanitize_input(admin_config_value($config, 'cf_cert_kcp_cd', '')),
        'cf_cert_kcp_enckey' => get_sanitize_input(admin_config_value($config, 'cf_cert_kcp_enckey', '')),
        'cf_cert_limit' => (int) admin_config_value($config, 'cf_cert_limit', 0),
        'cf_cert_req_checked' => get_checked(admin_config_value($config, 'cf_cert_req', 0), 1),
        'cert_module_check_html' => admin_build_config_cert_module_check_html($config),
    );
}

function admin_build_config_form_view(array $config, $remote_addr = '')
{
    return array(
        'title' => '환경설정',
        'admin_container_class' => 'admin-page-config-form',
        'admin_page_subtitle' => '기본, 회원, 보안, 연동 설정을 탭에서 빠르게 관리하세요.',
        'current_user_ip' => get_text((string) $remote_addr),
        'webp_warning' => (stripos((string) admin_config_value($config, 'cf_image_extension', ''), 'webp') !== false && !function_exists('imagewebp')) ? '1' : '0',
        'basic_view' => admin_build_config_basic_view($config),
        'join_view' => admin_build_config_join_view($config),
        'cert_view' => admin_build_config_cert_view($config),
        'mail_view' => admin_build_config_mail_view($config),
        'pg_anchor_menu_view' => admin_build_anchor_menu_view(admin_config_form_tabs(), array(
            'nav_id' => 'config_tabs_nav',
            'nav_class' => 'tab-nav-justified',
            'nav_aria_label' => '환경설정 탭',
            'link_class' => 'tab-trigger-underline-justified js-config-tab-link',
            'active_class' => 'active',
            'as_tabs' => true,
            'link_id_prefix' => 'config_tab_',
        )),
    );
}

function admin_build_config_form_page_view(array $config, $remote_addr = '')
{
    return admin_build_config_form_view($config, $remote_addr);
}
