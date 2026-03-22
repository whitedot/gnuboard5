<?php
if (!defined('_GNUBOARD_')) exit;

class MemberPageController
{
    public static function render($title, $template_name, array $data = array(), array $options = array())
    {
        global $g5, $member_skin_path;

        $g5['title'] = $title;

        $skin_path = isset($options['skin_path']) ? $options['skin_path'] : $member_skin_path;
        $use_sub = !empty($options['sub']);

        if ($use_sub) {
            include_once(G5_MEMBER_PATH.'/_head.sub.php');
        } else {
            include_once(G5_MEMBER_PATH.'/_head.php');
        }

        MemberSkinRenderer::display($skin_path, $template_name, $data);

        if (!empty($options['after_event'])) {
            $args = isset($options['after_args']) ? $options['after_args'] : array();
            call_user_func_array('run_event', array_merge(array($options['after_event']), $args));
        }

        if ($use_sub) {
            include_once(G5_MEMBER_PATH.'/_tail.sub.php');
        } else {
            include_once(G5_MEMBER_PATH.'/_tail.php');
        }
    }
}

class MemberSkinHookController
{
    public static function includeOptional($skin_path, $template_name)
    {
        $template_path = rtrim($skin_path, '/\\').'/'.$template_name;

        if (is_file($template_path)) {
            include_once($template_path);
        }
    }
}
