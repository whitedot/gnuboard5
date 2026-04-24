<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_include_page_head($use_sub = false)
{
    global $g5, $config, $member, $is_member, $is_admin, $is_mobile;

    include_once($use_sub ? G5_MEMBER_PATH . '/_head.sub.php' : G5_MEMBER_PATH . '/_head.php');
}

function member_include_page_tail($use_sub = false)
{
    global $g5, $config, $member, $is_member, $is_admin, $is_mobile;

    include_once($use_sub ? G5_MEMBER_PATH . '/_tail.sub.php' : G5_MEMBER_PATH . '/_tail.php');
}

function member_include_layout_head($use_sub = false)
{
    global $g5, $config, $member, $is_member, $is_admin, $is_mobile;

    include_once($use_sub ? G5_PATH . '/head.sub.php' : G5_PATH . '/_head.php');
}

function member_include_layout_tail($use_sub = false)
{
    global $g5, $config, $member, $is_member, $is_admin, $is_mobile;

    include_once($use_sub ? G5_PATH . '/tail.sub.php' : G5_PATH . '/_tail.php');
}
