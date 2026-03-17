<?php
if (!defined('_GNUBOARD_')) exit;

class G5_URI {
    public $basename;
    public $parts;
    public $slashes;

    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    public function parseURL() {
        $uri = $_SERVER['REQUEST_URI'];
        $script = $_SERVER['SCRIPT_NAME'];
        $script_names = explode('.', $script);
        $ext = end($script_names);

        if (strstr($uri, '.')) {
            $arr_uri = explode('.', $uri);
            $last = end($arr_uri);

            if ($last == $ext) {
                array_pop($arr_uri);
                $uri = implode('.', $arr_uri);
            }
        }

        $basename = basename($script, '.'.$ext);
        $temp = explode('/', $uri);
        $key = array_search($basename, $temp);
        $parts = array_slice($temp, $key + 1);
        $this->basename = $basename;
        $this->parts = $parts;
    }

    public function setRelative($relativevar) {
        $numslash = count($this->parts);
        $slashes = '';
        for ($i = 0; $i < $numslash; $i++) {
            $slashes .= '../';
        }
        $this->slashes = $slashes;

        $links = array();
        $links[$relativevar] = $slashes;

        return $links;
    }

    public function getParts() {
        return $this->parts;
    }

    public function setParts() {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        $urlparts = $this->getParts();
        $links = array();
        for ($i = 0; $i < $numargs; $i++) {
            $links[$arg_list[$i]] = $urlparts[$i];
        }

        return $links;
    }

    public function makeClean($string_url) {
        $url = parse_url($string_url);
        $strUrl = basename($url['path'], '.php');
        parse_str(isset($url['query']) ? $url['query'] : '', $queryString);
        foreach ($queryString as $value) {
            $strUrl .= "/$value";
        }
        return $strUrl;
    }

    public function url_clean($string_url, $add_qry='') {
        $string_url = str_replace('&amp;', '&', $string_url);

        if (!$add_qry) {
            return $string_url;
        }

        return strpos($string_url, '?') === false
            ? $string_url.'?'.$add_qry
            : $string_url.'&'.$add_qry;
    }
}
