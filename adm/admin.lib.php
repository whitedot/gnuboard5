<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// Admin domain entrypoint. Load pure helpers first, then run the bootstrap side effects.
require_once G5_LIB_PATH . '/domain/admin/helper.lib.php';
require_once G5_LIB_PATH . '/domain/admin/security.lib.php';
require_once G5_LIB_PATH . '/domain/admin/bootstrap.lib.php';
