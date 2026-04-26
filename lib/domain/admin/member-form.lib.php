<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// Member form aggregate loader: request parsing, view model, then update/delete flows.
require_once __DIR__ . '/member-form-request.lib.php';
require_once __DIR__ . '/member-form-view.lib.php';
require_once __DIR__ . '/member-form-update.lib.php';
