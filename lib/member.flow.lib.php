<?php
if (!defined('_GNUBOARD_')) exit;

class MemberNotificationService
{
    public static function sendPasswordLostMail($email, array $mb, $change_password, $mb_nonce, $mb_lost_certify)
    {
        global $config;

        $subject = '['.$config['cf_title'].'] 요청하신 회원정보 찾기 안내 메일입니다.';
        $href = G5_MEMBER_URL.'/password_lost_certify.php?mb_no='.$mb['mb_no'].'&amp;mb_nonce='.$mb_nonce;
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
        global $config;

        $subject = '['.$config['cf_title'].'] 인증확인 메일입니다.';
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
        sql_query_prepared(" update {$GLOBALS['g5']['member_table']} set mb_email_certify2 = :mb_email_certify2 where mb_id = :mb_id ", array(
            'mb_email_certify2' => $mb_md5,
            'mb_id' => $mb_id,
        ));

        $certify_href = G5_MEMBER_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;
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
        global $config;

        $subject = '['.$config['cf_title'].'] 인증확인 메일입니다.';
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
        sql_query_prepared(" update {$GLOBALS['g5']['member_table']} set mb_email_certify2 = :mb_email_certify2 where mb_id = :mb_id ", array(
            'mb_email_certify2' => $mb_md5,
            'mb_id' => $mb_id,
        ));

        $certify_href = G5_MEMBER_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;
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
        global $config;

        if ($msg) {
            MemberResponseRenderer::alertScript($msg);
        }

        run_event('register_form_update_after', $mb_id, $w);

        if ($w == '') {
            goto_url(G5_HTTP_MEMBER_URL.'/register_result.php');
        }

        if ($w != 'u') {
            return;
        }

        $tmp_password = sql_fetch_value_prepared(" select mb_password from {$GLOBALS['g5']['member_table']} where mb_id = :mb_id ", array(
            'mb_id' => $member['mb_id'],
        ));

        if ($old_email != $mb_email && $config['cf_use_email_certify']) {
            set_session('ss_mb_id', '');
            alert('회원 정보가 수정 되었습니다.\n\nE-mail 주소가 변경되었으므로 다시 인증하셔야 합니다.', G5_URL);
        }

        MemberResponseRenderer::autoPost(
            G5_HTTP_MEMBER_URL.'/register_form.php',
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

function member_certify_hash_matches($cert_type, $mb_name, $mb_hp, $md5_cert_no)
{
    if (!$cert_type || !$md5_cert_no) {
        return false;
    }

    $cert_birth = get_session('ss_cert_birth');
    $cert_hash = get_session('ss_cert_hash');

    if ($cert_type === 'ipin') {
        return $cert_hash === md5($mb_name . $cert_type . $cert_birth . $md5_cert_no);
    }

    return $cert_hash === md5($mb_name . $cert_type . $cert_birth . $mb_hp . $md5_cert_no);
}

function build_member_certify_fields($w, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    global $config;

    $sql_certify = array();

    if ($config['cf_cert_use'] && $cert_type && $md5_cert_no) {
        if (!member_certify_hash_matches($cert_type, $mb_name, $mb_hp, $md5_cert_no)) {
            alert('본인인증된 정보와 입력된 회원정보가 일치하지않습니다. 다시시도 해주세요');
        }

        $sql_certify['mb_hp'] = $mb_hp;
        $sql_certify['mb_certify'] = $cert_type;
        $sql_certify['mb_adult'] = get_session('ss_cert_adult');
        $sql_certify['mb_birth'] = get_session('ss_cert_birth');
        $sql_certify['mb_sex'] = get_session('ss_cert_sex');
        $sql_certify['mb_dupinfo'] = get_session('ss_cert_dupinfo');

        if ($w == 'u') {
            $sql_certify['mb_name'] = $mb_name;
        }

        return $sql_certify;
    }

    if (get_session('ss_reg_mb_name') != $mb_name || get_session('ss_reg_mb_hp') != $mb_hp) {
        $sql_certify['mb_hp'] = $mb_hp;
        $sql_certify['mb_certify'] = '';
        $sql_certify['mb_adult'] = 0;
        $sql_certify['mb_birth'] = '';
        $sql_certify['mb_sex'] = '';
    }

    return $sql_certify;
}

function member_insert_cert_history_if_verified($mb_id, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    if (!member_certify_hash_matches($cert_type, $mb_name, $mb_hp, $md5_cert_no)) {
        return;
    }

    insert_member_cert_history(
        $mb_id,
        $mb_name,
        $mb_hp,
        get_session('ss_cert_birth'),
        get_session('ss_cert_type')
    );
}

function build_member_agree_log_entry($prefix, array $agree_items)
{
    if (empty($agree_items)) {
        return '';
    }

    return '[' . G5_TIME_YMDHIS . ', ' . $prefix . '] ' . implode(' | ', $agree_items) . "\n";
}

function append_member_agree_log($prefix, array $agree_items, $existing_log = '')
{
    $entry = build_member_agree_log_entry($prefix, $agree_items);

    if ($entry === '') {
        return $existing_log;
    }

    return $entry . (string) $existing_log;
}

function member_build_create_agree_updates(array &$insert_fields, $mb_marketing_agree, $mb_mailling)
{
    $agree_items = array();

    if ($mb_marketing_agree == 1) {
        $insert_fields['mb_marketing_date'] = G5_TIME_YMDHIS;
        $agree_items[] = '마케팅 목적의 개인정보 수집 및 이용(동의)';
    }

    if ($mb_mailling == 1) {
        $insert_fields['mb_mailling_date'] = G5_TIME_YMDHIS;
        $agree_items[] = '광고성 이메일 수신(동의)';
    }

    if (!empty($agree_items)) {
        $insert_fields['mb_agree_log'] = build_member_agree_log_entry('회원가입', $agree_items);
    }
}

function member_build_update_agree_updates(array &$update_fields, $mb_id, $mb_marketing_agree_default, $mb_marketing_agree, $mb_mailling_default, $mb_mailling)
{
    $agree_items = array();

    if ($mb_marketing_agree_default !== $mb_marketing_agree) {
        $update_fields['mb_marketing_date'] = G5_TIME_YMDHIS;
        $agree_items[] = '마케팅 목적의 개인정보 수집 및 이용(' . ($mb_marketing_agree == 1 ? '동의' : '철회') . ')';
    }

    if ($mb_mailling_default !== $mb_mailling) {
        $update_fields['mb_mailling_date'] = G5_TIME_YMDHIS;
        $agree_items[] = '광고성 이메일 수신(' . ($mb_mailling == 1 ? '동의' : '철회') . ')';
    }

    if (!empty($agree_items)) {
        $member_agree_row = get_member($mb_id, 'mb_agree_log');
        $existing_agree_log = isset($member_agree_row['mb_agree_log']) ? $member_agree_row['mb_agree_log'] : '';
        $update_fields['mb_agree_log'] = append_member_agree_log('회원 정보 수정', $agree_items, $existing_agree_log);
    }
}

function member_insert_account_with_history($mb_id, array $insert_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    $insert_parts = array();
    foreach ($insert_fields as $field => $value) {
        $insert_parts[] = $field . ' = :' . $field;
    }

    if (!sql_begin_transaction()) {
        return false;
    }

    $sql = " insert into {$GLOBALS['g5']['member_table']} set " . implode(', ', $insert_parts);
    if (!sql_query_prepared($sql, $insert_fields, false)) {
        sql_rollback();
        return false;
    }

    member_insert_cert_history_if_verified($mb_id, $mb_name, $mb_hp, $cert_type, $md5_cert_no);

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    return true;
}

function member_update_account_with_history($mb_id, array $update_fields, $mb_name, $mb_hp, $cert_type, $md5_cert_no)
{
    $update_parts = array();
    foreach ($update_fields as $field => $value) {
        $update_parts[] = $field . ' = :' . $field;
    }

    $update_fields['mb_id'] = $mb_id;
    $sql = " update {$GLOBALS['g5']['member_table']}
                set " . implode(",\n                    ", $update_parts) . "
              where mb_id = :mb_id ";

    if (!sql_begin_transaction()) {
        return false;
    }

    if (!sql_query_prepared($sql, $update_fields, false)) {
        sql_rollback();
        return false;
    }

    member_insert_cert_history_if_verified($mb_id, $mb_name, $mb_hp, $cert_type, $md5_cert_no);

    if (!sql_commit()) {
        sql_rollback();
        return false;
    }

    return true;
}

function member_read_registration_request($w, array $post, array $session)
{
    $request = array(
        'mb_id' => '',
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'mb_password_re' => isset($post['mb_password_re']) ? trim($post['mb_password_re']) : '',
        'mb_name' => clean_xss_tags(isset($post['mb_name']) ? trim($post['mb_name']) : '', 1, 1),
        'mb_nick' => isset($post['mb_nick']) ? trim($post['mb_nick']) : '',
        'mb_email' => get_email_address(isset($post['mb_email']) ? trim($post['mb_email']) : ''),
        'mb_sex' => isset($post['mb_sex']) ? trim($post['mb_sex']) : '',
        'mb_birth' => isset($post['mb_birth']) ? trim($post['mb_birth']) : '',
        'mb_hp' => isset($post['mb_hp']) ? trim($post['mb_hp']) : '',
        'mb_mailling' => isset($post['mb_mailling']) ? trim($post['mb_mailling']) : '0',
        'mb_open' => isset($post['mb_open']) ? trim($post['mb_open']) : '0',
        'mb_marketing_agree' => isset($post['mb_marketing_agree']) ? trim($post['mb_marketing_agree']) : '0',
        'mb_nick_default' => isset($post['mb_nick_default']) ? trim($post['mb_nick_default']) : '',
        'mb_open_default' => isset($post['mb_open_default']) ? trim($post['mb_open_default']) : '0',
        'mb_marketing_agree_default' => isset($post['mb_marketing_agree_default']) ? trim($post['mb_marketing_agree_default']) : '0',
        'mb_mailling_default' => isset($post['mb_mailling_default']) ? trim($post['mb_mailling_default']) : '0',
        'cert_no' => isset($post['cert_no']) ? trim($post['cert_no']) : '',
    );

    if ($w === 'u') {
        $request['mb_id'] = isset($session['ss_mb_id']) ? trim($session['ss_mb_id']) : '';
    } elseif ($w === '') {
        $request['mb_id'] = isset($post['mb_id']) ? trim($post['mb_id']) : '';
    }

    return $request;
}

function member_read_admin_member_request(array $post)
{
    $request = array(
        'mb_id' => isset($post['mb_id']) ? trim($post['mb_id']) : '',
        'mb_password' => isset($post['mb_password']) ? trim($post['mb_password']) : '',
        'mb_certify_case' => isset($post['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', $post['mb_certify_case']) : '',
        'mb_certify' => isset($post['mb_certify']) ? preg_replace('/[^0-9a-z_]/i', '', $post['mb_certify']) : '',
        'mb_marketing_agree' => isset($post['mb_marketing_agree']) ? clean_xss_tags($post['mb_marketing_agree'], 1, 1) : '0',
        'mb_email' => isset($post['mb_email']) ? get_email_address(trim($post['mb_email'])) : '',
        'mb_nick' => isset($post['mb_nick']) ? trim(strip_tags($post['mb_nick'])) : '',
        'mb_memo' => isset($post['mb_memo']) ? clean_xss_tags(trim($post['mb_memo']), 1, 1) : '',
        'mb_hp' => hyphen_hp_number(isset($post['mb_hp']) ? $post['mb_hp'] : ''),
        'passive_certify' => !empty($post['passive_certify']),
        'posts' => array(),
    );

    $check_keys = array(
        'mb_name',
        'mb_leave_date',
        'mb_intercept_date',
        'mb_mailling',
        'mb_open',
        'mb_level'
    );

    for ($i = 1; $i <= 10; $i++) {
        $check_keys[] = 'mb_' . $i;
    }

    foreach ($check_keys as $key) {
        $request['posts'][$key] = isset($post[$key]) ? clean_xss_tags($post[$key], 1, 1) : '';
    }

    if ($request['mb_certify_case'] && $request['mb_certify']) {
        $request['mb_certify'] = isset($post['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', (string) $post['mb_certify_case']) : '';
        $request['mb_adult'] = isset($post['mb_adult']) ? preg_replace('/[^0-9a-z_]/i', '', (string) $post['mb_adult']) : '';
    } else {
        $request['mb_certify'] = '';
        $request['mb_adult'] = 0;
    }

    return $request;
}

function member_clear_certification_session()
{
    $session_keys = array(
        'ss_cert_type',
        'ss_cert_no',
        'ss_cert_hash',
        'ss_cert_birth',
        'ss_cert_adult',
    );

    foreach ($session_keys as $session_key) {
        if (isset($_SESSION[$session_key])) {
            unset($_SESSION[$session_key]);
        }
    }
}
