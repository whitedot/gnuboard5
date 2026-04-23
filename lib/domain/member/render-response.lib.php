<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

class MemberResponseRenderer
{
    public static function alertScript($message)
    {
        if ($message === null || $message === '') {
            return;
        }

        echo '<script>alert(' . json_encode((string) $message) . ');</script>';
    }

    public static function autoPost($action, array $fields, $message = '', $title = '처리중')
    {
        echo MemberTemplateRenderer::capture(
            G5_MEMBER_VIEW_PATH . '/basic/auto_post_form.skin.php',
            array(
                'action' => $action,
                'fields' => $fields,
                'message' => $message,
                'title' => $title,
            )
        );
        exit;
    }
}
