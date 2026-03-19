<?php
$script = basename($_SERVER['SCRIPT_NAME']);
$target_path = __DIR__ . '/../member/' . $script;

if (!is_file($target_path)) {
    http_response_code(404);
    exit;
}

$location = '../member/' . $script;
if (!empty($_SERVER['QUERY_STRING'])) {
    $location .= '?' . $_SERVER['QUERY_STRING'];
}

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
$status = in_array($method, array('GET', 'HEAD'), true) ? 301 : 307;

header('Location: ' . $location, true, $status);
exit;
