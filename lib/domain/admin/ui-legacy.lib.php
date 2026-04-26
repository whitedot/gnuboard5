<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function get_subdirectory_names($directory_path)
{
    $result_array = array();
    if (!is_dir($directory_path)) {
        return array();
    }

    $handle = opendir($directory_path);
    if ($handle === false) {
        return array();
    }

    while ($file = readdir($handle)) {
        if ($file == '.' || $file == '..') {
            continue;
        }

        if (is_dir($directory_path . '/' . $file)) {
            $result_array[] = $file;
        }
    }
    closedir($handle);
    sort($result_array);

    return $result_array;
}

function rm_rf($file)
{
    if (file_exists($file)) {
        if (is_dir($file)) {
            $handle = opendir($file);
            while ($filename = readdir($handle)) {
                if ($filename != '.' && $filename != '..') {
                    rm_rf($file . '/' . $filename);
                }
            }
            closedir($handle);
            @chmod($file, G5_DIR_PERMISSION);
            @rmdir($file);
        } else {
            @chmod($file, G5_FILE_PERMISSION);
            @unlink($file);
        }
    }
}

function domain_mail_host($is_at = true)
{
    $server_input = function_exists('g5_get_runtime_server_input') ? g5_get_runtime_server_input() : array();
    $http_host = isset($server_input['HTTP_HOST']) ? (string) $server_input['HTTP_HOST'] : '';
    list($domain_host,) = explode(':', $http_host);

    if ('www.' === substr($domain_host, 0, 4)) {
        $domain_host = substr($domain_host, 4);
    }

    return $is_at ? '@' . $domain_host : $domain_host;
}

function check_log_folder($log_path, $is_delete = true)
{
    if (is_writable($log_path)) {
        $htaccess_file = $log_path . '/.htaccess';
        if (!file_exists($htaccess_file) && ($handle = @fopen($htaccess_file, 'w'))) {
            fwrite($handle, 'Order deny,allow' . "\n");
            fwrite($handle, 'Deny from all' . "\n");
            fclose($handle);
        }

        $index_file = $log_path . '/index.php';
        if (!file_exists($index_file) && ($handle = @fopen($index_file, 'w'))) {
            fwrite($handle, '');
            fclose($handle);
        }
    }

    if ($is_delete) {
        try {
            $del_files = array_merge(glob($log_path . '/*.txt'), glob($log_path . '/*.log'));
            if ($del_files && is_array($del_files)) {
                foreach ($del_files as $del_file) {
                    $filetime = filemtime($del_file);
                    if ($filetime && $filetime < (G5_SERVER_TIME - 2592000)) {
                        @unlink($del_file);
                    }
                }
            }
        } catch (Exception $e) {
        }
    }
}

function admin_enqueue_extend_stylesheets()
{
    $files = glob(G5_ADMIN_PATH . '/css/admin_extend_*');
    if (!is_array($files)) {
        return;
    }

    foreach ((array) $files as $k => $css_file) {
        $fileinfo = pathinfo($css_file);
        $ext = isset($fileinfo['extension']) ? $fileinfo['extension'] : '';
        if ($ext !== 'css') {
            continue;
        }

        $css_file = str_replace(G5_ADMIN_PATH, G5_ADMIN_URL, $css_file);
        add_stylesheet('<link rel="stylesheet" href="' . $css_file . '">', $k);
    }
}

function admin_register_menu_group(array &$menu, $group_key, array $items)
{
    $menu[$group_key] = $items;
}

function admin_file_version($path, $fallback_version)
{
    return is_file($path) ? filemtime($path) : $fallback_version;
}
