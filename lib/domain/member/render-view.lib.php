<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberViewRenderer
{
    public static function capture($view_path, $template_name, array $data = array())
    {
        return MemberTemplateRenderer::capture(rtrim($view_path, '/\\') . '/' . $template_name, $data);
    }

    public static function display($view_path, $template_name, array $data = array())
    {
        echo self::capture($view_path, $template_name, $data);
    }
}

class MemberMailRenderer
{
    public static function capture($template_name, array $data = array())
    {
        return MemberTemplateRenderer::capture(G5_MEMBER_VIEW_PATH . '/mail/' . $template_name, $data);
    }
}
