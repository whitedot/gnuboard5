<?php
include_once('./_common.php');

$sql_common = " from {$g5['g5_shop_coupon_zone_table']}
                where cz_start <= '".G5_TIME_YMD."'
                  and cz_end >= '".G5_TIME_YMD."' ";

$sql_order  = " order by cz_id desc ";

add_javascript('<script src="'.G5_JS_URL.'/shop.couponzone.js"></script>', 100);

$g5['title'] = '쿠폰존';
include_once(G5_SHOP_PATH.'/_head.php');

if (!G5_IS_MOBILE && $is_admin)
    echo '<div><a href="'.G5_ADMIN_URL.'/shop_admin/couponzonelist.php"><span>쿠폰존 관리</span><i></i></a></div>';

if(G5_IS_MOBILE) {
    define('G5_SHOP_CSS_URL', G5_MSHOP_SKIN_URL);
    $skin_file = G5_MSHOP_SKIN_PATH.'/couponzone.10.skin.php';
} else {
    define('G5_SHOP_CSS_URL', G5_SHOP_SKIN_URL);
    $skin_file = G5_SHOP_SKIN_PATH.'/couponzone.10.skin.php';
}

if (is_file($skin_file)) {
    include_once($skin_file);

} else {
    echo '<div>'.str_replace(G5_PATH.'/', '', $skin_file).' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
}

include_once(G5_SHOP_PATH.'/_tail.php');