<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_delete_directory_tree($folder_path)
{
    $items = glob($folder_path . '/*');
    if (!is_array($items)) {
        $items = array();
    }

    foreach ($items as $item) {
        if (is_dir($item)) {
            admin_delete_directory_tree($item);
            continue;
        }

        @unlink($item);
    }

    @rmdir($folder_path);
}

function admin_process_member_list_file_delete()
{
    $base_path = G5_DATA_PATH . '/member_list';
    $messages = array();
    $count = 0;

    if (!@opendir($base_path)) {
        return array(
            'messages' => array('회원관리파일를 열지못했습니다.'),
            'count' => 0,
        );
    }

    $files = glob($base_path . '/*');
    if (!is_array($files)) {
        $files = array();
    }

    foreach ($files as $member_list_file) {
        $ext = strtolower(pathinfo($member_list_file, PATHINFO_EXTENSION));
        $basename = basename($member_list_file);

        if (is_file($member_list_file) && $ext !== 'log') {
            @unlink($member_list_file);
            $messages[] = '파일 삭제: ' . $member_list_file;
            $count++;
            continue;
        }

        if (is_dir($member_list_file) && $basename !== 'log') {
            admin_delete_directory_tree($member_list_file);
            $messages[] = '폴더 삭제: ' . $member_list_file;
            $count++;
        }
    }

    $messages[] = '완료됨';

    return array(
        'messages' => $messages,
        'count' => $count,
    );
}

function admin_complete_member_list_file_delete_request($is_admin)
{
    admin_require_super_admin($is_admin);

    return array(
        'title' => '회원관리파일 일괄삭제',
        'admin_container_class' => 'admin-page-member-export-delete',
        'admin_page_subtitle' => '서버에 남아 있는 회원 내보내기 산출물을 정리하고 삭제 결과를 바로 확인하세요.',
        'result' => admin_process_member_list_file_delete(),
    );
}
