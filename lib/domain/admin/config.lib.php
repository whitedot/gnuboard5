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

function admin_read_config_form_update_request(array $post, array $ori_config)
{
    $request = array(
        'cf_title' => isset($post['cf_title']) ? strip_tags(clean_xss_attributes($post['cf_title'])) : '',
        'cf_admin' => isset($post['cf_admin']) ? clean_xss_tags($post['cf_admin'], 1, 1) : '',
    );

    $field_types = array(
        'cf_use_email_certify' => 'int',
        'cf_use_hp' => 'int',
        'cf_req_hp' => 'int',
        'cf_register_level' => 'int',
        'cf_leave_day' => 'int',
        'cf_email_use' => 'int',
        'cf_prohibit_id' => 'text',
        'cf_prohibit_email' => 'text',
        'cf_page_rows' => 'int',
        'cf_cert_req' => 'int',
        'cf_cert_use' => 'int',
        'cf_cert_find' => 'int',
        'cf_cert_hp' => 'char',
        'cf_cert_simple' => 'char',
        'cf_cert_use_seed' => 'int',
        'cf_admin_email' => 'char',
        'cf_admin_email_name' => 'char',
        'cf_cut_name' => 'int',
        'cf_nick_modify' => 'int',
        'cf_possible_ip' => 'text',
        'cf_intercept_ip' => 'text',
        'cf_image_extension' => 'char',
        'cf_flash_extension' => 'char',
        'cf_movie_extension' => 'char',
        'cf_stipulation' => 'text',
        'cf_privacy' => 'text',
        'cf_use_promotion' => 'int',
        'cf_open_modify' => 'int',
        'cf_captcha_mp3' => 'char',
        'cf_cert_limit' => 'int',
        'cf_captcha' => 'char',
    );

    foreach ($field_types as $key => $type) {
        if ($type === 'int') {
            $request[$key] = isset($post[$key]) ? (int) $post[$key] : 0;
            continue;
        }

        if (in_array($key, array('cf_stipulation', 'cf_privacy'), true)) {
            $request[$key] = isset($post[$key]) ? $post[$key] : '';
            continue;
        }

        $request[$key] = isset($post[$key]) ? strip_tags(clean_xss_attributes($post[$key])) : '';
    }

    foreach (array('cf_cert_kcp_cd', 'cf_cert_kcp_enckey', 'cf_recaptcha_site_key', 'cf_recaptcha_secret_key', 'cf_cert_kg_cd', 'cf_cert_kg_mid') as $key) {
        $request[$key] = isset($post[$key]) && $post[$key]
            ? preg_replace('/[^a-z0-9_\-\.]/i', '', $post[$key])
            : '';
    }

    foreach (array('cf_image_extension', 'cf_flash_extension', 'cf_movie_extension') as $key) {
        if (!isset($post[$key])) {
            $request[$key] = isset($ori_config[$key]) ? $ori_config[$key] : '';
        }
    }

    return $request;
}

function admin_validate_config_admin($cf_admin)
{
    $mb = get_member($cf_admin);

    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        alert('최고관리자 회원아이디가 존재하지 않습니다.');
    }

    return $mb;
}

function admin_validate_config_intercept_ip($cf_intercept_ip, $remote_addr)
{
    if (!$cf_intercept_ip) {
        return;
    }

    $pattern = explode("\n", trim($cf_intercept_ip));
    for ($i = 0; $i < count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if (empty($pattern[$i])) {
            continue;
        }

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
        $pat = "/^{$pattern[$i]}$/";

        if (preg_match($pat, $remote_addr)) {
            alert('현재 접속 IP : ' . $remote_addr . ' 가 차단될수 있기 때문에, 다른 IP를 입력해 주세요.');
        }
    }
}

function admin_normalize_config_cert_settings(array $request)
{
    if ($request['cf_cert_use'] && !$request['cf_cert_hp'] && !$request['cf_cert_simple']) {
        alert('본인확인을 위해 휴대폰 본인확인 또는 KG이니시스 간편인증 서비스 중 하나 이상 선택해 주십시오.');
    }

    if (!$request['cf_cert_use']) {
        $request['cf_cert_hp'] = '';
        $request['cf_cert_simple'] = '';
    }

    if ($request['cf_cert_hp'] === 'kcb') {
        $request['cf_cert_hp'] = '';
    }

    return $request;
}

function admin_should_check_config_captcha(array $request, array $ori_config)
{
    return $request['cf_admin'] && $ori_config['cf_admin'] !== $request['cf_admin'];
}

function admin_check_config_captcha($should_check)
{
    if (!$should_check) {
        return;
    }

    include_once G5_CAPTCHA_PATH . '/captcha.lib.php';
    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

function admin_build_config_update_params(array $request)
{
    return array(
        'cf_title' => $request['cf_title'],
        'cf_admin' => $request['cf_admin'],
        'cf_admin_email' => $request['cf_admin_email'],
        'cf_admin_email_name' => $request['cf_admin_email_name'],
        'cf_use_email_certify' => $request['cf_use_email_certify'],
        'cf_cut_name' => $request['cf_cut_name'],
        'cf_nick_modify' => $request['cf_nick_modify'],
        'cf_possible_ip' => trim($request['cf_possible_ip']),
        'cf_intercept_ip' => trim($request['cf_intercept_ip']),
        'cf_use_hp' => $request['cf_use_hp'],
        'cf_req_hp' => $request['cf_req_hp'],
        'cf_register_level' => $request['cf_register_level'],
        'cf_leave_day' => $request['cf_leave_day'],
        'cf_email_use' => $request['cf_email_use'],
        'cf_prohibit_id' => $request['cf_prohibit_id'],
        'cf_prohibit_email' => $request['cf_prohibit_email'],
        'cf_image_extension' => $request['cf_image_extension'],
        'cf_flash_extension' => $request['cf_flash_extension'],
        'cf_movie_extension' => $request['cf_movie_extension'],
        'cf_page_rows' => $request['cf_page_rows'],
        'cf_stipulation' => $request['cf_stipulation'],
        'cf_privacy' => $request['cf_privacy'],
        'cf_use_promotion' => $request['cf_use_promotion'],
        'cf_open_modify' => $request['cf_open_modify'],
        'cf_captcha_mp3' => $request['cf_captcha_mp3'],
        'cf_cert_use' => $request['cf_cert_use'],
        'cf_cert_find' => $request['cf_cert_find'],
        'cf_cert_hp' => $request['cf_cert_hp'],
        'cf_cert_simple' => $request['cf_cert_simple'],
        'cf_cert_use_seed' => (int) $request['cf_cert_use_seed'],
        'cf_cert_kg_cd' => $request['cf_cert_kg_cd'],
        'cf_cert_kg_mid' => trim($request['cf_cert_kg_mid']),
        'cf_cert_kcp_cd' => $request['cf_cert_kcp_cd'],
        'cf_cert_kcp_enckey' => $request['cf_cert_kcp_enckey'],
        'cf_cert_limit' => $request['cf_cert_limit'],
        'cf_cert_req' => $request['cf_cert_req'],
        'cf_captcha' => $request['cf_captcha'],
        'cf_recaptcha_site_key' => $request['cf_recaptcha_site_key'],
        'cf_recaptcha_secret_key' => $request['cf_recaptcha_secret_key'],
    );
}

function admin_apply_config_update(array $update_params)
{
    global $g5;

    $sql = " update {$g5['config_table']}
                set cf_title = :cf_title,
                    cf_admin = :cf_admin,
                    cf_admin_email = :cf_admin_email,
                    cf_admin_email_name = :cf_admin_email_name,
                    cf_use_email_certify = :cf_use_email_certify,
                    cf_cut_name = :cf_cut_name,
                    cf_nick_modify = :cf_nick_modify,
                    cf_possible_ip = :cf_possible_ip,
                    cf_intercept_ip = :cf_intercept_ip,
                    cf_use_hp = :cf_use_hp,
                    cf_req_hp = :cf_req_hp,
                    cf_register_level = :cf_register_level,
                    cf_leave_day = :cf_leave_day,
                    cf_email_use = :cf_email_use,
                    cf_prohibit_id = :cf_prohibit_id,
                    cf_prohibit_email = :cf_prohibit_email,
                    cf_image_extension = :cf_image_extension,
                    cf_flash_extension = :cf_flash_extension,
                    cf_movie_extension = :cf_movie_extension,
                    cf_page_rows = :cf_page_rows,
                    cf_stipulation = :cf_stipulation,
                    cf_privacy = :cf_privacy,
                    cf_use_promotion = :cf_use_promotion,
                    cf_open_modify = :cf_open_modify,
                    cf_captcha_mp3 = :cf_captcha_mp3,
                    cf_cert_use = :cf_cert_use,
                    cf_cert_find = :cf_cert_find,
                    cf_cert_hp = :cf_cert_hp,
                    cf_cert_simple = :cf_cert_simple,
                    cf_cert_use_seed = :cf_cert_use_seed,
                    cf_cert_kg_cd = :cf_cert_kg_cd,
                    cf_cert_kg_mid = :cf_cert_kg_mid,
                    cf_cert_kcp_cd = :cf_cert_kcp_cd,
                    cf_cert_kcp_enckey = :cf_cert_kcp_enckey,
                    cf_cert_limit = :cf_cert_limit,
                    cf_cert_req = :cf_cert_req,
                    cf_captcha = :cf_captcha,
                    cf_recaptcha_site_key = :cf_recaptcha_site_key,
                    cf_recaptcha_secret_key = :cf_recaptcha_secret_key ";

    sql_query_prepared($sql, $update_params);
}

function admin_complete_config_form_update_request(array $request, array $ori_config, $auth, $sub_menu, $is_admin)
{
    auth_check_menu($auth, $sub_menu, 'w');
    admin_require_super_admin($is_admin);
    $server_input = g5_get_runtime_server_input();

    admin_validate_config_admin($request['cf_admin']);
    check_admin_token();

    admin_validate_config_intercept_ip($request['cf_intercept_ip'], isset($server_input['REMOTE_ADDR']) ? $server_input['REMOTE_ADDR'] : '');
    $request = admin_normalize_config_cert_settings($request);
    admin_check_config_captcha(admin_should_check_config_captcha($request, $ori_config));

    admin_apply_config_update(admin_build_config_update_params($request));

    g5_delete_all_cache();

    if (function_exists('get_admin_captcha_by')) {
        get_admin_captcha_by('remove');
    }

    run_event('admin_config_form_update');
    update_rewrite_rules();

    goto_url('./config_form.php', false);
}
