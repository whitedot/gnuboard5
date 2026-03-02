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

if (!$config['cf_faq_skin']) {
    $config['cf_faq_skin'] = 'basic';
}

$g5['title'] = '환경설정';
$admin_container_class = 'admin-page-config-form';
$admin_page_subtitle = '기본, 회원, 보안, 연동 설정을 탭에서 빠르게 관리하세요.';
require_once './admin.head.php';
?>
<?php

$pg_anchor = '';
$config_tabs = array(
    array('id' => 'anc_cf_basic', 'label' => '기본'),
    array('id' => 'anc_cf_board', 'label' => '게시판'),
    array('id' => 'anc_cf_join', 'label' => '회원'),
    array('id' => 'anc_cf_cert', 'label' => '본인확인'),
    array('id' => 'anc_cf_url', 'label' => 'URL'),
    array('id' => 'anc_cf_mail', 'label' => '메일'),
    array('id' => 'anc_cf_article_mail', 'label' => '글작성 메일'),
    array('id' => 'anc_cf_join_mail', 'label' => '가입 메일'),
    array('id' => 'anc_cf_vote_mail', 'label' => '투표 메일'),
    array('id' => 'anc_cf_sns', 'label' => 'SNS'),
    array('id' => 'anc_cf_lay', 'label' => '레이아웃'),
    array('id' => 'anc_cf_sms', 'label' => 'SMS'),
);

$pg_anchor_menu = admin_build_anchor_menu($config_tabs, array(
    'nav_id' => 'config_tabs_nav',
    'nav_class' => 'tab-nav',
    'nav_aria_label' => '환경설정 탭',
    'link_class' => 'tab-trigger-line-primary js-config-tab-link',
    'active_class' => 'active',
    'as_tabs' => true,
    'link_id_prefix' => 'config_tab_',
));

if (!$config['cf_icode_server_ip']) {
    $config['cf_icode_server_ip'] = '211.172.232.124';
}
if (!$config['cf_icode_server_port']) {
    $config['cf_icode_server_port'] = '7295';
}

$userinfo = array('payment' => '');
if ($config['cf_sms_use'] && $config['cf_icode_id'] && $config['cf_icode_pw']) {
    $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);
}
?>

<div id="config_tabs_bar">
    <?php echo $pg_anchor_menu; ?>
</div>

<form name="fconfigform" id="fconfigform" method="post" action="./config_form_update.php" onsubmit="return fconfigform_submit(this);" class="admin-form-layout space-y-5">
    <input type="hidden" name="token" value="" id="token">

    <?php
    include_once G5_ADMIN_PATH . '/config_form_parts/basic.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/board.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/join.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/cert.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/url.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/mail.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/sns.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/layout.php';
    include_once G5_ADMIN_PATH . '/config_form_parts/sms.php';
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

    <div class="flex items-center justify-end border-default-300 border-t border-dashed pt-4">
        <button type="submit" class="btn btn-solid-primary" accesskey="s">저장</button>
    </div>
</form>

<?php
// 자바스크립트 및 기타 로직
include_once G5_ADMIN_PATH.'/config_form_parts/script.php';

require_once './admin.tail.php';
?>
