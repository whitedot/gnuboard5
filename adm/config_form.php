<?php
$sub_menu = "100100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

// https://github.com/gnuboard/gnuboard5/issues/296 대응
$sql = " select * from {$g5['config_table']} limit 1";
$config = sql_fetch($sql);

$g5['title'] = '환경설정';
$admin_container_class = 'admin-page-config-form';
$admin_page_subtitle = '기본, 회원, 보안, 연동 설정을 탭에서 빠르게 관리하세요.';
require_once './admin.head.php';
?>
<?php

$pg_anchor = '';
$config_tabs = array(
    array('id' => 'anc_cf_basic', 'label' => '기본'),
    array('id' => 'anc_cf_join', 'label' => '회원'),
    array('id' => 'anc_cf_cert', 'label' => '본인확인'),
    array('id' => 'anc_cf_mail', 'label' => '메일'),
);

$pg_anchor_menu = admin_build_anchor_menu($config_tabs, array(
    'nav_id' => 'config_tabs_nav',
    'nav_class' => 'tab-nav-justified',
    'nav_aria_label' => '환경설정 탭',
    'link_class' => 'tab-trigger-underline-justified js-config-tab-link',
    'active_class' => 'active',
    'as_tabs' => true,
    'link_id_prefix' => 'config_tab_',
));

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
