<?php
if (!defined('_GNUBOARD_')) exit;

// Legacy common loader. Keep include order stable; add new shared implementation to lib/support or lib/bootstrap first.

// Utility functions (basic helpers, microtime, etc)
include_once(G5_LIB_PATH.'/common.util.lib.php');

// Session and Cookie handling
include_once(G5_LIB_PATH.'/common.session.lib.php');

// Mobile detection
include_once(G5_LIB_PATH.'/common.mobile.lib.php');

// Support-layer wrappers for shared utilities
include_once(G5_LIB_PATH.'/support/sql.lib.php');
include_once(G5_LIB_PATH.'/support/string.lib.php');
include_once(G5_LIB_PATH.'/support/file.lib.php');
include_once(G5_LIB_PATH.'/common.html.lib.php');

// Certification/history functions - requires SQL
include_once(G5_LIB_PATH.'/common.cert.lib.php');

// Network and URL helpers
include_once(G5_LIB_PATH.'/common.net.lib.php');

// External module checks
include_once(G5_LIB_PATH.'/common.module.lib.php');

// Cryptography (password, encryption) - requires SQL
include_once(G5_LIB_PATH.'/common.crypto.lib.php');

// Data entities (Member, Content access) - requires SQL
include_once(G5_LIB_PATH.'/common.data.lib.php');
