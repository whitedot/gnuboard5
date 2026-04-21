<?php
if (!defined('_GNUBOARD_')) exit;

// url에 http:// 를 붙인다
function set_http($url, $protocol="http://")
{
    if (!trim($url)) {
        return;
    }

    if (!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url)) {
        $url = $protocol . $url;
    }

    return $url;
}

// goo.gl 짧은주소 만들기
function googl_short_url($longUrl)
{
    return function_exists('run_replace') ? run_replace('googl_short_url', $longUrl) : $longUrl;
}

// get_sock 함수 대체
if (!function_exists("get_sock")) {
    function get_sock($url, $timeout=30)
    {
        if (preg_match("/http:\/\/([a-zA-Z0-9_\-\.]+)([^<]*)/", $url, $res)) {
            $host = $res[1];
            $get  = $res[2];
        }

        $header = '';

        $fp = fsockopen($host, 80, $errno, $errstr, $timeout);
        if (!$fp) {
            echo "$errstr ($errno)\n";
            return null;
        }

        fputs($fp, "GET $get HTTP/1.0\r\n");
        fputs($fp, "Host: $host\r\n");
        fputs($fp, "\r\n");

        while (trim($buffer = fgets($fp,1024)) != "") {
            $header .= $buffer;
        }
        while (!feof($fp)) {
            $buffer .= fgets($fp,1024);
        }

        fclose($fp);

        return $buffer;
    }
}
