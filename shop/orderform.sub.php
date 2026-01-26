<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_SHOP_PATH.'/settle_'.$default['de_pg_service'].'.inc.php');
require_once(G5_SHOP_PATH.'/settle_kakaopay.inc.php');

if( $default['de_inicis_lpay_use'] || $default['de_inicis_kakaopay_use'] ){   //이니시스 Lpay 혹은 이니시스 카카오페이 사용시
    require_once(G5_SHOP_PATH.'/inicis/lpay_common.php');
}

if(function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')){  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_SHOP_PATH.'/kcp/global_nhn_kcp.php');
}

// 결제대행사별 코드 include (스크립트 등)
require_once(G5_SHOP_PATH.'/'.$default['de_pg_service'].'/orderform.1.php');

if( $default['de_inicis_lpay_use'] || $default['de_inicis_kakaopay_use'] ){   //이니시스 L.pay 사용시
    require_once(G5_SHOP_PATH.'/inicis/lpay_form.1.php');
}

if(function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')){  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_SHOP_PATH.'/kcp/global_nhn_kcp_form.1.php');
}

if($is_kakaopay_use) {
    require_once(G5_SHOP_PATH.'/kakaopay/orderform.1.php');
}
?>

<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">
<div id="sod_frm" class="sod_frm_pc">
    
    <!-- 주문상품 확인 시작 -->
    <?php include_once(G5_SHOP_PATH.'/order_form_parts/cart.php'); ?>

    <div class="sod_left">
        <input type="hidden" name="od_price"    value="<?php echo $tot_sell_price; ?>">
        <input type="hidden" name="org_od_price"    value="<?php echo $tot_sell_price; ?>">
        <input type="hidden" name="od_send_cost" value="<?php echo $send_cost; ?>">
        <input type="hidden" name="od_send_cost2" value="0">
        <input type="hidden" name="item_coupon" value="0">
        <input type="hidden" name="od_coupon" value="0">
        <input type="hidden" name="od_send_coupon" value="0">
        <input type="hidden" name="od_goods_name" value="<?php echo $goods; ?>">

        <?php
        // 결제대행사별 코드 include (결제대행사 정보 필드)
        require_once(G5_SHOP_PATH.'/'.$default['de_pg_service'].'/orderform.2.php');

        if($is_kakaopay_use) {
            require_once(G5_SHOP_PATH.'/kakaopay/orderform.2.php');
        }
        ?>

        <!-- 주문하시는 분 입력 -->
        <?php include_once(G5_SHOP_PATH.'/order_form_parts/orderer.php'); ?>

        <!-- 받으시는 분 입력 -->
        <?php include_once(G5_SHOP_PATH.'/order_form_parts/taker.php'); ?>
    </div>

    <div class="sod_right">
        <!-- 주문상품 합계 시작 { -->
        <div id="sod_bsk_tot">
            <ul>
                <li class="sod_bsk_sell">
                    <span>주문</span>
                    <strong><?php echo number_format($tot_sell_price); ?></strong>원
                </li>
                <li class="sod_bsk_coupon">
                    <span>쿠폰할인</span>
                    <strong id="ct_tot_coupon">0</strong>원
                </li>
                <li class="sod_bsk_dvr">
                    <span>배송비</span>
                    <strong><?php echo number_format($send_cost); ?></strong>원
                </li>
                <li class="sod_bsk_point">
                    <span>포인트</span>
                    <strong><?php echo number_format($tot_point); ?></strong>점
                </li>
               <li class="sod_bsk_cnt">
                    <span>총계</span>
                    <?php $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 ?>
                    <strong id="ct_tot_price"><?php echo number_format($tot_price); ?></strong>원
                </li>

            </ul>
        </div>
        <!-- } 주문상품 합계 끝 -->

        <!-- 결제정보 입력 -->
        <?php include_once(G5_SHOP_PATH.'/order_form_parts/payment.php'); ?>
    </div>

</div>
</form>

<?php
if( $default['de_inicis_lpay_use'] || $default['de_inicis_kakaopay_use'] ){   //이니시스 L.pay 혹은 이니시스 카카오페이 사용시
    require_once(G5_SHOP_PATH.'/inicis/lpay_order.script.php');
}
if(function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')){  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_SHOP_PATH.'/kcp/global_nhn_kcp_order.script.php');
}

// 스크립트
include_once(G5_SHOP_PATH.'/order_form_parts/script.php');
?>