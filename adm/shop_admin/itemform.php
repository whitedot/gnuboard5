<?php
$sub_menu = '400300';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(G5_LIB_PATH.'/iteminfo.lib.php');

auth_check_menu($auth, $sub_menu, "w");

$html_title = "상품 ";

$it = array(
'it_id'=>'',
'it_skin'=>'',
'it_name'=>'',
'it_basic'=>'',
'it_order'=>0,
'it_type1'=>0,
'it_type2'=>0,
'it_type3'=>0,
'it_type4'=>0,
'it_type5'=>0,
'it_brand'=>'',
'it_model'=>'',
'it_tel_inq'=>0,
'it_use'=>0,
'it_nocoupon'=>0,
'ec_mall_pid'=>'',
'it_mobile_explan'=>'',
'it_sell_email'=>'',
'it_shop_memo'=>'',
'it_info_gubun'=>'',
'it_explan'=>'',
'it_point_type'=>0,
'it_cust_price'=>0,
'it_option_subject'=>'',
'it_price'=>0,
'it_point'=>0,
'it_supply_point'=>0,
'it_soldout'=>0,
'it_stock_sms'=>0,
'it_stock_qty'=>0,
'it_noti_qty'=>0,
'it_buy_min_qty'=>0,
'it_buy_max_qty'=>0,
'it_notax'=>0,
'it_supply_subject'=>'',
'it_sc_type'=>0,
'it_sc_method'=>0,
'it_sc_price'=>0,
'it_sc_minimum'=>0,
'it_sc_qty'=>0,
'it_img1'=>'',
'it_img2'=>'',
'it_img3'=>'',
'it_img4'=>'',
'it_img5'=>'',
'it_img6'=>'',
'it_img7'=>'',
'it_img8'=>'',
'it_img9'=>'',
'it_img10'=>'',
'it_head_html'=>'',
'it_tail_html'=>'',
'it_mobile_head_html'=>'',
'it_mobile_tail_html'=>'',
);

for($i=0;$i<=10;$i++){
    $it['it_'.$i.'_subj'] = '';
    $it['it_'.$i] = '';
}

if ($w == "")
{
    $html_title .= "입력";

    // 옵션은 쿠키에 저장된 값을 보여줌. 다음 입력을 위한것임
    //$it[ca_id] = _COOKIE[ck_ca_id];
    $it['ca_id'] = get_cookie("ck_ca_id");
    $it['ca_id2'] = get_cookie("ck_ca_id2");
    $it['ca_id3'] = get_cookie("ck_ca_id3");
    if (!$it['ca_id'])
    {
        $sql = " select ca_id from {$g5['g5_shop_category_table']} order by ca_order, ca_id limit 1 ";
        $row = sql_fetch($sql);
        if (! (isset($row['ca_id']) && $row['ca_id']))
            alert("등록된 분류가 없습니다. 우선 분류를 등록하여 주십시오.", './categorylist.php');
        $it['ca_id'] = $row['ca_id'];
    }
    //$it[it_maker]  = stripslashes($_COOKIE[ck_maker]);
    //$it[it_origin] = stripslashes($_COOKIE[ck_origin]);
    $it['it_maker']  = stripslashes(get_cookie("ck_maker"));
    $it['it_origin'] = stripslashes(get_cookie("ck_origin"));
}
else if ($w == "u")
{
    $html_title .= "수정";

    if ($is_admin != 'super')
    {
        $sql = " select it_id from {$g5['g5_shop_item_table']} a, {$g5['g5_shop_category_table']} b
                  where a.it_id = '$it_id'
                    and a.ca_id = b.ca_id
                    and b.ca_mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if (!$row['it_id'])
            alert("\'{$member['mb_id']}\' 님께서 수정 할 권한이 없는 상품입니다.");
    }

    $it = get_shop_item($it_id);

    if(!$it)
        alert('상품정보가 존재하지 않습니다.');
    
    if (function_exists('check_case_exist_title')) check_case_exist_title($it, G5_SHOP_DIR, false);

    if (! (isset($ca_id) && $ca_id))
        $ca_id = $it['ca_id'];

    $sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    $ca = sql_fetch($sql);
}
else
{
    alert();
}

$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;

$g5['title'] = $html_title;
include_once (G5_ADMIN_PATH.'/admin.head.php');

// 분류리스트
$category_select = '';
$script = '';
$sql = " select * from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
    $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
$sql .= " order by ca_order, ca_id ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $len = strlen($row['ca_id']) / 2 - 1;

    $nbsp = "";
    for ($i=0; $i<$len; $i++)
        $nbsp .= "&nbsp;&nbsp;&nbsp;";

    $category_select .= "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";

    $script .= "ca_use['{$row['ca_id']}'] = {$row['ca_use']};\n";
    $script .= "ca_stock_qty['{$row['ca_id']}'] = {$row['ca_stock_qty']};\n";
    //$script .= "ca_explan_html['$row[ca_id]'] = $row[ca_explan_html];\n";
    $script .= "ca_sell_email['{$row['ca_id']}'] = '{$row['ca_sell_email']}';\n";
}

$pg_anchor ='<ul class="anchor">
<li><a href="#anc_sitfrm_cate">상품분류</a></li>
<li><a href="#anc_sitfrm_skin">스킨설정</a></li>
<li><a href="#anc_sitfrm_ini">기본정보</a></li>
<li><a href="#anc_sitfrm_compact">요약정보</a></li>
<li><a href="#anc_sitfrm_cost">가격 및 재고</a></li>
<li><a href="#anc_sitfrm_sendcost">배송비</a></li>
<li><a href="#anc_sitfrm_img">상품이미지</a></li>
<li><a href="#anc_sitfrm_relation">관련상품</a></li>
<li><a href="#anc_sitfrm_event">관련이벤트</a></li>
<li><a href="#anc_sitfrm_optional">상세설명설정</a></li>
<li><a href="#anc_sitfrm_extra">여분필드</a></li>
</ul>
';
?>

<form name="fitemform" action="./itemformupdate.php" method="post" enctype="MULTIPART/FORM-DATA" autocomplete="off" onsubmit="return fitemformcheck(this)">

<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod"  value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx"  value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<?php
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/category.php'); // 상품분류
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/skin.php'); // 스킨설정
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/info.php'); // 기본정보
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/compact.php'); // 요약정보
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/cost.php'); // 가격 및 재고
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/delivery.php'); // 배송비
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/image.php'); // 상품이미지
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/relation.php'); // 관련상품
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/event.php'); // 관련이벤트
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/details.php'); // 상세설명설정
?>

<div class="btn_fixed_top">
    <a href="./itemlist.php?<?php echo $qstr; ?>" class="btn btn_02">목록</a>
    <a href="<?php echo shop_item_url($it_id); ?>" class="btn_02  btn">상품보기</a>
    <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
</div>
</form>

<?php
include_once(G5_ADMIN_PATH.'/shop_admin/item_form_parts/script.php');
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>