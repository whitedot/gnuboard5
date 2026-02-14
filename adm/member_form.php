<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

$mb = array(
    'mb_certify' => null,
    'mb_adult' => null,
    'mb_sms' => null,
    'mb_intercept_date' => null,
    'mb_id' => null,
    'mb_name' => null,
    'mb_nick' => null,
    'mb_point' => null,
    'mb_email' => null,
    'mb_homepage' => null,
    'mb_hp' => null,
    'mb_tel' => null,
    'mb_zip1' => null,
    'mb_zip2' => null,
    'mb_addr1' => null,
    'mb_addr2' => null,
    'mb_addr3' => null,
    'mb_addr_jibeon' => null,
    'mb_signature' => null,
    'mb_profile' => null,
    'mb_memo' => null,
    'mb_leave_date' => null,
);

$sr_only = '';
$required_mb_id = '';
$required_mb_id_class = '';
$required_mb_password = '';
$html_title = '';

if ($w == '') {
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sr_only = '<strong class="sr-only">?꾩닔</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_sms'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $mb['mb_marketing_agree'] = 0;
    $mb['mb_thirdparty_agree'] = 0;
    $html_title = '異붽?';
} elseif ($w == 'u') {
    $mb = get_member($mb_id);
    if (!$mb['mb_id']) {
        alert('議댁옱?섏? ?딅뒗 ?뚯썝?먮즺?낅땲??');
    }

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
        alert('?먯떊蹂대떎 沅뚰븳???믨굅??媛숈? ?뚯썝? ?섏젙?????놁뒿?덈떎.');
    }

    $required_mb_id = 'readonly';
    $html_title = '?섏젙';

    $mb['mb_name'] = get_text($mb['mb_name']);
    $mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
} else {
    alert('?쒕?濡???媛믪씠 ?섏뼱?ㅼ? ?딆븯?듬땲??');
}

// 蹂몄씤?뺤씤諛⑸쾿
switch ($mb['mb_certify']) {
    case 'simple':
        $mb_certify_case = 'HP';
        $mb_certify_val = 'simple';
        break;
    case 'hp':
        $mb_certify_case = 'HP';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '?꾩씠?';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '愿由ъ옄 ?섏젙';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 蹂몄씤?뺤씤
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// ?깆씤?몄쬆
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//硫붿씪?섏떊
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS ?섏떊
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// ?뺣낫 怨듦컻
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

// 留덉???紐⑹쟻??媛쒖씤?뺣낫 ?섏쭛 諛??댁슜
$mb_marketing_agree_yes     =  $mb['mb_marketing_agree'] ? 'checked="checked"' : '';
$mb_marketing_agree_no      = !$mb['mb_marketing_agree'] ? 'checked="checked"' : '';

// 媛쒖씤?뺣낫 ?????쒓났 ?숈쓽
$mb_thirdparty_agree_yes    =  $mb['mb_thirdparty_agree'] ? 'checked="checked"' : '';
$mb_thirdparty_agree_no     = !$mb['mb_thirdparty_agree'] ? 'checked="checked"' : '';

$mb_cert_history = '';
if (isset($mb_id) && $mb_id) {
    $sql = "select * from {$g5['member_cert_history_table']} where mb_id = '{$mb_id}' order by ch_id asc";
    $mb_cert_history = sql_query($sql);
}

if ($mb['mb_intercept_date']) {
    $g5['title'] = "李⑤떒??";
} else {
    $g5['title'] = "";
}
$g5['title'] .= '?뚯썝 ' . $html_title;
require_once './admin.head.php';

// add_javascript('js 援щЦ', 異쒕젰?쒖꽌); ?レ옄媛 ?묒쓣 ?섎줉 癒쇱? 異쒕젰??
add_javascript(G5_POSTCODE_JS, 0);    //?ㅼ쓬 二쇱냼 js

$pg_anchor = '<ul class="anchor">
    <li><a href="#anc_mb_basic">湲곕낯 ?뺣낫</a></li>
    <li><a href="#anc_mb_contact">?곕씫泥?諛?二쇱냼</a></li>
    <li><a href="#anc_mb_media">?꾩씠肄?諛??대?吏</a></li>
    <li><a href="#anc_mb_consent">?섏떊 諛?怨듦컻 ?ㅼ젙</a></li>
    <li><a href="#anc_mb_profile">?꾨줈??諛?硫붾え</a></li>
    <li><a href="#anc_mb_history">?몄쬆 諛??쒕룞 ?댁뿭</a></li>
    <li><a href="#anc_mb_extra">?щ텇 ?꾨뱶</a></li>
</ul>';
?>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <?php
    echo $pg_anchor;

    // 湲곕낯 ?뺣낫
    include_once G5_ADMIN_PATH.'/member_form_parts/basic.php';

    // ?곕씫泥?諛?二쇱냼
    include_once G5_ADMIN_PATH.'/member_form_parts/contact.php';

    // ?꾩씠肄?諛??대?吏
    include_once G5_ADMIN_PATH.'/member_form_parts/media.php';

    // ?섏떊 諛?怨듦컻 ?ㅼ젙
    include_once G5_ADMIN_PATH.'/member_form_parts/consent.php';

    // ?꾨줈??諛?硫붾え
    include_once G5_ADMIN_PATH.'/member_form_parts/profile.php';

    // ?몄쬆 諛??쒕룞 ?댁뿭
    include_once G5_ADMIN_PATH.'/member_form_parts/history.php';
    ?>

    <div class="btn_fixed_top">
        <a href="./member_list.php?<?php echo $qstr ?>" class="btn btn_02">紐⑸줉</a>
        <input type="submit" value="?뺤씤" class="btn_submit btn" accesskey='s'>
    </div>
</form>

<?php
// ?먮컮?ㅽ겕由쏀듃
include_once G5_ADMIN_PATH.'/member_form_parts/script.php';

run_event('admin_member_form_after', $mb, $w);

require_once './admin.tail.php';
