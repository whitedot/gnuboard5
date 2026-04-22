<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_reset_ajax_identity_checks(array $fields)
{
    foreach ($fields as $field) {
        set_session($field, '');
    }
}

function member_store_ajax_mb_id_check(array $request)
{
    set_session('ss_check_mb_id', $request['mb_id']);
}

function member_store_ajax_mb_email_check(array $request)
{
    set_session('ss_check_mb_email', $request['mb_email']);
}

function member_store_ajax_mb_nick_check(array $request)
{
    set_session('ss_check_mb_nick', $request['mb_nick']);
}

function member_process_ajax_mb_id(array $request)
{
    member_reset_ajax_identity_checks(array('ss_check_mb_id'));
    member_validate_ajax_mb_id($request);
    member_store_ajax_mb_id_check($request);
}

function member_process_ajax_mb_email(array $request)
{
    member_reset_ajax_identity_checks(array('ss_check_mb_email'));
    member_validate_ajax_mb_email($request);
    member_store_ajax_mb_email_check($request);
}

function member_process_ajax_mb_hp(array $request)
{
    member_validate_ajax_mb_hp($request);
}

function member_process_ajax_mb_nick(array $request)
{
    member_reset_ajax_identity_checks(array('ss_check_mb_nick'));
    member_validate_ajax_mb_nick($request);
    member_store_ajax_mb_nick_check($request);
}
