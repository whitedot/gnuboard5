<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// Pure admin helper loader. Keep this file side-effect free.
// Execution-time access checks and menu setup belong in bootstrap.lib.php.
require_once __DIR__ . '/ui.lib.php';
require_once __DIR__ . '/view-helper.lib.php';
require_once __DIR__ . '/member.lib.php';
require_once __DIR__ . '/config.lib.php';
require_once __DIR__ . '/export.lib.php';
require_once __DIR__ . '/xlsx.lib.php';
