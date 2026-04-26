<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// Pure security helper loader. Token issuance/checking lives here; request execution stays in controllers/bootstrap.
require_once __DIR__ . '/auth.lib.php';
require_once __DIR__ . '/token.lib.php';
require_once __DIR__ . '/request-security.lib.php';
