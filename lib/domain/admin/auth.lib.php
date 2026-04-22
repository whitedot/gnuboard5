<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function auth_check_menu($auth, $sub_menu, $attr, $return = false)
{
    $check_auth = isset($auth[$sub_menu]) ? $auth[$sub_menu] : '';

    return auth_check($check_auth, $attr, $return);
}

function auth_check($auth, $attr, $return = false)
{
    global $is_admin;

    if ($is_admin == 'super') {
        return;
    }

    if (!trim($auth)) {
        $msg = '이 메뉴에는 접근 권한이 없습니다.\\n\\n접근 권한은 최고관리자만 부여할 수 있습니다.';
        if ($return) {
            return $msg;
        }

        alert($msg);
    }

    $attr = strtolower($attr);

    if (!strstr($auth, $attr)) {
        if ($attr == 'r') {
            $msg = '읽을 권한이 없습니다.';
        } elseif ($attr == 'w') {
            $msg = '입력, 추가, 생성, 수정 권한이 없습니다.';
        } elseif ($attr == 'd') {
            $msg = '삭제 권한이 없습니다.';
        } else {
            $msg = '속성이 잘못 되었습니다.';
        }

        if ($return) {
            return $msg;
        }

        alert($msg);
    }
}
