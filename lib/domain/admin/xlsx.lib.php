<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// XLSX aggregate loader: archive writer first, then spreadsheet XML writer.
require_once __DIR__ . '/archive.lib.php';
require_once __DIR__ . '/xlsx-writer.lib.php';
