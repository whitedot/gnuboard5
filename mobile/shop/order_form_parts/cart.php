<div id="sod_approval_frm">
<?php
ob_start();
?>

    <ul class="sod_list">
        <?php
        $tot_point = 0;
        $tot_sell_price = 0;

        $goods = $goods_it_id = "";
        $goods_count = -1;

        // $s_cart_id 로 현재 장바구니 자료 쿼리
        $sql = " select a.ct_id,
                        a.it_id,
                        a.it_name,
                        a.ct_price,
                        a.ct_point,
                        a.ct_qty,
                        a.ct_status,
                        a.ct_send_cost,
                        a.it_sc_type,
                        b.ca_id,
                        b.ca_id2,
                        b.ca_id3,
                        b.it_notax
                   from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
                  where a.od_id = '$s_cart_id'
                    and a.ct_select = '1' ";
        $sql .= " group by a.it_id ";
        $sql .= " order by a.ct_id ";
        $result = sql_query($sql);

        $good_info = '';
        $it_send_cost = 0;
        $it_cp_count = 0;

        $comm_tax_mny = 0; // 과세금액
        $comm_vat_mny = 0; // 부가세
        $comm_free_mny = 0; // 면세금액
        $tot_tax_mny = 0;

        // 토스페이먼츠 escrowProducts 배열 생성
        $escrow_products = array();

        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            // 합계금액 계산
            $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                            SUM(ct_point * ct_qty) as point,
                            SUM(ct_qty) as qty
                        from {$g5['g5_shop_cart_table']}
                        where it_id = '{$row['it_id']}'
                          and od_id = '$s_cart_id' ";
            $sum = sql_fetch($sql);

            if (!$goods)
            {
                //$goods = addslashes($row[it_name]);
                //$goods = get_text($row[it_name]);
                $goods = preg_replace("/\?|\'|\"|\||\ Jal|\,|\&|\;/", "", $row['it_name']);
                $goods_it_id = $row['it_id'];
            }
            $goods_count++;

            // 에스크로 상품정보
            if($default['de_escrow_use']) {
                if ($i>0)
                    $good_info .= chr(30);
                $good_info .= "seq=".($i+1).chr(31);
                $good_info .= "ordr_numb={$od_id}_".sprintf("%04d", $i).chr(31);
                $good_info .= "good_name=".addslashes($row['it_name']).chr(31);
                $good_info .= "good_cntx=".$row['ct_qty'].chr(31);
                $good_info .= "good_amtx=".$row['ct_price'].chr(31);
            }

            $a1 = '<strong>';
            $a2 = '</strong>';
            $image_width = 80;
            $image_height = 80;
            $image = get_it_image($row['it_id'], $image_width, $image_height);

            $it_name = $a1 . stripslashes($row['it_name']) . $a2;
            $it_options = print_item_options($row['it_id'], $s_cart_id);


            // 복합과세금액
            if($default['de_tax_flag_use']) {
                if($row['it_notax']) {
                    $comm_free_mny += $sum['price'];
                } else {
                    $tot_tax_mny += $sum['price'];
                }
            }

            $point      = $sum['point'];
            $sell_price = $sum['price'];

            // 토스페이먼츠 escrowProducts 배열에 상품 정보 추가
            $escrow_products[] = array(
                'id'        => $row['ct_id'],
                'name'      => $row['it_name'],
                'code'      => $row['it_id'],
                'unitPrice' => (int) $row['ct_price'],
                'quantity'  => (int) $row['ct_qty']
            );
            
            $cp_button = '';
            // 쿠폰
            if($is_member) {
                $cp_count = 0;

                $sql = " select cp_id
                            from {$g5['g5_shop_coupon_table']}
                            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
                              and cp_start <= '".G5_TIME_YMD."'
                              and cp_end >= '".G5_TIME_YMD."'
                              and cp_minimum <= '$sell_price'
                              and (
                                    ( cp_method = '0' and cp_target = '{$row['it_id']}' )
                                    OR
                                    ( cp_method = '1' and ( cp_target IN ( '{$row['ca_id']}', '{$row['ca_id2']}', '{$row['ca_id3']}' ) ) )
                                  ) ";
                $res = sql_query($sql);

                for($k=0; $cp=sql_fetch_array($res); $k++) {
                    if(is_used_coupon($member['mb_id'], $cp['cp_id']))
                        continue;

                    $cp_count++;
                }

                if($cp_count) {
                    $cp_button = '<div class="li_cp"><button type="button" class="cp_btn">쿠폰적용</button></div>';
                    $it_cp_count++;
                }
            }

            // 배송비
            switch($row['ct_send_cost'])
            {
                case 1:
                    $ct_send_cost = '착불';
                    break;
                case 2:
                    $ct_send_cost = '무료';
                    break;
                default:
                    $ct_send_cost = '선불';
                    break;
            }

            // 조건부무료
            if($row['it_sc_type'] == 2) {
                $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $s_cart_id);

                if($sendcost == 0)
                    $ct_send_cost = '무료';
            }
        ?>

        <li class="sod_li">
            <input type="hidden" name="it_id[<?php echo $i; ?>]"    value="<?php echo $row['it_id']; ?>">
            <input type="hidden" name="it_name[<?php echo $i; ?>]"  value="<?php echo get_text($row['it_name']); ?>">
            <input type="hidden" name="it_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
            <?php if($default['de_tax_flag_use']) { ?>
            <input type="hidden" name="it_notax[<?php echo $i; ?>]" value="<?php echo $row['it_notax']; ?>">
            <?php } ?>
            <input type="hidden" name="cp_id[<?php echo $i; ?>]" value="">
            <input type="hidden" name="cp_price[<?php echo $i; ?>]" value="0">
            <div class="li_name">
                <?php echo $it_name; ?>
            </div>
            <div class="li_op_wr">
                <span class="total_img"><?php echo $image; ?></span>
                <div class="s_opt"><?php echo $it_options; ?></div>
                <div class="li_mod" ><?php echo $cp_button; ?></div>
            </div>

            <div class="li_prqty">
                <span class="prqty_price li_prqty_sp"><span>판매가 </span><?php echo number_format($row['ct_price']); ?></span>
                <span class="prqty_qty li_prqty_sp"><span>수량 </span><?php echo number_format($sum['qty']); ?></span>
                <span class="prqty_sc li_prqty_sp"><span>배송비 </span><?php echo $ct_send_cost; ?></span>
                 <span class="total_point li_prqty_sp"><span>적립포인트 </span><strong><?php echo number_format($sum['point']); ?></strong></span>

            </div>
            <div class="total_price total_span"><span>주문금액 </span><strong><?php echo number_format($sell_price); ?></strong></div>

        </li>

        <?php
            $tot_point      += $point;
            $tot_sell_price += $sell_price;
        } // for 끝

        if ($i == 0) {
            //echo '<li class="empty_li">장바구니에 담긴 상품이 없습니다.</li>';
            alert('장바구니가 비어 있습니다.', G5_SHOP_URL.'/cart.php');
        } else {
            // 배송비 계산
            $send_cost = get_sendcost($s_cart_id);
        }

        // 복합과세처리
        if($default['de_tax_flag_use']) {
            $comm_tax_mny = round(($tot_tax_mny + $send_cost) / 1.1);
            $comm_vat_mny = ($tot_tax_mny + $send_cost) - $comm_tax_mny;
        }
        ?>
    </ul>

    <?php if ($goods_count) $goods .= ' 외 '.$goods_count.'건'; ?>


    <!-- 주문상품 합계 시작 { -->
    <div class="sod_ta_wr">
        <dl id="m_sod_bsk_tot">
            <dt class="sod_bsk_sell">주문</dt>
            <dd class="sod_bsk_sell"><strong><?php echo number_format($tot_sell_price); ?> 원</strong></dd>
            <?php if($it_cp_count > 0) { ?>
            <dt class="sod_bsk_coupon">쿠폰</dt>
            <dd class="sod_bsk_coupon"><strong id="ct_tot_coupon">0 원</strong></dd>
            <?php } ?>
            <dt class="sod_bsk_dvr">배송비</dt>
            <dd class="sod_bsk_dvr"><strong><?php echo number_format($send_cost); ?> 원</strong></dd>

            <dt class="sod_bsk_point">포인트</dt>
            <dd class="sod_bsk_point"><strong><?php echo number_format($tot_point); ?> 점</strong></dd>
            <dt class="sod_bsk_cnt">총계</dt>
            <dd class="sod_bsk_cnt">
                <?php $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 ?>
                <strong id="ct_tot_price"><?php echo number_format($tot_price); ?></strong> 원
            </dd>
        </dl>
    </div>

    <!-- } 주문상품 합계 끝 -->

<?php
$content = ob_get_contents();
ob_end_clean();

// 결제대행사별 코드 include (결제등록 필드)
require_once(G5_MSHOP_PATH.'/'.$default['de_pg_service'].'/orderform.1.php');

if( is_inicis_simple_pay() ){   //이니시스 삼성페이 또는 lpay 사용시
    require_once(G5_MSHOP_PATH.'/samsungpay/orderform.1.php');
}

if(function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')){  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_MSHOP_PATH.'/kcp/easypay_form.1.php');
}
?>
</div>
