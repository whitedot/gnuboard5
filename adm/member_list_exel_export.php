<?php
$sub_menu = '200400';
require_once './_common.php';
require_once './member_list_exel.lib.php';

check_demo();

$stream_context = admin_complete_member_export_stream_request($_GET, $auth, $sub_menu);
$params = $stream_context['params'];

try {
    admin_run_member_export($params);
} catch (Exception $e) {
    error_log('[Member Export Error] ' . $e->getMessage());
    member_export_send_progress('error', $e->getMessage());
    member_export_write_log($params, array(
        'success' => false,
        'error' => $e->getMessage(),
    ));
}
