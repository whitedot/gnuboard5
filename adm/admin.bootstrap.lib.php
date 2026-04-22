<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// 접근 권한 검사
if (!$member['mb_id']) {
    alert('로그인 하십시오.', G5_MEMBER_URL . '/login.php?url=' . urlencode(correct_goto_url(G5_ADMIN_URL)));
} else if ($is_admin != 'super') {
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
}

// 관리자의 클라이언트를 검증하여 일치하지 않으면 세션을 끊고 관리자에게 메일을 보낸다.
if (!verify_mb_key($member)) {
    session_destroy();

    include_once G5_LIB_PATH . '/mailer.lib.php';

    mailer($member['mb_nick'], $member['mb_email'], $member['mb_email'], 'XSS 공격 알림', $_SERVER['REMOTE_ADDR'] . ' 아이피로 XSS 공격이 있었습니다.<br><br>관리자 권한을 탈취하려는 접근이므로 주의하시기 바랍니다.<br><br>해당 아이피는 차단하시고 의심되는 게시물이 있는지 확인하시기 바랍니다.' . G5_URL, 0);

    alert_close('정상적으로 로그인하여 접근하시기 바랍니다.');
}

if (isset($auth) && is_array($auth)) {
    @ksort($auth);
} else {
    $auth = array();
}

unset($menu);
unset($amenu);
$tmp = dir(G5_ADMIN_PATH);
$menu_files = array();
while ($entry = $tmp->read()) {
    if (!preg_match('/^admin.menu([0-9]{3}).*\.php$/', $entry, $m)) {
        continue;
    }

    $amenu[$m[1]] = $entry;
    $menu_files[] = G5_ADMIN_PATH . '/' . $entry;
}
@asort($menu_files);
foreach ($menu_files as $file) {
    include_once $file;
}
@ksort($amenu);

$amenu = run_replace('admin_amenu', $amenu);
if (isset($menu) && $menu) {
    $menu = run_replace('admin_menu', $menu);
}

$arr_query = array();
if (isset($sst)) {
    $arr_query[] = 'sst=' . $sst;
}
if (isset($sod)) {
    $arr_query[] = 'sod=' . $sod;
}
if (isset($sfl)) {
    $arr_query[] = 'sfl=' . $sfl;
}
if (isset($stx)) {
    $arr_query[] = 'stx=' . $stx;
}
if (isset($page)) {
    $arr_query[] = 'page=' . $page;
}
$qstr = implode("&amp;", $arr_query);

if (isset($_REQUEST) && $_REQUEST) {
    if (admin_referer_check(true)) {
        admin_check_xss_params($_REQUEST);
    }
}
