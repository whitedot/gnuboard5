<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function build_notice_sanitized_url($url, $msg = '')
{
    $sanitized_url = $url ? clean_xss_tags($url, 1) : '';

    $sanitized_url = preg_replace("/[\<\>\'\"\\\'\\\"\(\)]/", "", $sanitized_url);
    $sanitized_url = preg_replace('/\r\n|\r|\n|[^\x20-\x7e]/', '', $sanitized_url);

    check_url_host($sanitized_url, $msg);

    return $sanitized_url;
}

function build_notice_post_fields(array $post)
{
    $fields = array();

    foreach ($post as $key => $value) {
        $clean_key = clean_xss_tags($key);
        $clean_value = clean_xss_tags($value);

        if (strlen($clean_value) < 1) {
            continue;
        }

        if (preg_match("/pass|pwd|capt|url/", $clean_key)) {
            continue;
        }

        $fields[] = array(
            'name' => htmlspecialchars($clean_key),
            'value' => htmlspecialchars($clean_value),
        );
    }

    return $fields;
}

function get_notice_runtime_post_input()
{
    return function_exists('g5_get_runtime_post_input') ? g5_get_runtime_post_input() : array();
}

function get_notice_runtime_server_input()
{
    return function_exists('g5_get_runtime_server_input') ? g5_get_runtime_server_input() : array();
}

function build_alert_page_view($msg, $url, $error, $post, $header = '', array $post_input = array(), array $server_input = array())
{
    $sanitized_message = $msg ? strip_tags($msg) : '';
    $sanitized_url = build_notice_sanitized_url($url, $sanitized_message);
    $server_input = $server_input ? $server_input : get_notice_runtime_server_input();

    if (!$sanitized_url) {
        $sanitized_url = isset($server_input['HTTP_REFERER']) ? build_notice_sanitized_url($server_input['HTTP_REFERER'], $sanitized_message) : '';
    }

    return array(
        'title' => $error ? '오류안내 페이지' : '결과안내 페이지',
        'header_text' => $error ? '다음 항목에 오류가 있습니다.' : '다음 내용을 확인해 주세요.',
        'message_text' => $sanitized_message,
        'message_html' => str_replace("\\n", "<br>", $sanitized_message),
        'redirect_url' => $sanitized_url,
        'redirect_url_js' => str_replace('&amp;', '&', $sanitized_url),
        'show_post_form' => !empty($post),
        'post_fields' => !empty($post) ? build_notice_post_fields($post_input) : array(),
    );
}

function build_alert_close_page_view($msg, $error, $header = '')
{
    $sanitized_message = $msg ? strip_tags($msg) : '';

    return array(
        'title' => $error ? '오류안내 페이지' : '결과안내 페이지',
        'header_text' => $error ? '다음 항목에 오류가 있습니다.' : '다음 내용을 확인해 주세요.',
        'message_text' => $sanitized_message,
        'message_html' => str_replace("\\n", "<br>", $sanitized_message),
        'description_text' => $error ? '새창을 닫으시고 이전 작업을 다시 시도해 주세요.' : '새창을 닫으신 후 서비스를 이용해 주세요.',
    );
}

function build_confirm_page_view($msg, $header, $url1, $url2, $url3)
{
    $pattern1 = "/[\<\>\'\"\\\'\\\"\(\)]/";
    $pattern2 = "/\r\n|\r|\n|[^\x20-\x7e]/";

    $sanitize_confirm_url = function ($url) use ($pattern1, $pattern2) {
        $sanitized_url = $url ? preg_replace($pattern1, "", clean_xss_tags($url, 1)) : '';
        return preg_replace($pattern2, "", $sanitized_url);
    };

    $confirm_url = $sanitize_confirm_url($url1);
    $cancel_url = $sanitize_confirm_url($url2);
    $back_url = $sanitize_confirm_url($url3);
    $header_text = $header ? $header : $msg;

    check_url_host($confirm_url);
    check_url_host($cancel_url);
    check_url_host($back_url);

    return array(
        'header_text' => get_text(strip_tags($header_text)),
        'message_text' => get_text(strip_tags($msg)),
        'confirm_message' => strip_tags(str_replace("<br>", "\\n", $msg)),
        'confirm_url' => $confirm_url,
        'cancel_url' => $cancel_url,
        'back_url' => $back_url,
    );
}

// 경고메세지를 경고창으로
function alert($msg = '', $url = '', $error = true, $post = false)
{
    global $g5, $config, $member, $is_member, $is_admin;

    run_event('alert', $msg, $url, $error, $post);

    if (function_exists('safe_filter_url_host')) {
        $url = safe_filter_url_host($url);
    }

    $msg = $msg ? strip_tags($msg, '<br>') : '올바른 방법으로 이용해 주십시오.';

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    $alert_view = build_alert_page_view($msg, $url, $error, $post, $header, get_notice_runtime_post_input(), get_notice_runtime_server_input());
    include_once(G5_PATH.'/alert.php');
    exit;
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg, $error = true)
{
    global $g5, $config, $member, $is_member, $is_admin;

    run_event('alert_close', $msg, $error);

    $msg = strip_tags($msg, '<br>');

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    $alert_close_view = build_alert_close_page_view($msg, $error, $header);
    include_once(G5_PATH.'/alert_close.php');
    exit;
}

// confirm 창
function confirm($msg, $url1 = '', $url2 = '', $url3 = '')
{
    global $g5, $config, $member, $is_member, $is_admin;

    if (!$msg) {
        $msg = '올바른 방법으로 이용해 주십시오.';
        alert($msg);
    }

    if (function_exists('safe_filter_url_host')) {
        $url1 = safe_filter_url_host($url1);
        $url2 = safe_filter_url_host($url2);
        $url3 = safe_filter_url_host($url3);
    }

    if (!trim($url1) || !trim($url2)) {
        $msg = '$url1 과 $url2 를 지정해 주세요.';
        alert($msg);
    }

    if (!$url3) {
        $server_input = get_notice_runtime_server_input();
        $url3 = isset($server_input['HTTP_REFERER']) ? clean_xss_tags($server_input['HTTP_REFERER']) : '';
    }

    $msg = str_replace("\\n", "<br>", $msg);

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    $confirm_view = build_confirm_page_view($msg, $header, $url1, $url2, $url3);
    include_once(G5_PATH.'/confirm.php');
    exit;
}

function get_head_title($title)
{
    return $title;
}
