<?php
$sub_menu = "100100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

admin_require_super_admin($is_admin);

$config = admin_read_config_row();
$request_server = g5_get_runtime_server_input();
$config_form_view = admin_build_config_form_page_view($config, isset($request_server['REMOTE_ADDR']) ? $request_server['REMOTE_ADDR'] : '');

$g5['title'] = $config_form_view['title'];
$admin_container_class = $config_form_view['admin_container_class'];
$admin_page_subtitle = $config_form_view['admin_page_subtitle'];
$config_basic_view = $config_form_view['basic_view'];
$config_join_view = $config_form_view['join_view'];
$config_cert_view = $config_form_view['cert_view'];
$config_mail_view = $config_form_view['mail_view'];
require_once './admin.head.php';
?>

<?php echo admin_render_anchor_menu($config_form_view['pg_anchor_menu_view']); ?>

<form name="fconfigform" id="fconfigform" method="post" action="./config_form_update.php" class="admin-form-layout ui-form-theme ui-form-showcase space-y-5" data-current-user-ip="<?php echo $config_form_view['current_user_ip']; ?>" data-webp-warning="<?php echo $config_form_view['webp_warning']; ?>">
    <input type="hidden" name="token" value="" id="token">

    <?php
    include_once G5_ADMIN_PATH . '/config_form_parts/basic.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/join.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/cert.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/mail.php';
    ?>

    <div id="config_captcha_wrap" class="rounded-lg border border-default-200 bg-default-50 p-4 space-y-3" hidden>
        <h2 class="text-base font-semibold">캡차 입력</h2>
        <?php
        require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
        $captcha_html = captcha_html();
        echo $captcha_html;
        ?>
    </div>

    <div class="admin-form-sticky-actions flex items-center justify-end border-default-300 border-t border-dashed pt-4">
        <button type="submit" class="btn btn-solid-primary" accesskey="s">저장</button>
    </div>
</form>

<?php
require_once './admin.tail.php';
?>
