<?php if($od['od_test']) { ?>
<div class="od_test_caution">주의) 이 주문은 테스트용으로 실제 결제가 이루어지지 않았으므로 절대 배송하시면 안됩니다.</div>
<?php } ?>
<?php if($od['od_pg'] === 'inicis' && !$od['od_test']) {
    $sql = "select P_TID from {$g5['g5_shop_inicis_log_table']} where oid = '$od_id' and P_STATUS = 'cancel' ";
    $tmp_row = sql_fetch($sql);
    if(isset($tmp_row['P_TID']) && $tmp_row['P_TID']){
?>
<div class="od_test_caution">주의) 이 주문은 결제취소된 내역이 있습니다. 이니시스 관리자 상점에서 반드시 재확인을 해 주세요.</div>
<?php 
    }   //end if
}   //end if
?>

<section id="anc_sodr_pay">
    <h2 class="h2_frm">주문결제 내역</h2>
    <?php echo $pg_anchor; ?>

    <?php
    // 주문금액 = 상품구입금액 + 배송비 + 추가배송비
    $amount['order'] = $od['od_cart_price'] + $od['od_send_cost'] + $od['od_send_cost2'];

    // 입금액 = 결제금액 + 포인트
    $amount['receipt'] = $od['od_receipt_price'] + $od['od_receipt_point'];

    // 쿠폰금액
    $amount['coupon'] = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'];

    // 취소금액
    $amount['cancel'] = $od['od_cancel_price'];

    // 미수금 = 주문금액 - 취소금액 - 입금금액 - 쿠폰금액
    //$amount['미수'] = $amount['order'] - $amount['receipt'] - $amount['coupon'];

    // 결제방법
    $s_receipt_way = check_pay_name_replace($od['od_settle_case'], $od);

    if ($od['od_receipt_point'] > 0)
        $s_receipt_way .= "+포인트";
    ?>

    <div class="tbl_head01 tbl_wrap">
        <strong class="sodr_nonpay">미수금 <?php echo display_price($od['od_misu']); ?></strong>

        <table>
        <caption>주문결제 내역</caption>
        <thead>
        <tr>
            <th scope="col">주문번호</th>
            <th scope="col">결제방법</th>
            <th scope="col">주문총액</th>
            <th scope="col">배송비</th>
            <th scope="col">포인트결제</th>
            <th scope="col">총결제액</th>
            <th scope="col">쿠폰</th>
            <th scope="col">주문취소</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $od['od_id']; ?></td>
            <td class="td_paybybig"><?php echo $s_receipt_way; ?></td>
            <td class="td_numbig td_numsum"><?php echo display_price($amount['order']); ?></td>
            <td class="td_numbig"><?php echo display_price($od['od_send_cost'] + $od['od_send_cost2']); ?></td>
            <td class="td_numbig"><?php echo display_point($od['od_receipt_point']); ?></td>
            <td class="td_numbig td_numincome"><?php echo number_format($amount['receipt']); ?>원</td>
            <td class="td_numbig td_numcoupon"><?php echo display_price($amount['coupon']); ?></td>
            <td class="td_numbig td_numcancel"><?php echo number_format($amount['cancel']); ?>원</td>
        </tr>
        </tbody>
        </table>
    </div>
</section>
