<?php
$sub_menu = "100280";
include_once('./_common.php');

$data = array();
$data['error'] = '';

$data['error'] = auth_check_menu($auth, $sub_menu, 'w', true);
if($data['error'])
    die(json_encode($data));

if(!$config['cf_theme']) {
    $data['error'] = '사용 중인 테마가 없습니다.';
    die(json_encode($data));
}

$theme_dir = get_theme_dir();
if(!in_array($config['cf_theme'], $theme_dir)) {
    $data['error'] = $config['cf_theme'].' 테마는 설치된 테마가 아닙니다.';
    die(json_encode($data));
}

$type = $_POST['type'];
$arr_type = array('conf_member');
if(!in_array($type, $arr_type)) {
    $data['error'] = '올바른 방법으로 이용해 주십시오.';
    die(json_encode($data));
}

if($type == 'conf_member') {
    $keys = run_replace('theme_config_load_keys', array('cf_member_skin'), $type);

    $tconfig = get_theme_config_value($config['cf_theme'], implode(',', $keys));

    $i = 0;
    foreach($keys as $val) {
        if($tconfig[$val]) {
            $data[$val] = preg_match('#^theme/.+$#', $tconfig[$val]) ? $tconfig[$val] : 'theme/'.$tconfig[$val];
            $i++;
        }
    }

    if($i == 0)
        $data['error'] = '적용할 기본환경 회원스킨 설정이 없습니다.';
}

die(json_encode($data));
