<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberSkinRenderer
{
    public static function capture($skin_path, $template_name, array $data = array())
    {
        return MemberTemplateRenderer::capture(rtrim($skin_path, '/\\') . '/' . $template_name, $data);
    }

    public static function display($skin_path, $template_name, array $data = array())
    {
        echo self::capture($skin_path, $template_name, $data);
    }
}

class MemberMailRenderer
{
    public static function capture($template_name, array $data = array())
    {
        return MemberTemplateRenderer::capture(G5_MEMBER_VIEW_PATH . '/mail/' . $template_name, $data);
    }
}
