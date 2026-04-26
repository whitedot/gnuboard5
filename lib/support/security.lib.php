<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function g5_get_support_security_get_input()
{
    return function_exists('g5_get_runtime_get_input') ? g5_get_runtime_get_input() : array();
}

function g5_get_support_security_post_input()
{
    return function_exists('g5_get_runtime_post_input') ? g5_get_runtime_post_input() : array();
}

function g5_get_support_security_cookie_input()
{
    return function_exists('g5_get_runtime_cookie_input') ? g5_get_runtime_cookie_input() : array();
}

function g5_get_support_security_server_input()
{
    return function_exists('g5_get_runtime_server_input') ? g5_get_runtime_server_input() : array();
}

function check_input_vars()
{
    $max_input_vars = ini_get('max_input_vars');

    if ($max_input_vars) {
        $post_vars = count(g5_get_support_security_post_input(), COUNT_RECURSIVE);
        $get_vars = count(g5_get_support_security_get_input(), COUNT_RECURSIVE);
        $cookie_vars = count(g5_get_support_security_cookie_input(), COUNT_RECURSIVE);

        $input_vars = $post_vars + $get_vars + $cookie_vars;

        if ($input_vars > $max_input_vars) {
            alert('폼에서 전송된 변수의 개수가 max_input_vars 값보다 큽니다.\\n전송된 값중 일부는 유실되어 DB에 기록될 수 있습니다.\\n\\n문제를 해결하기 위해서는 서버 php.ini의 max_input_vars 값을 변경하십시오.');
        }
    }
}

function check_vaild_callback($callback)
{
    $_callback = preg_replace('/[^0-9]/', '', $callback);

    if (substr($_callback, 0, 4) == '1588' && strlen($_callback) != 8) {
        return false;
    }
    if (substr($_callback, 0, 2) == '02' && strlen($_callback) != 9 && strlen($_callback) != 10) {
        return false;
    }
    if (substr($_callback, 0, 3) == '030' && strlen($_callback) != 10 && strlen($_callback) != 11) {
        return false;
    }

    if (!preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$/", $_callback)
        && !preg_match("/^(15|16|18)\d{2}\-?\d{4,5}$/", $_callback)) {
        return false;
    } elseif (preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080)\-?0{3,4}\-?\d{4}$/", $_callback)) {
        return false;
    } else {
        return true;
    }
}

function get_real_client_ip()
{
    $server_input = g5_get_support_security_server_input();
    $remote_addr = isset($server_input['REMOTE_ADDR']) ? $server_input['REMOTE_ADDR'] : '';

    return run_replace('get_real_client_ip', $remote_addr);
}

function check_mail_bot($ip = '')
{
    $check_ips = array('211.249.40.');
    $bot_message = 'bot 으로 판단되어 중지합니다.';

    if ($ip) {
        foreach ($check_ips as $c_ip) {
            if (preg_match('/^' . preg_quote($c_ip) . '/', $ip)) {
                die($bot_message);
            }
        }
    }

    $server_input = g5_get_support_security_server_input();
    $user_agent = isset($server_input['HTTP_USER_AGENT']) ? $server_input['HTTP_USER_AGENT'] : '';
    if ($user_agent === 'Carbon' || strpos($user_agent, 'BingPreview') !== false || strpos($user_agent, 'Slackbot') !== false) {
        die($bot_message);
    }
}

function is_include_path_check($path = '', $is_input = '')
{
    if ($path) {
        if (strlen($path) > 255) {
            return false;
        }

        if ($is_input) {
            if (stripos($path, 'rar:') !== false || stripos($path, 'php:') !== false || stripos($path, 'zlib:') !== false || stripos($path, 'bzip2:') !== false || stripos($path, 'zip:') !== false || stripos($path, 'data:') !== false || stripos($path, 'phar:') !== false || stripos($path, 'file:') !== false || stripos($path, '://') !== false) {
                return false;
            }

            $replace_path = str_replace('\\', '/', $path);
            $server_input = g5_get_support_security_server_input();
            $script_name = isset($server_input['SCRIPT_NAME']) ? $server_input['SCRIPT_NAME'] : '';
            $slash_count = substr_count(str_replace('\\', '/', $script_name), '/');
            $peer_count = substr_count($replace_path, '../');

            if ($peer_count && $peer_count > $slash_count) {
                return false;
            }

            $dirname_doc_root = !empty($server_input['DOCUMENT_ROOT']) ? dirname($server_input['DOCUMENT_ROOT']) : dirname(dirname(dirname(__DIR__)));

            if ($dirname_doc_root && file_exists($path) && strpos(realpath($path), realpath($dirname_doc_root)) !== 0) {
                return false;
            }

            try {
                $unipath = strlen($path) == 0 || substr($path, 0, 1) != '/';
                $unc = substr($path, 0, 2) == '\\\\' ? true : false;
                if (strpos($path, ':') === false && $unipath && !$unc) {
                    $path = getcwd() . DIRECTORY_SEPARATOR . $path;
                    if (substr($path, 0, 1) == '/') {
                        $unipath = false;
                    }
                }

                $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
                $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
                $absolutes = array();
                foreach ($parts as $part) {
                    if ('.' == $part) {
                        continue;
                    }
                    if ('..' == $part) {
                        array_pop($absolutes);
                    } else {
                        $absolutes[] = $part;
                    }
                }
                $path = implode(DIRECTORY_SEPARATOR, $absolutes);
                $path = !$unipath ? '/' . $path : $path;
                $path = $unc ? '\\\\' . $path : $path;
            } catch (Exception $e) {
                return false;
            }

            if (preg_match('/\/data\/(file|editor|cache|member|member_image|session|tmp)\/[A-Za-z0-9_]{1,20}\//i', $replace_path) || preg_match('/pe(?:ar|cl)(?:cmd)?\.php/i', $replace_path)) {
                return false;
            }
            if (preg_match('/' . G5_PLUGIN_DIR . '\//i', $replace_path) && preg_match('/' . G5_KCPCERT_DIR . '\//i', $replace_path) || preg_match('/search\.skin\.php/i', $replace_path)) {
                return false;
            }
            if (substr_count($replace_path, './') > 5) {
                return false;
            }
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension && preg_match('/(jpg|jpeg|png|gif|bmp|conf|php\-x)$/i', $extension)) {
            return false;
        }
    }

    return true;
}

function is_inicis_url_return($url)
{
    $url_data = parse_url($url);

    if (isset($url_data['host']) && preg_match('#\.inicis\.com$#i', $url_data['host'])) {
        return $url;
    }
    return '';
}

function filter_input_include_path($path)
{
    return str_replace('//', '/', strip_tags($path));
}
