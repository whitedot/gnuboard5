<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

if (!defined('ADMIN_MEMBER_EXPORT_PAGE_SIZE')) {
    define('ADMIN_MEMBER_EXPORT_PAGE_SIZE', 10000);
}
if (!defined('ADMIN_MEMBER_EXPORT_MAX_SIZE')) {
    define('ADMIN_MEMBER_EXPORT_MAX_SIZE', 300000);
}
if (!defined('ADMIN_MEMBER_EXPORT_BASE_DIR')) {
    define('ADMIN_MEMBER_EXPORT_BASE_DIR', 'member_list');
}
if (!defined('ADMIN_MEMBER_EXPORT_BASE_DATE')) {
    define('ADMIN_MEMBER_EXPORT_BASE_DATE', date('YmdHis'));
}
if (!defined('ADMIN_MEMBER_EXPORT_DIR')) {
    define('ADMIN_MEMBER_EXPORT_DIR', G5_DATA_PATH . '/' . ADMIN_MEMBER_EXPORT_BASE_DIR . '/' . ADMIN_MEMBER_EXPORT_BASE_DATE);
}
if (!defined('ADMIN_MEMBER_EXPORT_LOG_DIR')) {
    define('ADMIN_MEMBER_EXPORT_LOG_DIR', G5_DATA_PATH . '/' . ADMIN_MEMBER_EXPORT_BASE_DIR . '/log');
}

// Export config aggregate loader: options, request parsing, and SQL filter helpers.
require_once __DIR__ . '/export-options.lib.php';
require_once __DIR__ . '/export-request.lib.php';
require_once __DIR__ . '/export-filter.lib.php';
