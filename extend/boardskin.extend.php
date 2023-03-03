<?php

// 사용법(1)
// 아래 구조의 테이블을 생성하세요.
// 어떻게 쓰는지 모르겠으면 아래 코드를 복사해서 그누보드 index.php 에 붙여넣고,
// 메인페이지에 한 번 접속하면 됩니다.
/*
sql_query(" CREATE TABLE `g5_select_bo_skin` (
    `skin_id` int(11) NOT NULL,
    `mb_id` varchar(20) NOT NULL,
    `bo_table` varchar(255) NOT NULL,
    `bo_skin` varchar(255) NOT NULL,
    `bo_mobile_skin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8; ");
*/

// 사용법(2)
// 관리자 > 환경설정 > 기본환경설정 > 여분필드 > 여분필드1 에
// 멀티스킨을 이용할 게시판 아이디(bo_table)값을 |로 구분하여 입력하세요.
$arr_select_bo = explode('|', $config['cf_1']);

// 멀티 스킨을 이용하는 게시판일 때만 동작
if (isset($bo_table) && in_array($bo_table, $arr_select_bo)) {

    // PC, 모바일 구분
    $bo_skin_device = 'bo_';
    if ($is_mobile) $bo_skin_device .= 'mobile_';
    $bo_skin_device .= 'skin';

    // $_GET['select_bo_skin'] 값이 있으면
    $_bo_skin = clean_xss_tags($_GET['select_bo_skin']);
    if (isset($_bo_skin) && !empty($_bo_skin)) {

        // 게시판 스킨(디렉토리 기준)이 존재하면 세션을 생성합니다.
        $dirname = get_skin_path('board', $_bo_skin);
        if (is_dir($dirname))
            set_session('ss_select_bo_skin', $_bo_skin);

    }

    $ss_bo_skin = get_session('ss_select_bo_skin');

    // 회원이면
    if ($is_member) {

        // DB에 저장된 마지막값 호출
        $sql = " SELECT {$bo_skin_device} FROM g5_select_bo_skin WHERE mb_id = '{$member['mb_id']}' and bo_table = '{$bo_table}' ORDER BY skin_id DESC ";
        $result = sql_fetch($sql);

        // 세션이 있으면
        if ($ss_bo_skin) {

            // DB에 저장된 값이 있고
            if ($result[$bo_skin_device]) {

                // 세션값이 다르면 정보 UPDATE
                if ($result[$bo_skin_device] != $ss_bo_skin)
                    sql_query(" UPDATE g5_select_bo_skin SET {$bo_skin_device} = '{$ss_bo_skin}' WHERE mb_id = '{$member['mb_id']}' AND bo_table = '{$bo_table}' ");

            // DB에 저장된 값이 없으면 INSERT
            } else {

                sql_query(" INSERT INTO g5_select_bo_skin SET {$bo_skin_device} = '{$ss_bo_skin}', mb_id = '{$member['mb_id']}', bo_table = '{$bo_table}' ");

            }

        // 세션이 없으면
        } else {

            // DB에 저장된 값이 있으면 사용
            if ($result[$bo_skin_device])
                $ss_bo_skin = $result[$bo_skin_device];

        }

    }

    // 회원&비회원 공통
    if ($ss_bo_skin) {
        $board_skin_path = get_skin_path('board', $ss_bo_skin);
        $board_skin_url = get_skin_url('board', $ss_bo_skin);
    }

}