<?php
$sub_menu = '100930';
include_once './_common.php';

$page_view = admin_complete_member_list_file_delete_request($is_admin);
$delete_result = $page_view['result'];
$g5['title'] = $page_view['title'];
include_once G5_ADMIN_PATH . '/admin.head.php';
?>

<p>
    완료 메세지가 나오기 전에 프로그램의 실행을 중지하지 마십시오.
</p>

<div><ul>
    <?php foreach ($delete_result['messages'] as $message) { ?>
        <li><?php echo $message; ?></li>
    <?php } ?>
</ul></div>

<div><p><strong>회원관리파일 <?php echo $delete_result['count']; ?>건 삭제 완료됐습니다.</strong><br>프로그램의 실행을 끝마치셔도 좋습니다.</p></div>

<?php
include_once G5_ADMIN_PATH . '/admin.tail.php';
