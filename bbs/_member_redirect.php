<?php
include_once('./_common.php');

function redirect_member_page($filename)
{
    $query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';
    goto_url(G5_MEMBER_URL.'/'.$filename.$query);
}

function run_member_page($filename)
{
    $member_file = G5_MEMBER_PATH.'/'.$filename;

    if (!is_file($member_file)) {
        alert('회원 전용 페이지를 찾을 수 없습니다.', G5_URL);
    }

    chdir(G5_MEMBER_PATH);
    include $member_file;
    exit;
}
