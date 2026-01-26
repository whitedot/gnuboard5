<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * Common Library Modules
 * 
 * The functions previously contained in this file have been moved to separate module files
 * in the lib/ directory for better maintainability and organization.
 */

// Utility functions (basic helpers, microtime, etc)
include_once(G5_LIB_PATH.'/common.util.lib.php');

// Session and Cookie handling
include_once(G5_LIB_PATH.'/common.session.lib.php');

// Mobile device detection
include_once(G5_LIB_PATH.'/common.mobile.lib.php');

// Database functions (SQL)
include_once(G5_LIB_PATH.'/common.sql.lib.php');

// String manipulation (text, cleaning, conversion)
include_once(G5_LIB_PATH.'/common.string.lib.php');

// Cryptography (password, encryption) - requires SQL
include_once(G5_LIB_PATH.'/common.crypto.lib.php');

// File handling (upload, download, view) - requires SQL
include_once(G5_LIB_PATH.'/common.file.lib.php');

// Data entities (Member, Board, Group access) - requires SQL
include_once(G5_LIB_PATH.'/common.data.lib.php');

// Point system - requires SQL
include_once(G5_LIB_PATH.'/common.point.lib.php');

// HTML/UI functions (paging, alerts, selects) - requires Data/SQL
include_once(G5_LIB_PATH.'/common.html.lib.php');
