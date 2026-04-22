<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// 경고메세지를 경고창으로
function alert($msg = '', $url = '', $error = true, $post = false)
{
    global $g5, $config, $member, $is_member, $is_admin;

    run_event('alert', $msg, $url, $error, $post);

    if (function_exists('safe_filter_url_host')) {
        $url = safe_filter_url_host($url);
    }

    $msg = $msg ? strip_tags($msg, '<br>') : '올바른 방법으로 이용해 주십시오.';

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_PATH.'/alert.php');
    exit;
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg, $error = true)
{
    global $g5, $config, $member, $is_member, $is_admin;

    run_event('alert_close', $msg, $error);

    $msg = strip_tags($msg, '<br>');

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_PATH.'/alert_close.php');
    exit;
}

// confirm 창
function confirm($msg, $url1 = '', $url2 = '', $url3 = '')
{
    global $g5, $config, $member, $is_member, $is_admin;

    if (!$msg) {
        $msg = '올바른 방법으로 이용해 주십시오.';
        alert($msg);
    }

    if (function_exists('safe_filter_url_host')) {
        $url1 = safe_filter_url_host($url1);
        $url2 = safe_filter_url_host($url2);
        $url3 = safe_filter_url_host($url3);
    }

    if (!trim($url1) || !trim($url2)) {
        $msg = '$url1 과 $url2 를 지정해 주세요.';
        alert($msg);
    }

    if (!$url3) {
        $url3 = clean_xss_tags($_SERVER['HTTP_REFERER']);
    }

    $msg = str_replace("\\n", "<br>", $msg);

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_PATH.'/confirm.php');
    exit;
}

function get_head_title($title)
{
    return $title;
}
