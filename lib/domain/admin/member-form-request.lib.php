<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_read_member_form_request(array $request)
{
    return array(
        'w' => isset($request['w']) && !is_array($request['w']) ? substr(trim((string) $request['w']), 0, 1) : '',
        'mb_id' => isset($request['mb_id']) && !is_array($request['mb_id']) ? trim((string) $request['mb_id']) : '',
    );
}

function admin_read_member_form_update_request(array $post, array $config)
{
    return array(
        'form' => admin_read_member_form_request($post),
        'member' => member_read_admin_member_request($post),
        'list_qstr' => admin_build_member_list_qstr($post, $config),
    );
}

function admin_read_member_delete_request(array $post)
{
    return array(
        'mb_id' => isset($post['mb_id']) ? trim((string) $post['mb_id']) : '',
    );
}

function admin_read_member_delete_action_request(array $post, array $config)
{
    return array(
        'delete' => admin_read_member_delete_request($post),
        'list_qstr' => admin_build_member_list_qstr($post, $config),
    );
}
