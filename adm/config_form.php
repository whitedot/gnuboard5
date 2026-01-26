<?php
$sub_menu = "100100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

// https://github.com/gnuboard/gnuboard5/issues/296 이슈처리
$sql = " select * from {$g5['config_table']} limit 1";
$config = sql_fetch($sql);

if (!$config['cf_faq_skin']) {
    $config['cf_faq_skin'] = "basic";
}
if (!$config['cf_mobile_faq_skin']) {
    $config['cf_mobile_faq_skin'] = "basic";
}

$g5['title'] = '환경설정';
require_once './admin.head.php';

$pg_anchor = '<ul class="anchor">
    <li><a href="#anc_cf_basic">기본환경</a></li>
    <li><a href="#anc_cf_board">게시판기본</a></li>
    <li><a href="#anc_cf_join">회원가입</a></li>
    <li><a href="#anc_cf_cert">본인확인</a></li>
    <li><a href="#anc_cf_url">짧은주소</a></li>
    <li><a href="#anc_cf_mail">기본메일환경</a></li>
    <li><a href="#anc_cf_article_mail">글작성메일</a></li>
    <li><a href="#anc_cf_join_mail">가입메일</a></li>
    <li><a href="#anc_cf_vote_mail">투표메일</a></li>
    <li><a href="#anc_cf_sns">SNS</a></li>
    <li><a href="#anc_cf_lay">레이아웃 추가설정</a></li>
    <li><a href="#anc_cf_sms">SMS</a></li>
    <li><a href="#anc_cf_extra">여분필드</a></li>
</ul>';


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

    <?php
    // 홈페이지 기본환경 설정
    include_once G5_ADMIN_PATH.'/config_form_parts/basic.php';

    // 게시판 기본 설정
    include_once G5_ADMIN_PATH.'/config_form_parts/board.php';

    // 회원가입 설정
    include_once G5_ADMIN_PATH.'/config_form_parts/join.php';

    // 본인확인 설정
    include_once G5_ADMIN_PATH.'/config_form_parts/cert.php';

    // 짧은주소 설정
    require_once '_rewrite_config_form.php';

    // 메일 관련 설정 (기본, 게시판, 회원가입, 투표 공통)
    include_once G5_ADMIN_PATH.'/config_form_parts/mail.php';

    // SNS 설정
    include_once G5_ADMIN_PATH.'/config_form_parts/sns.php';

    // 레이아웃 추가설정
    include_once G5_ADMIN_PATH.'/config_form_parts/layout.php';

    // SMS 설정
    include_once G5_ADMIN_PATH.'/config_form_parts/sms.php';

    // 여분필드 기본 설정
    include_once G5_ADMIN_PATH.'/config_form_parts/extra.php';
    ?>
    
    <div id="config_captcha_wrap" style="display:none">
        <h2>캡챠입력</h2>
        <?php
        require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
        $captcha_html = captcha_html();
        $captcha_js   = chk_captcha_js();
        echo $captcha_html;
        ?>
    </div>
    
    <div class="btn_fixed_top btn_confirm">
        <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
    </div>

</form>

<?php
// 자바스크립트 및 기타 로직
include_once G5_ADMIN_PATH.'/config_form_parts/script.php';

require_once './admin.tail.php';
?>
