<?php
define('G5_IS_ADMIN', true);
require_once '../common.php';
require_once G5_ADMIN_PATH . '/admin.lib.php';

if (isset($token)) {
    $token = admin_sanitize_token_value($token);
}

run_event('admin_common');
