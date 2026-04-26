<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_get_member_form_defaults()
{
    return array(
        'mb_certify' => null,
        'mb_adult' => null,
        'mb_intercept_date' => null,
        'mb_id' => null,
        'mb_name' => null,
        'mb_nick' => null,
        'mb_email' => null,
        'mb_hp' => null,
        'mb_memo' => null,
        'mb_leave_date' => null,
    );
}

function admin_build_member_form_binary_options($selected_value)
{
    return array(
        array(
            'value' => '1',
            'label' => '예',
            'checked' => (bool) $selected_value,
        ),
        array(
            'value' => '0',
            'label' => '아니오',
            'checked' => !$selected_value,
        ),
    );
}

function admin_build_member_form_certify_case_options($selected_value)
{
    return array(
        array(
            'value' => 'simple',
            'label' => '간편인증',
            'id' => 'mb_certify_sa',
            'checked' => $selected_value === 'simple',
        ),
        array(
            'value' => 'hp',
            'label' => '휴대폰',
            'id' => 'mb_certify_hp',
            'checked' => $selected_value === 'hp',
        ),
    );
}

function admin_build_member_form_view(array $request, array $member, $is_admin, array $config)
{
    global $g5;

    $w = $request['w'];
    $mb_id = $request['mb_id'];
    $mb = admin_get_member_form_defaults();
    $html_title = '';
    $is_create = false;
    $is_update = false;
    $mb_id_input = array(
        'required' => false,
        'readonly' => false,
        'classes' => 'form-input',
    );
    $password_input = array(
        'required' => false,
        'classes' => 'form-input',
    );

    if ($w == '') {
        $is_create = true;
        $mb_id_input = array(
            'required' => true,
            'readonly' => false,
            'classes' => 'required alnum_ form-input',
        );
        $password_input = array(
            'required' => true,
            'classes' => 'required form-input',
        );
        $mb['mb_mailling'] = 1;
        $mb['mb_open'] = 1;
        $mb['mb_level'] = $config['cf_register_level'];
        $mb['mb_marketing_agree'] = 0;
        $html_title = '추가';
    } elseif ($w == 'u') {
        $is_update = true;
        $mb = get_member($mb_id);
        if (!$mb['mb_id']) {
            alert('존재하지 않는 회원자료입니다.');
        }

        if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
            alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');
        }

        $mb_id_input = array(
            'required' => false,
            'readonly' => true,
            'classes' => 'form-input',
        );
        $html_title = '수정';
        $mb['mb_name'] = get_text($mb['mb_name']);
        $mb['mb_nick'] = get_text($mb['mb_nick']);
        $mb['mb_email'] = get_text($mb['mb_email']);
        $mb['mb_birth'] = get_text($mb['mb_birth']);
        $mb['mb_hp'] = get_text($mb['mb_hp']);
    } else {
        alert('제대로 된 값이 넘어오지 않았습니다.');
    }

    $mb_cert_history = '';
    if ($mb_id) {
        $sql = "select * from {$g5['member_cert_history_table']} where mb_id = :mb_id order by ch_id asc";
        $mb_cert_history = sql_query_prepared($sql, array('mb_id' => $mb_id));
    }

    $title_prefix = $mb['mb_intercept_date'] ? '차단된 ' : '';

    return array(
        'mb' => $mb,
        'display_mb_id' => member_get_display_id($mb),
        'mask_preserved_id' => member_should_mask_preserved_id($mb),
        'is_create' => $is_create,
        'is_update' => $is_update,
        'mb_id_input' => $mb_id_input,
        'password_input' => $password_input,
        'member_level_options' => admin_build_member_level_options(1, (int) $member['mb_level'], isset($mb['mb_level']) ? $mb['mb_level'] : ''),
        'html_title' => $html_title,
        'certify_case_options' => admin_build_member_form_certify_case_options(isset($mb['mb_certify']) ? $mb['mb_certify'] : ''),
        'mb_certify_options' => admin_build_member_form_binary_options(!empty($mb['mb_certify'])),
        'mb_adult_options' => admin_build_member_form_binary_options(!empty($mb['mb_adult'])),
        'mb_mailling_options' => admin_build_member_form_binary_options(!empty($mb['mb_mailling'])),
        'mb_open_options' => admin_build_member_form_binary_options(!empty($mb['mb_open'])),
        'mb_marketing_agree_options' => admin_build_member_form_binary_options(!empty($mb['mb_marketing_agree'])),
        'mb_cert_history' => $mb_cert_history,
        'title' => $title_prefix . '회원 ' . $html_title,
    );
}

function admin_member_certify_type_label($type)
{
    switch ($type) {
        case 'simple':
            return '간편인증';
        case 'hp':
            return '휴대폰';
        default:
            return '기타';
    }
}

function admin_build_member_form_basic_section_view(array $member_form_view)
{
    return array(
        'is_create' => $member_form_view['is_create'],
        'member' => $member_form_view['mb'],
        'mask_preserved_id' => $member_form_view['mask_preserved_id'],
        'display_mb_id' => $member_form_view['display_mb_id'],
        'mb_id_input' => $member_form_view['mb_id_input'],
        'password_input' => $member_form_view['password_input'],
        'member_level_options' => $member_form_view['member_level_options'],
    );
}

function admin_build_member_form_contact_section_view(array $member_form_view)
{
    return array(
        'member' => $member_form_view['mb'],
        'certify_case_options' => $member_form_view['certify_case_options'],
        'mb_certify_options' => $member_form_view['mb_certify_options'],
        'mb_adult_options' => $member_form_view['mb_adult_options'],
    );
}

function admin_build_member_form_consent_section_view(array $member_form_view)
{
    $member = $member_form_view['mb'];

    return array(
        'is_update' => $member_form_view['is_update'],
        'mailing_options' => $member_form_view['mb_mailling_options'],
        'mailing_agree_date' => (!empty($member['mb_mailling']) && isset($member['mb_mailling_date']) && $member['mb_mailling_date'] !== '0000-00-00 00:00:00') ? $member['mb_mailling_date'] : '',
        'marketing_agree_options' => $member_form_view['mb_marketing_agree_options'],
        'marketing_agree_date' => (!empty($member['mb_marketing_agree']) && isset($member['mb_marketing_date']) && $member['mb_marketing_date'] !== '0000-00-00 00:00:00') ? $member['mb_marketing_date'] : '',
        'agree_log_html' => !empty($member['mb_agree_log']) ? conv_content($member['mb_agree_log'], 0) : '',
        'open_options' => $member_form_view['mb_open_options'],
        'open_date' => (!empty($member['mb_open']) && isset($member['mb_open_date']) && $member['mb_open_date'] !== '0000-00-00 00:00:00') ? $member['mb_open_date'] : '',
    );
}

function admin_build_member_form_profile_section_view(array $member_form_view)
{
    return array(
        'memo_value' => html_purifier(isset($member_form_view['mb']['mb_memo']) ? $member_form_view['mb']['mb_memo'] : ''),
    );
}

function admin_build_member_form_history_rows($mb_cert_history, $display_mb_id)
{
    $rows = array();

    if (!$mb_cert_history) {
        return $rows;
    }

    sql_data_seek($mb_cert_history, 0);
    while ($row = sql_fetch_array($mb_cert_history)) {
        $rows[] = array(
            'datetime' => $row['ch_datetime'],
            'display_mb_id' => $display_mb_id,
            'name' => $row['ch_name'],
            'hp' => $row['ch_hp'],
            'cert_type_label' => admin_member_certify_type_label($row['ch_type']),
        );
    }

    return $rows;
}

function admin_build_member_form_history_section_view(array $member_form_view, array $config)
{
    $member = $member_form_view['mb'];

    return array(
        'is_update' => $member_form_view['is_update'],
        'cert_history_rows' => admin_build_member_form_history_rows($member_form_view['mb_cert_history'], $member_form_view['display_mb_id']),
        'member_joined_at' => isset($member['mb_datetime']) ? $member['mb_datetime'] : '',
        'member_last_login_at' => isset($member['mb_today_login']) ? $member['mb_today_login'] : '',
        'member_ip' => isset($member['mb_ip']) ? $member['mb_ip'] : '',
        'show_email_certify' => !empty($config['cf_use_email_certify']),
        'email_certify_at' => isset($member['mb_email_certify']) ? $member['mb_email_certify'] : '',
        'leave_date' => isset($member['mb_leave_date']) ? $member['mb_leave_date'] : '',
        'intercept_date' => isset($member['mb_intercept_date']) ? $member['mb_intercept_date'] : '',
        'today_ymd' => date('Ymd'),
        'event_member' => $member,
        'event_mode' => isset($member_form_view['w']) ? $member_form_view['w'] : '',
    );
}

function admin_build_member_form_page_view(array $member_form_view, array $config, array $member_list_request = array())
{
    $hidden_fields = array(
        'w' => isset($member_form_view['w']) ? $member_form_view['w'] : '',
        'sfl' => isset($member_list_request['sfl']) ? $member_list_request['sfl'] : '',
        'stx' => isset($member_list_request['stx']) ? $member_list_request['stx'] : '',
        'sst' => isset($member_list_request['sst']) ? $member_list_request['sst'] : '',
        'sod' => isset($member_list_request['sod']) ? $member_list_request['sod'] : '',
        'page' => isset($member_list_request['page']) ? $member_list_request['page'] : '',
    );

    return array(
        'title' => $member_form_view['title'],
        'admin_container_class' => 'admin-page-member-form',
        'admin_page_subtitle' => '기본정보, 인증 연락처, 동의 상태, 활동 이력을 탭에서 빠르게 관리하세요.',
        'list_url' => './member_list.php?' . admin_bootstrap_build_qstr($member_list_request),
        'hidden_fields' => $hidden_fields,
        'admin_token' => get_admin_token(),
        'event_member' => $member_form_view['mb'],
        'event_mode' => $hidden_fields['w'],
        'pg_anchor_menu_view' => admin_build_anchor_menu_view(admin_member_form_tabs(), array(
            'nav_id' => 'member_tabs_nav',
            'nav_class' => 'tab-nav-justified',
            'nav_aria_label' => '회원 등록/수정 탭',
            'link_class' => 'tab-trigger-underline-justified js-member-tab-link',
            'active_class' => 'active',
            'as_tabs' => true,
            'link_id_prefix' => 'member_tab_',
        )),
        'sections' => array(
            'basic' => admin_build_member_form_basic_section_view($member_form_view),
            'contact' => admin_build_member_form_contact_section_view($member_form_view),
            'consent' => admin_build_member_form_consent_section_view($member_form_view),
            'profile' => admin_build_member_form_profile_section_view($member_form_view),
            'history' => admin_build_member_form_history_section_view($member_form_view, $config),
        ),
    );
}

function admin_member_form_tabs()
{
    return array(
        array('id' => 'anc_mb_basic', 'label' => '기본 정보'),
        array('id' => 'anc_mb_contact', 'label' => '인증 연락처'),
        array('id' => 'anc_mb_consent', 'label' => '수신 및 공개 설정'),
        array('id' => 'anc_mb_profile', 'label' => '관리 메모'),
        array('id' => 'anc_mb_history', 'label' => '인증 및 활동 내역'),
    );
}
