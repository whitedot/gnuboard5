<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_get_runtime_tables()
{
    return function_exists('g5_get_runtime_tables') ? g5_get_runtime_tables() : array();
}

function member_get_runtime_config()
{
    return function_exists('g5_get_runtime_config') ? g5_get_runtime_config() : array();
}

function member_get_member_table_name()
{
    return function_exists('g5_get_runtime_table_name')
        ? g5_get_runtime_table_name('member_table')
        : '';
}
