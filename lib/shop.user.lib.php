<?php
if (!defined('_GNUBOARD_')) exit;

//오늘본상품 데이터
function get_view_today_items($is_cache=false)
{
    global $g5;
    
    $tv_idx = get_session("ss_tv_idx");

    if( !$tv_idx ){
        return array();
    }

    static $cache = array();

    if( $is_cache && !empty($cache) ){
        return $cache;
    }

    for ($i=1;$i<=$tv_idx;$i++){

        $tv_it_idx = $tv_idx - ($i - 1);
        $tv_it_id = get_session("ss_tv[$tv_it_idx]");

        $rowx = get_shop_item($tv_it_id, true);
        if(!$rowx['it_id'])
            continue;
        
        $key = $rowx['it_id'];

        $cache[$key] = $rowx;
    }

    return $cache;
}

//오늘본상품 갯수 출력
function get_view_today_items_count()
{
    $tv_datas = get_view_today_items(true);

    return count($tv_datas);
}

//위시리스트 데이터 가져오기
function get_wishlist_datas($mb_id, $is_cache=false)
{
    global $g5, $member;

    if( !$mb_id ){
        $mb_id = $member['mb_id'];

        if( !$mb_id ) return array();
    }

    static $cache = array();

    if( $is_cache && isset($cache[$mb_id]) ){
        return $cache[$mb_id];
    }

    $cache[$mb_id] = array();
    $sql  = " select a.it_id, b.it_name from {$g5['g5_shop_wish_table']} a, {$g5['g5_shop_item_table']} b ";
    $sql .= " where a.mb_id = '".$mb_id."' and a.it_id  = b.it_id order by a.wi_id desc ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $key = $row['it_id'];
        $cache[$mb_id][$key] = $row;
    }

    return $cache[$mb_id];
}

//위시리스트 데이터 갯수 출력
function get_wishlist_datas_count($mb_id='')
{
    global $member;

    if( !$mb_id ){
        $mb_id = $member['mb_id'];

        if( !$mb_id ) return 0;
    }

    $wishlist_datas = get_wishlist_datas($mb_id, true);

    return is_array($wishlist_datas) ? count($wishlist_datas) : 0;
}

//각 상품에 대한 위시리스트 담은 갯수 출력
function get_wishlist_count_by_item($it_id='')
{
    global $g5;

    if( !$it_id ) return 0;

    $sql = "select count(a.it_id) as num from {$g5['g5_shop_wish_table']} a, {$g5['g5_shop_item_table']} b where a.it_id  = b.it_id and b.it_id = '$it_id'";

    $row = sql_fetch($sql);

    return (int) $row['num'];
}
