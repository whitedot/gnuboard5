<?php
if (!defined('_GNUBOARD_')) exit;

function is_member_page_request($folder)
{
    if (!preg_match('/^[a-z0-9_.-]+$/i', (string) $folder)) {
        return false;
    }

    return is_file(G5_MEMBER_PATH.'/'.$folder.'.php');
}

function get_page_url($folder)
{
    if (is_member_page_request($folder)) {
        return G5_MEMBER_URL.'/'.$folder.'.php';
    }

    $root_file = G5_PATH.'/'.$folder.'.php';

    if (is_file($root_file)) {
        return G5_URL.'/'.$folder.'.php';
    }

    return G5_URL;
}

function get_pretty_url($folder, $no='', $query_string='', $action='')
{
    $url = '';

    if ($url = run_replace('get_pretty_url', $url, $folder, $no, $query_string, $action)) {
        return $url;
    }

    $url = get_page_url($folder);

    if ($no && $url !== G5_URL) {
        $url .= '?'.$no;
    }

    if ($query_string) {
        $url .= (!$no ? '?' : '&amp;').$query_string;
    }

    return $url;
}

function short_url_clean($string_url, $add_qry='')
{
    $string_url = str_replace('&amp;', '&', $string_url);

    if (!$add_qry) {
        return $string_url;
    }

    return strpos($string_url, '?') === false
        ? $string_url.'?'.$add_qry
        : $string_url.'&'.$add_qry;
}

function correct_goto_url($url)
{
    if (substr($url, -1) !== '/') {
        return $url.'/';
    }

    return $url;
}

function get_nginx_conf_rules($return_string = false)
{
    $get_path_url = parse_url(G5_URL);
    $base_path = isset($get_path_url['path']) ? $get_path_url['path'] . '/' : '/';

    $rules = array();
    $rules[] = '#### ' . G5_VERSION . ' nginx rules BEGIN #####';

    if ($add_rules = run_replace('add_nginx_conf_pre_rules', '', $get_path_url, $base_path, $return_string)) {
        $rules[] = $add_rules;
    }

    $rules[] = 'if (!-e $request_filename) {';

    if ($add_rules = run_replace('add_nginx_conf_rules', '', $get_path_url, $base_path, $return_string)) {
        $rules[] = $add_rules;
    }

    $rules[] = '}';
    $rules[] = '#### ' . G5_VERSION . ' nginx rules END #####';

    return $return_string ? implode("\n", $rules) : $rules;
}

function get_mod_rewrite_rules($return_string = false)
{
    $get_path_url = parse_url(G5_URL);
    $base_path = isset($get_path_url['path']) ? $get_path_url['path'] . '/' : '/';

    $rules = array();
    $rules[] = '#### ' . G5_VERSION . ' rewrite BEGIN #####';
    $rules[] = '<IfModule mod_rewrite.c>';
    $rules[] = 'RewriteEngine On';
    $rules[] = 'RewriteBase ' . $base_path;

    if ($add_rules = run_replace('add_mod_rewrite_pre_rules', '', $get_path_url, $base_path, $return_string)) {
        $rules[] = $add_rules;
    }

    $rules[] = 'RewriteCond %{REQUEST_FILENAME} -f [OR]';
    $rules[] = 'RewriteCond %{REQUEST_FILENAME} -d';
    $rules[] = 'RewriteRule ^ - [L]';

    if ($add_rules = run_replace('add_mod_rewrite_rules', '', $get_path_url, $base_path, $return_string)) {
        $rules[] = $add_rules;
    }

    $rules[] = '</IfModule>';
    $rules[] = '#### ' . G5_VERSION . ' rewrite END #####';

    return $return_string ? implode("\n", $rules) : $rules;
}

function check_need_rewrite_rules()
{
    $is_apache = (stripos($_SERVER['SERVER_SOFTWARE'], 'apache') !== false);

    if ($is_apache) {
        $save_path = G5_PATH.'/.htaccess';

        if (!file_exists($save_path)) {
            return true;
        }

        $rules = get_mod_rewrite_rules();
        $bof_str = $rules[0];
        $eof_str = end($rules);
        $code = file_get_contents($save_path);

        if (strpos($code, $bof_str) === false || strpos($code, $eof_str) === false) {
            return true;
        }
    }

    return false;
}

function update_rewrite_rules()
{
    $is_apache = (stripos($_SERVER['SERVER_SOFTWARE'], 'apache') !== false);

    if ($is_apache) {
        $save_path = G5_PATH.'/.htaccess';

        if ((!file_exists($save_path) && is_writable(G5_PATH)) || is_writable($save_path)) {
            $rules = get_mod_rewrite_rules();
            $bof_str = $rules[0];
            $eof_str = end($rules);

            if (file_exists($save_path)) {
                $code = file_get_contents($save_path);

                if ($code && strpos($code, $bof_str) !== false && strpos($code, $eof_str) !== false) {
                    return true;
                }
            }

            $fp = fopen($save_path, 'ab');
            flock($fp, LOCK_EX);

            $rewrite_str = implode("\n", $rules);

            fwrite($fp, "\n");
            fwrite($fp, $rewrite_str);
            fwrite($fp, "\n");

            flock($fp, LOCK_UN);
            fclose($fp);

            return true;
        }
    }

    return false;
}
