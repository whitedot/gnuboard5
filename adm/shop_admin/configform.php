<?php
$sub_menu = '400100';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check_menu($auth, $sub_menu, "r");

if (!$config['cf_icode_server_ip'])   $config['cf_icode_server_ip'] = '211.172.232.124';
if (!$config['cf_icode_server_port']) $config['cf_icode_server_port'] = '7295';

$userinfo = array('payment'=>'');
if ($config['cf_sms_use'] && $config['cf_icode_id'] && $config['cf_icode_pw']) {
    $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);
}

$g5['title'] = '쇼핑몰설정';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$pg_anchor = '<ul class="section-anchor">
<li><a href="#anc_scf_info">사업자정보</a></li>
<li><a href="#anc_scf_skin">스킨설정</a></li>
<li><a href="#anc_scf_index">쇼핑몰 초기화면</a></li>
<li><a href="#anc_mscf_index">모바일 초기화면</a></li>
<li><a href="#anc_scf_payment">결제설정</a></li>
<li><a href="#anc_scf_delivery">배송설정</a></li>
<li><a href="#anc_scf_etc">기타설정</a></li>
<li><a href="#anc_scf_sms">SMS설정</a></li>
</ul>';

if( function_exists('pg_setting_check') ){
    pg_setting_check(true);
}

if(!$default['de_kakaopay_cancelpwd']){
    $default['de_kakaopay_cancelpwd'] = '1111';
}
?>

<form name="fconfig" action="./configformupdate.php" onsubmit="return fconfig_check(this)" method="post" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="token" value="">

<?php
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/info.php');
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/skin.php');
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/index.php');
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/mobile_index.php');
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/payment.php');
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/delivery.php');
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/etc.php');
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/sms.php');
?>

<div class="action-bar">
    <a href=" <?php echo G5_SHOP_URL; ?>" class="btn btn-secondary">쇼핑몰</a>
    <input type="submit" value="확인" class="btn btn-sm border-default-300" accesskey="s">
</div>

</form>

<?php
include_once(G5_ADMIN_PATH.'/shop_admin/config_form_parts/script.php');
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>