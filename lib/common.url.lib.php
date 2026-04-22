<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function goto_url($url)
{
    run_event('goto_url', $url);

    if (function_exists('safe_filter_url_host')) {
        $url = safe_filter_url_host($url);
    }

    $url = str_replace("&amp;", "&", $url);

    if (!headers_sent()) {
        header('Location: ' . $url);
    } else {
        echo '<script>';
        echo 'location.replace("' . $url . '");';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
    }
    exit;
}

function login_url($url = '')
{
    if (!$url) {
        $url = G5_URL;
    }

    return urlencode(clean_xss_tags(urldecode($url)));
}

function https_url($dir, $https = true)
{
    if ($https) {
        if (G5_HTTPS_DOMAIN) {
            $url = G5_HTTPS_DOMAIN . '/' . $dir;
        } else {
            $url = G5_URL . '/' . $dir;
        }
    } else {
        if (G5_DOMAIN) {
            $url = G5_DOMAIN . '/' . $dir;
        } else {
            $url = G5_URL . '/' . $dir;
        }
    }

    return $url;
}

function safe_filter_url_host($url)
{
    $regex = run_replace('safe_filter_url_regex', '\\', $url);

    return $regex ? preg_replace('#' . preg_quote($regex, '#') . '#iu', '', $url) : '';
}

function check_url_host($url, $msg = '', $return_url = G5_URL, $is_redirect = false)
{
    if (!$msg) {
        $msg = 'url에 타 도메인을 지정할 수 없습니다.';
    }

    if (run_replace('check_url_host_before', '', $url, $msg, $return_url, $is_redirect) === 'is_checked') {
        return;
    }

    if (preg_match('#\\\0#', $url) || preg_match('/^\/{1,}\\\/', $url)) {
        alert('url 에 올바르지 않은 값이 포함되어 있습니다.');
    }

    if (preg_match('#//[^/@]+@#', $url)) {
        alert('url에 사용자 정보가 포함되어 있어 접근할 수 없습니다.');
    }

    while (($replace_url = preg_replace(array('/\/{2,}/', '/\\@/'), array('//', ''), urldecode($url))) != $url) {
        $url = $replace_url;
    }

    $p = @parse_url(trim(str_replace('\\', '', $url)));
    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
    $is_host_check = false;

    if ($is_redirect && !isset($p['host']) && urldecode($url) != $url) {
        $i = 0;
        while ($i <= 3) {
            $url = urldecode($url);
            if (urldecode($url) == $url) {
                break;
            }
            $i++;
        }

        if (urldecode($url) == $url) {
            $p = @parse_url($url);
        } else {
            $is_host_check = true;
        }
    }

    if ($is_redirect && (isset($p['host']) && $p['host'])) {
        $bool_ch = false;
        foreach (array('user', 'host') as $key) {
            if (isset($p[$key]) && strpbrk($p[$key], ':/?#@')) {
                $bool_ch = true;
            }
        }
        if ($bool_ch) {
            $regex = '/https?\:\/\/' . $host . '/i';
            if (!preg_match($regex, $url)) {
                $is_host_check = true;
            }
        }
    }

    if ((isset($p['scheme']) && $p['scheme']) || (isset($p['host']) && $p['host']) || $is_host_check) {
        if (run_replace('check_same_url_host', (($p['host'] != $host) || $is_host_check), $p, $host, $is_host_check, $return_url, $is_redirect)) {
            echo '<script>' . PHP_EOL;
            echo 'alert("url에 타 도메인을 지정할 수 없습니다.");' . PHP_EOL;
            echo 'document.location.href = "' . $return_url . '";' . PHP_EOL;
            echo '</script>' . PHP_EOL;
            echo '<noscript>' . PHP_EOL;
            echo '<p>' . $msg . '</p>' . PHP_EOL;
            echo '<p><a href="' . $return_url . '">돌아가기</a></p>' . PHP_EOL;
            echo '</noscript>' . PHP_EOL;
            exit;
        }
    }
}

function get_params_merge_url($params, $url = '')
{
    $str_url = $url ? $url : G5_URL;
    $p = @parse_url($str_url);
    $href = (isset($p['scheme']) ? "{$p['scheme']}://" : '')
        . (isset($p['user']) ? $p['user']
        . (isset($p['pass']) ? ":{$p['pass']}" : '') . '@' : '')
        . (isset($p['host']) ? $p['host'] : '')
        . ((isset($p['path']) && $url) ? $p['path'] : '')
        . ((isset($p['port']) && $p['port']) ? ":{$p['port']}" : '');

    $ori_params = '';
    if ($url) {
        $ori_params = !empty($p['query']) ? $p['query'] : '';
    } else if ($tmp = explode('?', $_SERVER['REQUEST_URI'])) {
        if (isset($tmp[0]) && $tmp[0]) {
            $href .= $tmp[0];
            $ori_params = isset($tmp[1]) ? $tmp[1] : '';
        }
        if ($freg = strstr($ori_params, '#')) {
            $p['fragment'] = preg_replace('/^#/', '', $freg);
        }
    }

    $q = array();
    if ($ori_params) {
        parse_str($ori_params, $q);
    }

    if (is_array($params) && $params) {
        $q = array_merge($q, $params);
    }

    $query = http_build_query($q, '', '&amp;');
    $qc = (strpos($href, '?') !== false) ? '&amp;' : '?';
    $href .= $qc . $query . (isset($p['fragment']) ? "#{$p['fragment']}" : '');

    return $href;
}
