<section id="anc_sodr_list">
    <h2>주문상품 목록</h2>
    <?php echo $pg_anchor; ?>
    <div>
        <p>
            현재 주문상태 <strong><?php echo $od['od_status'] ?></strong>
            |
            주문일시 <strong><?php echo substr($od['od_time'],0,16); ?> (<?php echo get_yoil($od['od_time']); ?>)</strong>
            |
            주문총액 <strong><?php echo number_format($od['od_cart_price'] + $od['od_send_cost'] + $od['od_send_cost2']); ?></strong>원
        </p>
        <?php if ($default['de_hope_date_use']) { ?><p>희망배송일은 <?php echo $od['od_hope_date']; ?> (<?php echo get_yoil($od['od_hope_date']); ?>) 입니다.</p><?php } ?>
        <?php if($od['od_mobile']) { ?>
        <p>모바일 쇼핑몰의 주문입니다.</p>
        <?php } ?>
    </div>

    <form name="frmorderform" method="post" action="./orderformcartupdate.php" onsubmit="return form_submit(this);">
    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    <input type="hidden" name="mb_id" value="<?php echo $od['mb_id']; ?>">
    <input type="hidden" name="od_email" value="<?php echo $od['od_email']; ?>">
    <input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
    <input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
    <input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
    <input type="hidden" name="search" value="<?php echo $search; ?>">
    <input type="hidden" name="page" value="<?php echo $page;?>">
    <input type="hidden" name="pg_cancel" value="0">

    <div>
        <table>
        <caption>주문 상품 목록</caption>
        <thead>
        <tr>
            <th scope="col">상품명</th>
            <th scope="col">
                <label for="sit_select_all">주문 상품 전체</label>
                <input type="checkbox" id="sit_select_all">
            </th>
            <th scope="col">옵션항목</th>
            <th scope="col">상태</th>
            <th scope="col">수량</th>
            <th scope="col">판매가</th>
            <th scope="col">소계</th>
            <th scope="col">쿠폰</th>
            <th scope="col">포인트</th>
            <th scope="col">배송비</th>
            <th scope="col">포인트반영</th>
            <th scope="col">재고반영</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $chk_cnt = 0;
        for($i=0; $row=sql_fetch_array($result); $i++) {
            // 상품이미지
            $image = get_it_image($row['it_id'], 50, 50);

            // 상품의 옵션정보
            $sql = " select ct_id, it_id, ct_price, ct_point, ct_qty, ct_option, ct_status, cp_price, ct_stock_use, ct_point_use, ct_send_cost, io_type, io_price
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '{$od['od_id']}'
                          and it_id = '{$row['it_id']}'
                        order by io_type asc, ct_id asc ";
            $res = sql_query($sql);
            $rowspan = sql_num_rows($res);

            // 합계금액 계산
            $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                            SUM(ct_qty) as qty
                        from {$g5['g5_shop_cart_table']}
                        where it_id = '{$row['it_id']}'
                          and od_id = '{$od['od_id']}' ";
            $sum = sql_fetch($sql);

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
                $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $od['od_id']);

                if($sendcost == 0)
                    $ct_send_cost = '무료';
            }

            for($k=0; $opt=sql_fetch_array($res); $k++) {
                if($opt['io_type'])
                    $opt_price = $opt['io_price'];
                else
                    $opt_price = $opt['ct_price'] + $opt['io_price'];

                // 소계
                $ct_price['stotal'] = $opt_price * $opt['ct_qty'];
                $ct_point['stotal'] = $opt['ct_point'] * $opt['ct_qty'];
            ?>
            <tr>
                <?php if($k == 0) { ?>
                <td rowspan="<?php echo $rowspan; ?>">
                    <a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>"><?php echo $image; ?> <?php echo stripslashes($row['it_name']); ?></a>
                    <?php if($od['od_tax_flag'] && $row['ct_notax']) echo '[비과세상품]'; ?>
                </td>
                <td rowspan="<?php echo $rowspan; ?>">
                    <label for="sit_sel_<?php echo $i; ?>"><?php echo $row['it_name']; ?> 옵션 전체선택</label>
                    <input type="checkbox" id="sit_sel_<?php echo $i; ?>" name="it_sel[]">
                </td>
                <?php } ?>
                <td>
                    <label for="ct_chk_<?php echo $chk_cnt; ?>"><?php echo get_text($opt['ct_option']); ?></label>
                    <input type="checkbox" name="ct_chk[<?php echo $chk_cnt; ?>]" id="ct_chk_<?php echo $chk_cnt; ?>" value="<?php echo $chk_cnt; ?>" class="<?php echo $i; ?>">
                    <input type="hidden" name="ct_id[<?php echo $chk_cnt; ?>]" value="<?php echo $opt['ct_id']; ?>">
                    <?php echo get_text($opt['ct_option']); ?>
                </td>
                <td class="cell-mngsmall"><?php echo $opt['ct_status']; ?></td>
                <td>
                    <label for="ct_qty_<?php echo $chk_cnt; ?>"><?php echo get_text($opt['ct_option']); ?> 수량</label>
                    <input type="text" name="ct_qty[<?php echo $chk_cnt; ?>]" id="ct_qty_<?php echo $chk_cnt; ?>" value="<?php echo $opt['ct_qty']; ?>" required class="required" size="5">
                </td>
                <td><?php echo number_format($opt_price); ?></td>
                <td><?php echo number_format($ct_price['stotal']); ?></td>
                <td><?php echo number_format($opt['cp_price']); ?></td>
                <td><?php echo number_format($ct_point['stotal']); ?></td>
                <td><?php echo $ct_send_cost; ?></td>
                <td class="cell-mngsmall"><?php echo get_yn($opt['ct_point_use']); ?></td>
                <td class="cell-mngsmall"><?php echo get_yn($opt['ct_stock_use']); ?></td>
            </tr>
            <?php
                $chk_cnt++;
            }
            ?>
        <?php
        }
        ?>
        </tbody>
        </table>
    </div>

    <div>
        <p>
            <input type="hidden" name="chk_cnt" value="<?php echo $chk_cnt; ?>">
            <strong>주문 및 장바구니 상태 변경</strong>
            <input type="submit" name="ct_status" value="주문" onclick="document.pressed=this.value">
            <input type="submit" name="ct_status" value="입금" onclick="document.pressed=this.value">
            <input type="submit" name="ct_status" value="준비" onclick="document.pressed=this.value">
            <input type="submit" name="ct_status" value="배송" onclick="document.pressed=this.value">
            <input type="submit" name="ct_status" value="완료" onclick="document.pressed=this.value">
            <input type="submit" name="ct_status" value="취소" onclick="document.pressed=this.value">
            <input type="submit" name="ct_status" value="반품" onclick="document.pressed=this.value">
            <input type="submit" name="ct_status" value="품절" onclick="document.pressed=this.value">
        </p>
    </div>

    <div>
        <p>주문, 입금, 준비, 배송, 완료는 장바구니와 주문서 상태를 모두 변경하지만, 취소, 반품, 품절은 장바구니의 상태만 변경하며, 주문서 상태는 변경하지 않습니다.</p>
        <p>개별적인(이곳에서의) 상태 변경은 모든 작업을 수동으로 처리합니다. 예를 들어 주문에서 입금으로 상태 변경시 입금액(결제금액)을 포함한 모든 정보는 수동 입력으로 처리하셔야 합니다.</p>
    </div>

    </form>

    <?php if ($od['od_mod_history']) { ?>
    <section id="sodr_qty_log">
        <h3>주문 수량변경 및 주문 전체취소 처리 내역</h3>
        <div>
            <?php echo conv_content($od['od_mod_history'], 0); ?>
        </div>
    </section>
    <?php } ?>

</section>
