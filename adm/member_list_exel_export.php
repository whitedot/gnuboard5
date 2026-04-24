<?php
$sub_menu = '200400';
require_once './_common.php';
require_once './member_list_exel.lib.php';

check_demo();

$member_export_runtime = admin_build_member_export_runtime_context($g5, isset($member) && is_array($member) ? $member : array());
$stream_context = admin_complete_member_export_stream_request($_GET, $auth, $sub_menu, $member_export_runtime);
$params = $stream_context['params'];
$runtime = $stream_context['runtime'];

try {
    admin_run_member_export($params, $runtime);
} catch (Exception $e) {
    error_log('[Member Export Error] ' . $e->getMessage());
    member_export_send_progress('error', $e->getMessage());
    member_export_write_log($params, array(
        'success' => false,
        'error' => $e->getMessage(),
    ), $runtime['actor_id']);
}
