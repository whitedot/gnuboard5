<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberPageController
{
    public static function render($title, $template_name, array $data = array(), array $options = array())
    {
        global $g5, $config, $member, $is_member, $is_admin, $member_view_path;

        $g5['title'] = $title;

        $view_path = isset($options['view_path']) ? $options['view_path'] : $member_view_path;
        $use_sub = !empty($options['sub']);

        member_include_page_head($use_sub);

        MemberViewRenderer::display($view_path, $template_name, $data);

        if (!empty($options['after_event'])) {
            $args = isset($options['after_args']) ? $options['after_args'] : array();
            call_user_func_array('run_event', array_merge(array($options['after_event']), $args));
        }

        member_include_page_tail($use_sub);
    }
}
