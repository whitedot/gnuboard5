<?php
if (!defined('_GNUBOARD_')) exit;

// 쿠폰번호 생성함수
function get_coupon_id()
{
    $len = 16;
    $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789";

    srand((double)microtime()*1000000);

    $i = 0;
    $str = '';

    while ($i < $len) {
        $num = rand() % strlen($chars);
        $tmp = substr($chars, $num, 1);
        $str .= $tmp;
        $i++;
    }

    $str = preg_replace("/([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})/", "\\1-\\2-\\3-\\4", $str);

    return $str;
}

// 쿠폰 사용체크
function is_used_coupon($mb_id, $cp_id)
{
    global $g5;

    $used = false;

    $sql = " select count(*) as cnt from {$g5['g5_shop_coupon_log_table']} where mb_id = '$mb_id' and cp_id = '$cp_id' ";
    $row = sql_fetch($sql);

    if($row['cnt'])
        $used = true;

    return $used;
}

// 다운로드한 쿠폰인지
function is_coupon_downloaded($mb_id, $cz_id)
{
    global $g5;

    if(!$mb_id)
        return false;

    $sql = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where mb_id = '$mb_id' and cz_id = '$cz_id' ";
    $row = sql_fetch($sql);

    return ($row['cnt'] > 0);
}
