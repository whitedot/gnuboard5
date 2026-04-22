<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_bootstrap_load_auth(array $member)
{
    global $g5;

    $auth = array();
    $sql = " select au_menu, au_auth from {$g5['auth_table']} where mb_id = :mb_id ";
    $result = sql_query_prepared($sql, array(
        'mb_id' => $member['mb_id'],
    ));

    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $auth[$row['au_menu']] = $row['au_auth'];
    }

    if (!$i) {
        alert('최고관리자 또는 관리권한이 있는 회원만 접근 가능합니다.', G5_URL);
    }

    @ksort($auth);

    return $auth;
}

function admin_bootstrap_require_access(array $member, $is_admin)
{
    if (!$member['mb_id']) {
        alert('로그인 하십시오.', G5_MEMBER_URL . '/login.php?url=' . urlencode(correct_goto_url(G5_ADMIN_URL)));
    }

    if ($is_admin == 'super') {
        return array();
    }

    return admin_bootstrap_load_auth($member);
}

function admin_bootstrap_verify_client(array $member)
{
    if (verify_mb_key($member)) {
        return;
    }

    session_destroy();
    include_once G5_LIB_PATH . '/support/mail.lib.php';

    mailer(
        $member['mb_nick'],
        $member['mb_email'],
        $member['mb_email'],
        'XSS 공격 알림',
        $_SERVER['REMOTE_ADDR'] . ' 아이피로 XSS 공격이 있었습니다.<br><br>관리자 권한을 탈취하려는 접근이므로 주의하시기 바랍니다.<br><br>해당 아이피는 차단하시고 의심되는 게시물이 있는지 확인하시기 바랍니다.' . G5_URL,
        0
    );

    alert_close('정상적으로 로그인하여 접근하시기 바랍니다.');
}
