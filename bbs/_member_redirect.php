<?php
include_once('./_common.php');

function redirect_member_page($filename)
{
    $query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';
    goto_url(G5_MEMBER_URL.'/'.$filename.$query);
}
