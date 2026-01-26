<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_MSHOP_PATH.'/settle_'.$default['de_pg_service'].'.inc.php');
require_once(G5_SHOP_PATH.'/settle_kakaopay.inc.php');

if( is_inicis_simple_pay() ){   //이니시스 삼성페이 또는 Lpay 사용시
    require_once(G5_MSHOP_PATH.'/samsungpay/incSamsungpayCommon.php');
}

if(function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')){  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_MSHOP_PATH.'/kcp/global_m_nhn_kcp.php');
}

$tablet_size = "1.0"; // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0)

// 개인결제번호제거
set_session('ss_personalpay_id', '');
set_session('ss_personalpay_hash', '');

// 장바구니 상품 목록 및 합계
include_once(G5_MSHOP_PATH.'/order_form_parts/cart.php');

if($is_kakaopay_use) {
    require_once(G5_SHOP_PATH.'/kakaopay/orderform.1.php');
}
?>

<div id="sod_frm" class="sod_frm_mobile">
    <form name="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">
    <input type="hidden" name="od_price"    value="<?php echo $tot_sell_price; ?>">
    <input type="hidden" name="org_od_price"    value="<?php echo $tot_sell_price; ?>">
    <input type="hidden" name="od_send_cost" value="<?php echo $send_cost; ?>">
    <input type="hidden" name="od_send_cost2" value="0">
    <input type="hidden" name="item_coupon" value="0">
    <input type="hidden" name="od_coupon" value="0">
    <input type="hidden" name="od_send_coupon" value="0">

    <?php echo $content; ?>

    <!-- 주문하시는 분 -->
    <?php include_once(G5_MSHOP_PATH.'/order_form_parts/orderer.php'); ?>

    <!-- 받으시는 분 -->
    <?php include_once(G5_MSHOP_PATH.'/order_form_parts/taker.php'); ?>

    <!-- 결제정보 입력 -->
    <?php include_once(G5_MSHOP_PATH.'/order_form_parts/payment.php'); ?>

    <div id="show_progress" style="display:none;">
        <img src="<?php echo G5_MOBILE_URL; ?>/shop/img/loading.gif" alt="">
        <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
    </div>

    <?php
    if($is_kakaopay_use) {
        require_once(G5_SHOP_PATH.'/kakaopay/orderform.3.php');
    }
    ?>
    </form>

    <?php
    if ($default['de_escrow_use']) {
        // 결제대행사별 코드 include (에스크로 안내)
        require_once(G5_MSHOP_PATH.'/'.$default['de_pg_service'].'/orderform.3.php');

        if( is_inicis_simple_pay() ){   //삼성페이 사용시
            require_once(G5_MSHOP_PATH.'/samsungpay/orderform.3.php');
        }
    }
    ?>

</div>

<?php
if( is_inicis_simple_pay() ){   //삼성페이 사용시
    require_once(G5_MSHOP_PATH.'/samsungpay/order.script.php');
}

if(function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')){  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_MSHOP_PATH.'/kcp/m_order.script.php');
}

// 스크립트
include_once(G5_MSHOP_PATH.'/order_form_parts/script.php');
?>