<?php
if (!defined('_GNUBOARD_')) exit;

// 금액 표시
function display_price($price, $tel_inq=false)
{
    if ($tel_inq)
        $price = '전화문의';
    else
        $price = number_format($price, 0).'원';

    return $price;
}

// 금액표시
// $it : 상품 배열
function get_price($it)
{
    global $member;

    if ($it['it_tel_inq']) return '전화문의';

    $price = $it['it_price'];

    return (int)$price;
}
