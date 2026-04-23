<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberViewHookController
{
    public static function includeOptional($view_path, $template_name)
    {
        $template_path = rtrim($view_path, '/\\') . '/' . $template_name;

        if (is_file($template_path)) {
            include_once($template_path);
        }
    }
}
