<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_validate_ajax_mb_id(array $request)
{
    if ($msg = empty_mb_id($request['mb_id'])) die($msg);
    if ($msg = valid_mb_id($request['mb_id'])) die($msg);
    if ($msg = count_mb_id($request['mb_id'])) die($msg);
    if ($msg = exist_mb_id($request['mb_id'])) die($msg);
    if ($msg = reserve_mb_id($request['mb_id'])) die($msg);
}

function member_validate_ajax_mb_email(array $request)
{
    if ($msg = empty_mb_email($request['mb_email'])) die($msg);
    if ($msg = valid_mb_email($request['mb_email'])) die($msg);
    if ($msg = prohibit_mb_email($request['mb_email'])) die($msg);
    if ($msg = exist_mb_email($request['mb_email'], $request['mb_id'])) die($msg);
}

function member_validate_ajax_mb_hp(array $request)
{
    if ($msg = valid_mb_hp($request['mb_hp'])) die($msg);
}

function member_validate_ajax_mb_nick(array $request)
{
    if ($msg = empty_mb_nick($request['mb_nick'])) die($msg);
    if ($msg = valid_mb_nick($request['mb_nick'])) die($msg);
    if ($msg = count_mb_nick($request['mb_nick'])) die($msg);
    if ($msg = exist_mb_nick($request['mb_nick'], $request['mb_id'])) die($msg);
    if ($msg = reserve_mb_nick($request['mb_nick'])) die($msg);
}
