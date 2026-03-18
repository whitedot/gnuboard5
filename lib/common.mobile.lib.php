<?php
if (!defined('_GNUBOARD_')) exit;

function is_mobile()
{
    if (isset($_SERVER['HTTP_USER_AGENT']))
        return  preg_match('/'.G5_MOBILE_AGENT.'/i', $_SERVER['HTTP_USER_AGENT']);
    else
        return '';
}
