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
require_once './admin.head.php';
?>
<style>
    .hint-text {
        margin-bottom: var(--spacing-base);
        border-radius: var(--radius);
        border: 1px dashed var(--color-default-300);
        background-color: var(--color-default-100);
        padding: calc(var(--spacing) * 4);
        font-size: var(--text-sm);
        color: var(--color-default-600);
        line-height: 1.5;
    }

    .hint-text p {
        margin: 0;
    }
</style>
<?php

$pg_anchor = '';
$pg_anchor_menu = '
<nav>
    <div>
        <h3>환경설정 바로가기</h3>
    </div>
    <div>
        <ul>
            <li><a href="#anc_cf_basic">기본</a></li>
            <li><a href="#anc_cf_board">게시판</a></li>
            <li><a href="#anc_cf_join">회원</a></li>
            <li><a href="#anc_cf_cert">본인확인</a></li>
            <li><a href="#anc_cf_url">URL</a></li>
            <li><a href="#anc_cf_mail">메일</a></li>
            <li><a href="#anc_cf_article_mail">글작성 메일</a></li>
            <li><a href="#anc_cf_join_mail">가입 메일</a></li>
            <li><a href="#anc_cf_vote_mail">투표 메일</a></li>
            <li><a href="#anc_cf_sns">SNS</a></li>
            <li><a href="#anc_cf_lay">레이아웃</a></li>
            <li><a href="#anc_cf_sms">SMS</a></li>
            <li><a href="#anc_cf_extra">여분필드</a></li>
        </ul>
    </div>
</nav>';

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

<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);">
    <input type="hidden" name="token" value="" id="token">

    <div>
        <aside>
            <?php echo $pg_anchor_menu; ?>
        </aside>

        <div>
            <?php
            include_once G5_ADMIN_PATH.'/config_form_parts/basic.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/board.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/join.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/cert.php';
            require_once '_rewrite_config_form.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/mail.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/sns.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/layout.php';
            include_once G5_ADMIN_PATH.'/config_form_parts/sms.php';
            ?>

            <div id="config_captcha_wrap" class="hidden">
                <div>
                    <h2>캡차 입력</h2>
                </div>
                <div>
                    <?php
                    require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
                    $captcha_html = captcha_html();
                    $captcha_js   = chk_captcha_js();
                    echo $captcha_html;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div>
        <input type="submit" value="저장" accesskey="s">
    </div>
</form>

<?php
// 자바스크립트 및 기타 로직
include_once G5_ADMIN_PATH.'/config_form_parts/script.php';

require_once './admin.tail.php';
?>
