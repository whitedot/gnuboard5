<?php
$sub_menu = "300200";
require_once './_common.php';

if ($w == 'u') {
    check_demo();
}

auth_check_menu($auth, $sub_menu, 'w');

if ($is_admin != 'super' && $w == '') {
    alert('최고관리자만 접근 가능합니다.');
}

check_admin_token();

$gr_id = isset($_POST['gr_id']) ? $_POST['gr_id'] : '';

if (!preg_match("/^([A-Za-z0-9_]{1,10})$/", $gr_id)) {
    alert('그룹 ID는 공백없이 영문자, 숫자, _ 만 사용 가능합니다. (10자 이내)');
}

if (empty($gr_subject)) {
    alert('그룹 제목을 입력하세요.');
}

$posts = array();

$check_keys = array(
    'gr_subject' => '',
    'gr_device' => '',
    'gr_admin' => '',
);

foreach ($check_keys as $key => $value) {
    if ($key === 'gr_subject') {
        $posts[$key] = isset($_POST[$key]) ? strip_tags(clean_xss_attributes($_POST[$key])) : '';
    } else {
        $posts[$key] = isset($_POST[$key]) ? $_POST[$key] : '';
    }
}

$sql_common = " gr_subject = '{$posts['gr_subject']}',
                gr_device = '{$posts['gr_device']}',
                gr_admin  = '{$posts['gr_admin']}' ";
if (isset($_POST['gr_use_access'])) {
    $sql_common .= ", gr_use_access = '{$_POST['gr_use_access']}' ";
} else {
    $sql_common .= ", gr_use_access = '' ";
}

if ($w == '') {
    $sql = " select count(*) as cnt from {$g5['group_table']} where gr_id = '{$gr_id}' ";
    $row = sql_fetch($sql);
    if ($row['cnt']) {
        alert('이미 존재하는 그룹 ID 입니다.');
    }

    $sql = " insert into {$g5['group_table']}
                set gr_id = '{$gr_id}',
                     {$sql_common} ";
    sql_query($sql);
} elseif ($w == "u") {
    $sql = " update {$g5['group_table']}
                set {$sql_common}
                where gr_id = '{$gr_id}' ";
    sql_query($sql);
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

run_event('admin_boardgroup_form_update', $gr_id, $w);

goto_url('./boardgroup_form.php?w=u&amp;gr_id=' . $gr_id . '&amp;' . $qstr);
