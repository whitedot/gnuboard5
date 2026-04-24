<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function member_get_runtime_request_context()
{
    $context = g5_get_runtime_request_context();

    return array(
        'get' => isset($_GET) && is_array($_GET) ? $_GET : array(),
        'post' => isset($_POST) && is_array($_POST) ? $_POST : array(),
        'request' => isset($context['request']) && is_array($context['request']) ? $context['request'] : array(),
        'query_state' => isset($context['query_state']) && is_array($context['query_state']) ? $context['query_state'] : array(),
        'session' => isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array(),
    );
}

function member_read_session_value(array $session, $key, $default = '')
{
    if (!array_key_exists($key, $session) || is_array($session[$key])) {
        return $default;
    }

    return trim((string) $session[$key]);
}

function member_get_runtime_session_snapshot()
{
    $context = member_get_runtime_request_context();

    return isset($context['session']) && is_array($context['session']) ? $context['session'] : array();
}

function member_read_certification_session_state(array $session = array())
{
    if (empty($session)) {
        $session = member_get_runtime_session_snapshot();
    }

    return array(
        'cert_mb_id' => member_read_session_value($session, 'ss_cert_mb_id'),
        'cert_no' => member_read_session_value($session, 'ss_cert_no'),
        'cert_hash' => member_read_session_value($session, 'ss_cert_hash'),
        'cert_type' => member_read_session_value($session, 'ss_cert_type'),
        'cert_birth' => member_read_session_value($session, 'ss_cert_birth'),
        'cert_adult' => member_read_session_value($session, 'ss_cert_adult'),
        'cert_sex' => member_read_session_value($session, 'ss_cert_sex'),
        'cert_dupinfo' => member_read_session_value($session, 'ss_cert_dupinfo'),
    );
}

function member_read_register_validation_session_state(array $session = array())
{
    if (empty($session)) {
        $session = member_get_runtime_session_snapshot();
    }

    return array(
        'checked_mb_id' => member_read_session_value($session, 'ss_check_mb_id'),
        'checked_mb_nick' => member_read_session_value($session, 'ss_check_mb_nick'),
        'checked_mb_email' => member_read_session_value($session, 'ss_check_mb_email'),
        'current_mb_id' => member_read_session_value($session, 'ss_mb_id'),
        'registered_mb_name' => member_read_session_value($session, 'ss_reg_mb_name'),
        'registered_mb_hp' => member_read_session_value($session, 'ss_reg_mb_hp'),
    );
}

function member_clear_session_keys(array $session_keys)
{
    foreach ($session_keys as $session_key) {
        set_session($session_key, '');
    }
}

function member_read_request_w(array $source, array $query_state)
{
    if (isset($source['w']) && !is_array($source['w'])) {
        return substr((string) $source['w'], 0, 2);
    }

    return isset($query_state['w']) && !is_array($query_state['w']) ? (string) $query_state['w'] : '';
}

function member_read_request_url(array $source, array $query_state)
{
    if (isset($source['url']) && !is_array($source['url'])) {
        return trim((string) $source['url']);
    }

    return isset($query_state['url']) && !is_array($query_state['url']) ? (string) $query_state['url'] : '';
}

function member_read_current_url_encoded(array $query_state)
{
    return isset($query_state['urlencode']) && !is_array($query_state['urlencode']) ? (string) $query_state['urlencode'] : '';
}

require_once __DIR__ . '/request-register.lib.php';
require_once __DIR__ . '/request-auth.lib.php';
require_once __DIR__ . '/request-account.lib.php';
require_once __DIR__ . '/request-ajax.lib.php';
