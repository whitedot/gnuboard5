<?php
include_once('./_common.php');

function print_result($error, $type='done')
{
    $json = json_encode(array('error'=>$error, 'type'=>$type));
    echo ($json);
    if($error)
        exit;
}

if (isset($_POST['comment_id']) && !empty($_POST['comment_id'])) {

    $real_bo_table = $g5['write_prefix'].$bo_table;

    $error = $count = "";

    $comment_id = $_POST['comment_id'];
    $comment_parent_id = $_POST['comment_parent_id'];
    $bo_table = $_POST['bo_table'];

    if (!$is_member)
    {
        $error = '회원만 가능합니다.';
        $type = 'err';
        print_result($error, $type);
    }

    if (!($bo_table && $comment_id)) {
        $error = '값이 제대로 넘어오지 않았습니다.';
        $type = 'err';
        print_result($error, $type);
    }

    $row = sql_fetch(" select count(*) as cnt from {$real_bo_table} ");
    if (!$row['cnt']) {
        $error = '게시판이 없습니다.';
        $type = 'err';
        print_result($error, $type);
    }

    $row = sql_fetch(" select wr_id from {$real_bo_table} where wr_id = {$comment_id} ");
    if (!$row['wr_id']) {
        $error = '댓글이 없습니다.';
        $type = 'err';
        print_result($error, $type);
    }

    $row = sql_fetch(" select wr_id from {$real_bo_table} where wr_parent = {$comment_parent_id} and wr_is_comment = 1 and wr_1 = 1 ");
    if ($row['wr_id'] && $row['wr_id'] != $comment_id) {
        sql_query(" update {$real_bo_table} set wr_1 = '' where wr_id = {$row['wr_id']} ");
    }

    $type = 'done';
    sql_query(" update {$real_bo_table} set wr_1 = 1 where wr_id = {$comment_id} ");
    print_result($error, $type);

} else {

    $error = '댓글 아이디가 넘어오지 않았습니다.';
    $type = 'err';
    print_result($error, $type);

}