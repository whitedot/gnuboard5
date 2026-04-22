<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_read_ajax_identity_request(array $post)
{
    return array(
        'mb_id' => isset($post['reg_mb_id']) ? trim($post['reg_mb_id']) : '',
        'mb_email' => isset($post['reg_mb_email']) ? trim($post['reg_mb_email']) : '',
        'mb_hp' => isset($post['reg_mb_hp']) ? trim($post['reg_mb_hp']) : '',
        'mb_nick' => isset($post['reg_mb_nick']) ? trim($post['reg_mb_nick']) : '',
    );
}
