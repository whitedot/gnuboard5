<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberPageController
{
    protected static function getTemplateMap()
    {
        return array(
            'login' => 'login.skin.php',
            'member_cert_refresh' => 'member_cert_refresh.skin.php',
            'member_confirm' => 'member_confirm.skin.php',
            'password_lost' => 'password_lost.skin.php',
            'password_reset' => 'password_reset.skin.php',
            'register' => 'register.skin.php',
            'register_email' => 'register_email.skin.php',
            'register_form' => 'register_form.skin.php',
            'register_result' => 'register_result.skin.php',
        );
    }

    protected static function resolveTemplateName($page_key)
    {
        $template_map = self::getTemplateMap();

        if (!isset($template_map[$page_key])) {
            throw new InvalidArgumentException('Unknown member page template key: ' . $page_key);
        }

        return $template_map[$page_key];
    }

    protected static function normalizePageView(array $page_view)
    {
        return array(
            'title' => isset($page_view['title']) ? $page_view['title'] : '',
            'data' => isset($page_view['data']) ? $page_view['data'] : array(),
            'options' => isset($page_view['options']) ? $page_view['options'] : array(),
        );
    }

    public static function renderPage($page_key, array $page_view)
    {
        $normalized_page_view = self::normalizePageView($page_view);

        self::render(
            $normalized_page_view['title'],
            self::resolveTemplateName($page_key),
            $normalized_page_view['data'],
            $normalized_page_view['options']
        );
    }

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
