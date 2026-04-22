<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberSkinHookController
{
    public static function includeOptional($skin_path, $template_name)
    {
        $template_path = rtrim($skin_path, '/\\') . '/' . $template_name;

        if (is_file($template_path)) {
            include_once($template_path);
        }
    }
}
