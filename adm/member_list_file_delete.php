<?php
$sub_menu = '100930';
include_once './_common.php';

$page_view = admin_complete_member_list_file_delete_request($is_admin);
$delete_result = $page_view['result'];
$g5['title'] = $page_view['title'];
$admin_container_class = $page_view['admin_container_class'];
$admin_page_subtitle = $page_view['admin_page_subtitle'];
include_once G5_ADMIN_PATH . '/admin.head.php';
?>

<section class="card space-y-4 p-5">
    <p class="text-sm text-default-700">
        완료 메시지가 나오기 전에는 프로그램 실행을 중지하지 마십시오.
    </p>

    <ul class="space-y-2 text-sm text-default-800">
        <?php foreach ($delete_result['messages'] as $message) { ?>
            <li><?php echo $message; ?></li>
        <?php } ?>
    </ul>

    <p class="text-sm">
        <strong>회원관리파일 <?php echo $delete_result['count']; ?>건 삭제 완료됐습니다.</strong><br>
        프로그램의 실행을 끝마치셔도 좋습니다.
    </p>
</section>

<?php
include_once G5_ADMIN_PATH . '/admin.tail.php';
