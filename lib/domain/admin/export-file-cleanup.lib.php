<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_ensure_member_export_directory($dir)
{
    if (!is_dir($dir)) {
        if (!@mkdir($dir, G5_DIR_PERMISSION, true)) {
            throw new Exception('디렉토리 생성 실패');
        }
        @chmod($dir, G5_DIR_PERMISSION);
    }

    if (!is_writable($dir)) {
        throw new Exception('디렉토리 쓰기 권한 없음');
    }
}

function admin_delete_member_export_files($file_list = array())
{
    $count = 0;

    if (!empty($file_list)) {
        foreach ($file_list as $file) {
            $file_path = rtrim(ADMIN_MEMBER_EXPORT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
            if (file_exists($file_path) && is_file($file_path) && @unlink($file_path)) {
                $count++;
            }
        }

        return $count;
    }

    $files = glob(rtrim(G5_DATA_PATH . '/' . ADMIN_MEMBER_EXPORT_BASE_DIR, '/') . '/*');
    if (!is_array($files)) {
        $files = array();
    }

    foreach ($files as $file) {
        $name = basename($file);
        if ($name === 'log' || preg_match('/^' . date('Ymd') . '\d{6}$/', $name)) {
            continue;
        }

        if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) !== 'log' && @unlink($file)) {
            $count++;
            continue;
        }

        if (is_dir($file)) {
            admin_delete_member_export_directory($file);
            $count++;
        }
    }

    return $count;
}

function admin_delete_member_export_directory($dir)
{
    foreach (glob($dir . '/{.,}*', GLOB_BRACE) as $item) {
        if (in_array(basename($item), array('.', '..'), true)) {
            continue;
        }

        if (is_dir($item)) {
            admin_delete_member_export_directory($item);
            continue;
        }

        unlink($item);
    }

    rmdir($dir);
}
