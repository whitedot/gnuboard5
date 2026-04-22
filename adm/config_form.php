<?php
$sub_menu = "100100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

admin_require_super_admin($is_admin);

$config = admin_read_config_row();
$config_form_view = admin_build_config_form_page_view();
extract($config_form_view, EXTR_SKIP);

$g5['title'] = $title;
require_once './admin.head.php';
?>

<?php echo $pg_anchor_menu; ?>

<form name="fconfigform" id="fconfigform" method="post" action="./config_form_update.php" onsubmit="return fconfigform_submit(this);" class="admin-form-layout ui-form-theme ui-form-showcase space-y-5">
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
        $captcha_js   = chk_captcha_js();
        echo $captcha_html;
        ?>
    </div>

    <div class="admin-form-sticky-actions flex items-center justify-end border-default-300 border-t border-dashed pt-4">
        <button type="submit" class="btn btn-solid-primary" accesskey="s">저장</button>
    </div>
</form>

<?php
// 자바스크립트 및 기타 로직
include_once G5_ADMIN_PATH.'/config_form_parts/script.php';

require_once './admin.tail.php';
?>
