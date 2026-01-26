<?php
if (!defined('_GNUBOARD_')) exit;

// 배송비 구함
function get_sendcost($cart_id, $selected=1)
{
    global $default, $g5;

    $send_cost = 0;
    $total_price = 0;
    $total_send_cost = 0;
    $diff = 0;

    $sql = " select distinct it_id
                from {$g5['g5_shop_cart_table']}
                where od_id = '$cart_id'
                  and ct_send_cost = '0'
                  and ct_status IN ( '쇼핑', '주문', '입금', '준비', '배송', '완료' )
                  and ct_select = '$selected' ";

    $result = sql_query($sql);
    for($i=0; $sc=sql_fetch_array($result); $i++) {
        // 합계
        $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                        SUM(ct_qty) as qty
                    from {$g5['g5_shop_cart_table']}
                    where it_id = '{$sc['it_id']}'
                      and od_id = '$cart_id'
                      and ct_status IN ( '쇼핑', '주문', '입금', '준비', '배송', '완료' )
                      and ct_select = '$selected'";
        $sum = sql_fetch($sql);

        $send_cost = get_item_sendcost($sc['it_id'], $sum['price'], $sum['qty'], $cart_id);

        if($send_cost > 0)
            $total_send_cost += $send_cost;

        if($default['de_send_cost_case'] == '차등' && $send_cost == -1) {
            $total_price += $sum['price'];
            $diff++;
        }
    }

    $send_cost = 0;
    if($default['de_send_cost_case'] == '차등' && $total_price >= 0 && $diff > 0) {
        // 금액별차등 : 여러단계의 배송비 적용 가능
        $send_cost_limit = explode(";", $default['de_send_cost_limit']);
        $send_cost_list  = explode(";", $default['de_send_cost_list']);
        $send_cost = 0;
        for ($k=0; $k<count($send_cost_limit); $k++) {
            // 총판매금액이 배송비 상한가 보다 작다면
            if ($total_price < preg_replace('/[^0-9]/', '', $send_cost_limit[$k])) {
                $send_cost = preg_replace('/[^0-9]/', '', $send_cost_list[$k]);
                break;
            }
        }
    }

    return ($total_send_cost + $send_cost);
}


// 상품별 배송비
function get_item_sendcost($it_id, $price, $qty, $cart_id)
{
    global $g5, $default;

    $sql = " select it_id, it_sc_type, it_sc_method, it_sc_price, it_sc_minimum, it_sc_qty
                from {$g5['g5_shop_cart_table']}
                where it_id = '$it_id'
                  and od_id = '$cart_id'
                order by ct_id
                limit 1 ";
    $ct = sql_fetch($sql);
    if(!$ct['it_id'])
        return 0;

    if($ct['it_sc_type'] > 1) {
        if($ct['it_sc_type'] == 2) { // 조건부무료
            if($price >= $ct['it_sc_minimum'])
                $sendcost = 0;
            else
                $sendcost = $ct['it_sc_price'];
        } else if($ct['it_sc_type'] == 3) { // 유료배송
            $sendcost = $ct['it_sc_price'];
        } else { // 수량별 부과
            if(!$ct['it_sc_qty'])
                $ct['it_sc_qty'] = 1;

            $q = ceil((int)$qty / (int)$ct['it_sc_qty']);
            $sendcost = (int)$ct['it_sc_price'] * $q;
        }
    } else if($ct['it_sc_type'] == 1) { // 무료배송
        $sendcost = 0;
    } else {
        $sendcost = -1;
    }

    return $sendcost;
}


// 가격비교 사이트 상품 배송비
function get_item_sendcost2($it_id, $price, $qty)
{
    global $g5, $default;

    $sql = " select it_id, it_sc_type, it_sc_method, it_sc_price, it_sc_minimum, it_sc_qty
                from {$g5['g5_shop_item_table']}
                where it_id = '$it_id' ";
    $it = sql_fetch($sql);
    if(!$it['it_id'])
        return 0;

    $sendcost = 0;

    // 쇼핑몰 기본설정을 사용할 때
    if($it['it_sc_type'] == 0)
    {
        if($default['de_send_cost_case'] == '차등') {
            // 금액별차등 : 여러단계의 배송비 적용 가능
            $send_cost_limit = explode(";", $default['de_send_cost_limit']);
            $send_cost_list  = explode(";", $default['de_send_cost_list']);

            for ($k=0; $k<count($send_cost_limit); $k++) {
                // 총판매금액이 배송비 상한가 보다 작다면
                if ($price < preg_replace('/[^0-9]/', '', $send_cost_limit[$k])) {
                    $sendcost = preg_replace('/[^0-9]/', '', $send_cost_list[$k]);
                    break;
                }
            }
        }
    }
    else
    {
        if($it['it_sc_type'] > 1) {
            if($it['it_sc_method'] == 1){  // 배송비 결제 설정이 착불인 경우
                $sendcost = -1;
            } else {    // 배송비 결제 설정이 선불 또는 사용자선택인 경우
                if($it['it_sc_type'] == 2) { // 조건부무료
                    if($price >= $it['it_sc_minimum'])
                        $sendcost = 0;
                    else
                        $sendcost = $it['it_sc_price'];
                } else if($it['it_sc_type'] == 3) { // 유료배송
                    $sendcost = $it['it_sc_price'];
                } else { // 수량별 부과
                    if(!$it['it_sc_qty'])
                        $it['it_sc_qty'] = 1;

                    $q = ceil((int)$qty / (int)$it['it_sc_qty']);
                    $sendcost = (int)$it['it_sc_price'] * $q;
                }
            }
        } else if($it['it_sc_type'] == 1) { // 무료배송
            $sendcost = 0;
        }
    }

    return $sendcost;
}

// 배송조회버튼 생성
function get_delivery_inquiry($company, $invoice, $class='')
{
    if(!$company || !$invoice)
        return '';

    $dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));

    for($i=0; $i<count($dlcomp); $i++) {
        if(strstr($dlcomp[$i], $company)) {
            list($com, $url, $tel) = explode("^", $dlcomp[$i]);
            break;
        }
    }

    $str = '';
    if(isset($com) && $com && isset($url) && $url) {
        $str .= '<a href="'.$url.$invoice.'" target="_blank"';
        if($class)
            $str .= ' class="'.$class.'"';
        $str .='>배송조회</a>';
        if($tel)
            $str .= ' (문의전화: '.$tel.')';
    }

    return $str;
}

// 배송업체 리스트 얻기
function get_delivery_company($company)
{
    $option = '<option value="">없음</option>'.PHP_EOL;
    $option .= '<option value="자체배송" '.get_selected($company, '자체배송').'>자체배송</option>'.PHP_EOL;

    $dlcomp = explode(")", str_replace("(", "", G5_DELIVERY_COMPANY));
    for ($i=0; $i<count($dlcomp); $i++) {
        if (trim($dlcomp[$i])=="") continue;
        list($value, $url, $tel) = explode("^", $dlcomp[$i]);
        $option .= '<option value="'.$value.'" '.get_selected($company, $value).'>'.$value.'</option>'.PHP_EOL;
    }

    return $option;
}
