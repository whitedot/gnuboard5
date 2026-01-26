<?php
if (!defined('_GNUBOARD_')) exit;

// 포인트 표시
function display_point($point)
{
    return number_format($point, 0).'점';
}

// 포인트를 구한다
function get_point($amount, $point)
{
    return (int)($amount * $point / 100);
}

// 상품포인트
function get_item_point($it, $io_id='', $trunc=10)
{
    global $g5;

    $it_point = 0;

    if($it['it_point_type'] > 0) {
        $it_price = $it['it_price'];

        if($it['it_point_type'] == 2 && $io_id) {
            $sql = " select io_id, io_price
                        from {$g5['g5_shop_item_option_table']}
                        where it_id = '{$it['it_id']}'
                          and io_id = '$io_id'
                          and io_type = '0'
                          and io_use = '1' ";
            $opt = sql_fetch($sql);

            if($opt['io_id'])
                $it_price += $opt['io_price'];
        }

        $it_point = floor(($it_price * ($it['it_point'] / 100) / $trunc)) * $trunc;
    } else {
        $it_point = $it['it_point'];
    }

    return $it_point;
}

//------------------------------------------------------------------------------
// 주문포인트를 적립한다.
// 설정일이 지난 포인트 부여되지 않은 배송완료된 장바구니 자료에 포인트 부여
// 설정일이 0 이면 주문서 완료 설정 시점에서 포인트를 바로 부여합니다.
//------------------------------------------------------------------------------
function save_order_point($ct_status="완료")
{
    global $g5, $default;

    $beforedays = date("Y-m-d H:i:s", ( time() - (86400 * (int)$default['de_point_days']) ) ); // 86400초는 하루
    $sql = " select * from {$g5['g5_shop_cart_table']} where ct_status = '$ct_status' and ct_point_use = '0' and ct_time <= '$beforedays' ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 회원 ID 를 얻는다.
        $od_row = sql_fetch("select od_id, mb_id from {$g5['g5_shop_order_table']} where od_id = '{$row['od_id']}' ");
        if ($od_row['mb_id'] && $row['ct_point'] > 0) { // 회원이면서 포인트가 0보다 크다면
            $po_point = $row['ct_point'] * $row['ct_qty'];
            $po_content = "주문번호 {$od_row['od_id']} ({$row['ct_id']}) 배송완료";
            insert_point($od_row['mb_id'], $po_point, $po_content, "@delivery", $od_row['mb_id'], "{$od_row['od_id']},{$row['ct_id']}");
        }
        sql_query("update {$g5['g5_shop_cart_table']} set ct_point_use = '1' where ct_id = '{$row['ct_id']}' ");
    }
}
