<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// 스킨 style sheet 파일 얻기
function get_skin_stylesheet($skin_path, $dir = '')
{
    if (!$skin_path) {
        return "";
    }

    $str = "";
    $files = array();

    if ($dir) {
        $skin_path .= '/'.$dir;
    }

    $skin_url = G5_URL.str_replace("\\", "/", str_replace(G5_PATH, "", $skin_path));

    if (is_dir($skin_path)) {
        if ($dh = opendir($skin_path)) {
            while (($file = readdir($dh)) !== false) {
                if ($file == "." || $file == "..") {
                    continue;
                }

                if (is_dir($skin_path.'/'.$file)) {
                    continue;
                }

                if (preg_match("/\.(css)$/i", $file)) {
                    $files[] = $file;
                }
            }
            closedir($dh);
        }
    }

    if (!empty($files)) {
        sort($files);

        foreach ($files as $file) {
            $str .= '<link rel="stylesheet" href="'.$skin_url.'/'.$file.'?='.date("md").'">'."\n";
        }
    }

    return $str;
}

// 스킨 javascript 파일 얻기
function get_skin_javascript($skin_path, $dir = '')
{
    if (!$skin_path) {
        return "";
    }

    $str = "";
    $files = array();

    if ($dir) {
        $skin_path .= '/'.$dir;
    }

    $skin_url = G5_URL.str_replace("\\", "/", str_replace(G5_PATH, "", $skin_path));

    if (is_dir($skin_path)) {
        if ($dh = opendir($skin_path)) {
            while (($file = readdir($dh)) !== false) {
                if ($file == "." || $file == "..") {
                    continue;
                }

                if (is_dir($skin_path.'/'.$file)) {
                    continue;
                }

                if (preg_match("/\.(js)$/i", $file)) {
                    $files[] = $file;
                }
            }
            closedir($dh);
        }
    }

    if (!empty($files)) {
        sort($files);

        foreach ($files as $file) {
            $str .= '<script src="'.$skin_url.'/'.$file.'"></script>'."\n";
        }
    }

    return $str;
}
