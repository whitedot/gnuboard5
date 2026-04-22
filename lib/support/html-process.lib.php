<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

if (!function_exists('get_called_class')) {
    function get_called_class()
    {
        $bt = debug_backtrace();
        $lines = file($bt[1]['file']);
        preg_match(
            '/([a-zA-Z0-9\_]+)::'.$bt[1]['function'].'/',
            $lines[$bt[1]['line'] - 1],
            $matches
        );

        return $matches[1];
    }
}

function get_html_process_cls()
{
    return html_process::getInstance();
}

function html_end()
{
    return get_html_process_cls()->run();
}

function add_stylesheet($stylesheet, $order = 0)
{
    if (trim($stylesheet)) {
        get_html_process_cls()->merge_stylesheet($stylesheet, $order);
    }
}

function add_javascript($javascript, $order = 0)
{
    if (trim($javascript)) {
        get_html_process_cls()->merge_javascript($javascript, $order);
    }
}

class html_process
{
    protected static $id = '0';
    private static $instances = array();
    protected static $is_end = '0';
    protected static $css = array();
    protected static $js  = array();

    public static function getInstance($id = '0')
    {
        self::$id = $id;
        if (isset(self::$instances[self::$id])) {
            return self::$instances[self::$id];
        }
        $calledClass = get_called_class();

        return self::$instances[self::$id] = new $calledClass;
    }

    public static function merge_stylesheet($stylesheet, $order)
    {
        $links = self::$css;
        $is_merge = true;

        foreach ($links as $link) {
            if ($link[1] == $stylesheet) {
                $is_merge = false;
                break;
            }
        }

        if ($is_merge) {
            self::$css[] = array($order, $stylesheet);
        }
    }

    public static function merge_javascript($javascript, $order)
    {
        $scripts = self::$js;
        $is_merge = true;

        foreach ($scripts as $script) {
            if ($script[1] == $javascript) {
                $is_merge = false;
                break;
            }
        }

        if ($is_merge) {
            self::$js[] = array($order, $javascript);
        }
    }

    public static function run()
    {
        global $g5;

        if (self::$is_end) {
            return;
        }

        self::$is_end = 1;

        $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

        $buffer = ob_get_contents();
        ob_end_clean();

        $stylesheet = '';
        $links = self::$css;

        if (!empty($links)) {
            foreach ($links as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $style[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $links);

            $links = run_replace('html_process_css_files', $links);

            foreach ($links as $link) {
                if (!trim($link[1])) {
                    continue;
                }

                $link[1] = preg_replace('#\.css([\'\"]?>)$#i', '.css?ver='.G5_CSS_VER.'$1', $link[1]);

                $stylesheet .= PHP_EOL.$link[1];
            }
        }

        $javascript = '';
        $scripts = self::$js;
        $php_eol = '';

        unset($order);
        unset($index);

        if (!empty($scripts)) {
            foreach ($scripts as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $scripts);

            $scripts = run_replace('html_process_script_files', $scripts);

            foreach ($scripts as $js) {
                if (!trim($js[1])) {
                    continue;
                }

                $add_version_str = (stripos($js[1], $http_host) !== false) ? '?ver='.G5_JS_VER : '';
                $js[1] = preg_replace('#\.js([\'\"]?>)<\/script>$#i', '.js'.$add_version_str.'$1</script>', $js[1]);

                $javascript .= $php_eol.$js[1];
                $php_eol = PHP_EOL;
            }
        }

        $title_find_pattern = '#(</title>[^<]*<link[^>]+>)#';
        if (preg_match($title_find_pattern, $buffer)) {
            $buffer = preg_replace($title_find_pattern, "$1$stylesheet", $buffer);
        } else {
            $javascript = $stylesheet.PHP_EOL.$javascript;
        }

        $nl = '';
        if ($javascript) {
            $nl = "\n";
        }
        $buffer = preg_replace('#(</head>[^<]*<body[^>]*>)#', "$javascript{$nl}$1", $buffer);

        $meta_tag = run_replace('html_process_add_meta', '');

        if ($meta_tag) {
            $nl = "\n";
            $buffer = preg_replace('#(<title[^>]*>.*?</title>)#', "$meta_tag{$nl}$1", $buffer);
        }

        $buffer = run_replace('html_process_buffer', $buffer);

        return $buffer;
    }
}
