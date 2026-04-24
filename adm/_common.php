<?php
define('G5_IS_ADMIN', true);
require_once '../common.php';
require_once G5_ADMIN_PATH . '/admin.lib.php';

if (!empty($g5['request_context']['token'])) {
    $token = admin_sanitize_token_value($g5['request_context']['token']);
}

run_event('admin_common');
