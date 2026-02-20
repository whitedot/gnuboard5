<section>
    <h2>결제상세정보</h2>
    <?php echo $pg_anchor; ?>

    <form name="frmorderreceiptform" action="./orderformreceiptupdate.php" method="post" autocomplete="off">
    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    <input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
    <input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
    <input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
    <input type="hidden" name="search" value="<?php echo $search; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="od_name" value="<?php echo $od['od_name']; ?>">
    <input type="hidden" name="od_hp" value="<?php echo $od['od_hp']; ?>">
    <input type="hidden" name="od_tno" value="<?php echo $od['od_tno']; ?>">
    <input type="hidden" name="od_escrow" value="<?php echo $od['od_escrow']; ?>">
    <input type="hidden" name="od_pg" value="<?php echo $od['od_pg']; ?>">

    <div>

        <section id="anc_sodr_chk">
            <h3>결제상세정보 확인</h3>

            
                <table>
                <caption>결제상세정보</caption>
                <colgroup>
                    <col>
                    <col>
                </colgroup>
                <tbody>
                <?php if ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체') { ?>
                <?php if ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '가상계좌') { ?>
                <tr>
                    <th scope="row">계좌번호</th>
                    <td><?php echo get_text($od['od_bank_account']); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="row"><?php echo $od['od_settle_case']; ?> 입금액</th>
                    <td><?php echo display_price($od['od_receipt_price']); ?></td>
                </tr>
                <tr>
                    <th scope="row">입금자</th>
                    <td><?php echo get_text($print_od_deposit_name); ?></td>
                </tr>
                <tr>
                    <th scope="row">입금확인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == 0) { ?>입금 확인일시를 체크해 주세요.
                        <?php } else { ?><?php echo $od['od_receipt_time']; ?> (<?php echo get_yoil($od['od_receipt_time']); ?>)
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '휴대폰') { ?>
                <tr>
                    <th scope="row">휴대폰번호</th>
                    <td><?php echo get_text($od['od_bank_account']); ?></td>
                    </tr>
                <tr>
                    <th scope="row"><?php echo $od['od_settle_case']; ?> 결제액</th>
                    <td><?php echo display_price($od['od_receipt_price']); ?></td>
                </tr>
                <tr>
                    <th scope="row">결제 확인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == 0) { ?>결제 확인일시를 체크해 주세요.
                        <?php } else { ?><?php echo $od['od_receipt_time']; ?> (<?php echo get_yoil($od['od_receipt_time']); ?>)
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '신용카드') { ?>
                <tr>
                    <th scope="row">신용카드 결제금액</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>0원
                        <?php } else { ?><?php echo display_price($od['od_receipt_price']); ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">카드 승인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>신용카드 결제 일시 정보가 없습니다.
                        <?php } else { ?><?php echo substr($od['od_receipt_time'], 0, 20); ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == 'KAKAOPAY') { ?>
                <tr>
                    <th scope="row">KAKOPAY 결제금액</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>0원
                        <?php } else { ?><?php echo display_price($od['od_receipt_price']); ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">KAKAOPAY 승인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>신용카드 결제 일시 정보가 없습니다.
                        <?php } else { ?><?php echo substr($od['od_receipt_time'], 0, 20); ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '간편결제' || ($od['od_pg'] == 'inicis' && is_inicis_order_pay($od['od_settle_case']) ) ) { ?>
                <tr>
                    <th scope="row"><?php echo $s_receipt_way; ?> 결제금액</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>0원
                        <?php } else { ?><?php echo display_price($od['od_receipt_price']); ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo $s_receipt_way; ?> 승인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") { echo $s_receipt_way; ?> 결제 일시 정보가 없습니다.
                        <?php } else { ?><?php echo substr($od['od_receipt_time'], 0, 20); ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] != '무통장') { ?>
                <tr>
                    <th scope="row">결제대행사 링크</th>
                    <td>
                        <?php
                        if ($od['od_settle_case'] != '무통장') {
                            switch($od['od_pg']) {
                                case 'lg':
                                    $pg_url  = 'https://app.tosspayments.com';
                                    $pg_test = '토스페이먼츠(구버전)';
                                    if ($default['de_card_test']) {
                                        $pg_url = 'https://pgweb.tosspayments.com/tmert';
                                        $pg_test .= ' 테스트 ';
                                    }
                                    break;
                                case 'inicis':
                                    $pg_url  = 'https://iniweb.inicis.com/';
                                    $pg_test = 'KG이니시스';
                                    break;
                                case 'KAKAOPAY':
                                    $pg_url  = 'https://mms.cnspay.co.kr';
                                    $pg_test = 'KAKAOPAY';
                                    break;
                                case 'nicepay':
                                    $pg_url  = 'https://npg.nicepay.co.kr/';
                                    $pg_test = 'NICEPAY';
                                    break;
                                case 'toss':
                                    $pg_url  = 'https://app.tosspayments.com';
                                    $pg_test = '토스페이먼츠 ';
                                    break;
                                default:
                                    $pg_url  = 'http://admin8.kcp.co.kr';
                                    $pg_test = 'KCP';
                                    if ($default['de_card_test']) {
                                        // 로그인 아이디 / 비번
                                        // 일반 : test1234 / test12345
                                        // 에스크로 : escrow / escrow913
                                        $pg_url = 'http://testadmin8.kcp.co.kr';
                                        $pg_test .= ' 테스트 ';
                                    }

                                }
                            echo "<a href=\"{$pg_url}\" target=\"_blank\">{$pg_test}바로가기</a><br>";
                        }
                        //------------------------------------------------------------------------------
                        ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if($od['od_tax_flag']) { ?>
                <tr>
                    <th scope="row">과세공급가액</th>
                    <td><?php echo display_price($od['od_tax_mny']); ?></td>
                </tr>
                <tr>
                    <th scope="row">과세부가세액</th>
                    <td><?php echo display_price($od['od_vat_mny']); ?></td>
                </tr>
                <tr>
                    <th scope="row">비과세공급가액</th>
                    <td><?php echo display_price($od['od_free_mny']); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="row">주문금액할인</th>
                    <td><?php echo display_price($od['od_coupon']); ?></td>
                </tr>
                <tr>
                    <th scope="row">포인트</th>
                    <td><?php echo display_point($od['od_receipt_point']); ?></td>
                </tr>
                <tr>
                    <th scope="row">결제취소/환불액</th>
                    <td><?php echo display_price($od['od_refund_price']); ?></td>
                </tr>
                <?php if ($od['od_invoice']) { ?>
                <tr>
                    <th scope="row">배송회사</th>
                    <td><?php echo $od['od_delivery_company']; ?> <?php echo get_delivery_inquiry($od['od_delivery_company'], $od['od_invoice'], 'dvr_link'); ?></td>
                </tr>
                <tr>
                    <th scope="row">운송장번호</th>
                    <td><?php echo $od['od_invoice']; ?></td>
                </tr>
                <tr>
                    <th scope="row">배송일시</th>
                    <td><?php echo is_null_time($od['od_invoice_time']) ? "" : $od['od_invoice_time']; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="row"><label for="od_send_cost">배송비</label></th>
                    <td>
                        <input type="text" name="od_send_cost" value="<?php echo $od['od_send_cost']; ?>" id="od_send_cost" size="10"> 원
                    </td>
                </tr>
                <?php if($od['od_send_coupon']) { ?>
                <tr>
                    <th scope="row">배송비할인</th>
                    <td><?php echo display_price($od['od_send_coupon']); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="row"><label for="od_send_cost2">추가배송비</label></th>
                    <td>
                        <input type="text" name="od_send_cost2" value="<?php echo $od['od_send_cost2']; ?>" id="od_send_cost2" size="10"> 원
                    </td>
                </tr>
                <?php
                if ($od['od_misu'] == 0 && $od['od_receipt_price'] && ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체')) {
                ?>
                <tr>
                    <th scope="row">현금영수증</th>
                    <td>
                    <?php
                    if ($od['od_cash']) {
                        if($od['od_pg'] == 'lg') {
                            require G5_SHOP_PATH.'/settle_lg.inc.php';

                            switch($od['od_settle_case']) {
                                case '계좌이체':
                                    $trade_type = 'BANK';
                                    break;
                                case '가상계좌':
                                    $trade_type = 'CAS';
                                    break;
                                default:
                                    $trade_type = 'CR';
                                    break;
                            }
                            $cash_receipt_script = 'javascript:showCashReceipts(\''.$LGD_MID.'\',\''.$od['od_id'].'\',\''.$od['od_casseqno'].'\',\''.$trade_type.'\',\''.$CST_PLATFORM.'\');';
                        } else if($od['od_pg'] == 'toss') {
                            $cash_receipt_script = 'window.open(\'https://dashboard.tosspayments.com/receipt/mids/si_'.$config['cf_lg_mid'].'/orders/'.$od['od_id'].'/cash-receipt?ref=dashboard\',\'receipt\',\'width=430,height=700\');';
                        } else if($od['od_pg'] == 'inicis') {
                            $cash = unserialize($od['od_cash_info']);
                            $cash_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/Cash_mCmReceipt.jsp?noTid='.$cash['TID'].'&clpaymethod=22\',\'showreceipt\',\'width=380,height=540,scrollbars=no,resizable=no\');';
                        } else if($od['od_pg'] == 'nicepay') {
                            
                            $od_tid = $od['od_tno'];
                            $cash_type = 0;

                            if (! $od_tid) {
                                $cash = unserialize($od['od_cash_info']);
                                $od_tid = isset($cash['TID']) ? $cash['TID'] : '';
                                $cash_type = $od_tid ? 1 : 0;
                            }

                            $cash_receipt_script = 'window.open(\'https://npg.nicepay.co.kr/issue/IssueLoader.do?type='.$cash_type.'&TID='.$od_tid.'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                        } else {
                            require G5_SHOP_PATH.'/settle_kcp.inc.php';

                            $cash = unserialize($od['od_cash_info']);
                            $cash_receipt_script = 'window.open(\''.G5_CASH_RECEIPT_URL.$default['de_kcp_mid'].'&orderid='.$od_id.'&bill_yn=Y&authno='.$cash['receipt_no'].'\', \'taxsave_receipt\', \'width=360,height=647,scrollbars=0,menus=0\');';
                        }
                    ?>
                        <a href="javascript:;" onclick="<?php echo $cash_receipt_script; ?>">현금영수증 확인</a>
                    <?php } else { ?>
                        <a href="javascript:;" onclick="window.open('<?php echo G5_SHOP_URL; ?>/taxsave.php?od_id=<?php echo $od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');">현금영수증 발급</a>
                    <?php } ?>
                    </td>
                </tr>
                <?php
                }
                ?>
                </tbody>
                </table>
            
        </section>

        <section id="anc_sodr_paymo">
            <h3>결제상세정보 수정</h3>

            
                <table>
                <caption>결제상세정보 수정</caption>
                <colgroup>
                    <col>
                    <col>
                </colgroup>
                <tbody>
                <?php if ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체') { ########## 시작?>
                <?php
                if ($od['od_settle_case'] == '무통장')
                {
                    // 은행계좌를 배열로 만든후
                    $str = explode("\n", $default['de_bank_account']);
                    $bank_account = '<select name="od_bank_account" id="od_bank_account">'.PHP_EOL;
                    $bank_account .= '<option value="">선택하십시오</option>'.PHP_EOL;
                    for ($i=0; $i<count($str); $i++) {
                        $str[$i] = str_replace("\r", "", $str[$i]);
                        $bank_account .= '<option value="'.$str[$i].'" '.get_selected($od['od_bank_account'], $str[$i]).'>'.$str[$i].'</option>'.PHP_EOL;
                    }
                    $bank_account .= '</select> ';
                }
                else if ($od['od_settle_case'] == '가상계좌')
                    $bank_account = $od['od_bank_account'].'<input type="hidden" name="od_bank_account" value="'.$od['od_bank_account'].'">';
                else if ($od['od_settle_case'] == '계좌이체')
                    $bank_account = $od['od_settle_case'];
                ?>

                <?php if ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '가상계좌') { ?>
                <tr>
                    <th scope="row"><label for="od_bank_account">계좌번호</label></th>
                    <td><?php echo $bank_account; ?></td>
                </tr>
                <?php } ?>

                <tr>
                    <th scope="row"><label for="od_receipt_price"><?php echo $od['od_settle_case']; ?> 입금액</label></th>
                    <td>
                        <?php echo $html_receipt_chk; ?>
                        <input type="text" name="od_receipt_price" value="<?php echo $od['od_receipt_price']; ?>" id="od_receipt_price"> 원
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_deposit_name">입금자명</label></th>
                    <td>
                        <?php if ($config['cf_sms_use'] && $default['de_sms_use4']) { ?>
                        <input type="checkbox" name="od_sms_ipgum_check" id="od_sms_ipgum_check">
                        <label for="od_sms_ipgum_check">SMS 입금 문자전송</label>
                        <br>
                        <?php } ?>

                        <input type="text" name="od_deposit_name" value="<?php echo get_text($od['od_deposit_name']); ?>" id="od_deposit_name">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_receipt_time">입금 확인일시</label></th>
                    <td>
                        <input type="checkbox" name="od_bank_chk" id="od_bank_chk" value="<?php echo date("Y-m-d H:i:s", G5_SERVER_TIME); ?>" onclick="if (this.checked == true) this.form.od_receipt_time.value=this.form.od_bank_chk.value; else this.form.od_receipt_time.value = this.form.od_receipt_time.defaultValue;">
                        <label for="od_bank_chk">현재 시간으로 설정</label><br>
                        <input type="text" name="od_receipt_time" value="<?php echo is_null_time($od['od_receipt_time']) ? "" : $od['od_receipt_time']; ?>" id="od_receipt_time" maxlength="19">
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '휴대폰') { ?>
                <tr>
                    <th scope="row">휴대폰번호</th>
                    <td><?php echo get_text($od['od_bank_account']); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_receipt_price"><?php echo $od['od_settle_case']; ?> 결제액</label></th>
                    <td>
                        <?php echo $html_receipt_chk; ?>
                        <input type="text" name="od_receipt_price" value="<?php echo $od['od_receipt_price']; ?>" id="od_receipt_price"> 원
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="op_receipt_time">휴대폰 결제일시</label></th>
                    <td>
                        <input type="checkbox" name="od_hp_chk" id="od_hp_chk" value="<?php echo date("Y-m-d H:i:s", G5_SERVER_TIME); ?>" onclick="if (this.checked == true) this.form.od_receipt_time.value=this.form.od_hp_chk.value; else this.form.od_receipt_time.value = this.form.od_receipt_time.defaultValue;">
                        <label for="od_hp_chk">현재 시간으로 설정</label><br>
                        <input type="text" name="od_receipt_time" value="<?php echo is_null_time($od['od_receipt_time']) ? "" : $od['od_receipt_time']; ?>" id="op_receipt_time" size="19" maxlength="19">
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '신용카드') { ?>
                <tr>
                    <th scope="row"><label for="od_receipt_price">신용카드 결제금액</label></th>
                    <td>
                        <?php echo $html_receipt_chk; ?>
                        <input type="text" name="od_receipt_price" id="od_receipt_price" value="<?php echo $od['od_receipt_price']; ?>" size="10"> 원
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_receipt_time">카드 승인일시</label></th>
                    <td>
                        <input type="checkbox" name="od_card_chk" id="od_card_chk" value="<?php echo date("Y-m-d H:i:s", G5_SERVER_TIME); ?>" onclick="if (this.checked == true) this.form.od_receipt_time.value=this.form.od_card_chk.value; else this.form.od_receipt_time.value = this.form.od_receipt_time.defaultValue;">
                        <label for="od_card_chk">현재 시간으로 설정</label><br>
                        <input type="text" name="od_receipt_time" value="<?php echo is_null_time($od['od_receipt_time']) ? "" : $od['od_receipt_time']; ?>" id="od_receipt_time" size="19" maxlength="19">
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == 'KAKAOPAY') { ?>
                <tr>
                    <th scope="row"><label for="od_receipt_price">KAKAOPAY 결제금액</label></th>
                    <td>
                        <?php echo $html_receipt_chk; ?>
                        <input type="text" name="od_receipt_price" id="od_receipt_price" value="<?php echo $od['od_receipt_price']; ?>" size="10"> 원
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_receipt_time">KAKAOPAY 승인일시</label></th>
                    <td>
                        <input type="checkbox" name="od_card_chk" id="od_card_chk" value="<?php echo date("Y-m-d H:i:s", G5_SERVER_TIME); ?>" onclick="if (this.checked == true) this.form.od_receipt_time.value=this.form.od_card_chk.value; else this.form.od_receipt_time.value = this.form.od_receipt_time.defaultValue;">
                        <label for="od_card_chk">현재 시간으로 설정</label><br>
                        <input type="text" name="od_receipt_time" value="<?php echo is_null_time($od['od_receipt_time']) ? "" : $od['od_receipt_time']; ?>" id="od_receipt_time" size="19" maxlength="19">
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '간편결제' || ($od['od_pg'] == 'inicis' && is_inicis_order_pay($od['od_settle_case']) )) { ?>
                <tr>
                    <th scope="row"><label for="od_receipt_price"><?php echo $s_receipt_way; ?> 결제금액</label></th>
                    <td>
                        <?php echo $html_receipt_chk; ?>
                        <input type="text" name="od_receipt_price" id="od_receipt_price" value="<?php echo $od['od_receipt_price']; ?>" size="10"> 원
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_receipt_time"><?php echo $s_receipt_way; ?> 승인일시</label></th>
                    <td>
                        <input type="checkbox" name="od_card_chk" id="od_card_chk" value="<?php echo date("Y-m-d H:i:s", G5_SERVER_TIME); ?>" onclick="if (this.checked == true) this.form.od_receipt_time.value=this.form.od_card_chk.value; else this.form.od_receipt_time.value = this.form.od_receipt_time.defaultValue;">
                        <label for="od_card_chk">현재 시간으로 설정</label><br>
                        <input type="text" name="od_receipt_time" value="<?php echo is_null_time($od['od_receipt_time']) ? "" : $od['od_receipt_time']; ?>" id="od_receipt_time" size="19" maxlength="19">
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <th scope="row"><label for="od_receipt_point">포인트 결제액</label></th>
                    <td><input type="text" name="od_receipt_point" value="<?php echo $od['od_receipt_point']; ?>" id="od_receipt_point" size="10"> 점</td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_refund_price">결제취소/환불 금액</label></th>
                    <td>
                        <input type="text" name="od_refund_price" value="<?php echo $od['od_refund_price']; ?>" id="od_refund_price" size="10"> 원
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_invoice">운송장번호</label></th>
                    <td>
                        <?php if ($config['cf_sms_use'] && $default['de_sms_use5']) { ?>
                        <input type="checkbox" name="od_sms_baesong_check" id="od_sms_baesong_check">
                        <label for="od_sms_baesong_check">SMS 배송 문자전송</label>
                        <br>
                        <?php } ?>

                        <input type="text" name="od_invoice" value="<?php echo $od['od_invoice']; ?>" id="od_invoice">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_delivery_company">배송회사</label></th>
                    <td>
                        <input type="checkbox" id="od_delivery_chk" value="<?php echo $default['de_delivery_company']; ?>" onclick="chk_delivery_company()">
                        <label for="od_delivery_chk">기본 배송회사로 설정</label><br>
                        <input type="text" name="od_delivery_company" id="od_delivery_company" value="<?php echo $od['od_delivery_company']; ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_invoice_time">배송일시</label></th>
                    <td>
                        <input type="checkbox" id="od_invoice_chk" value="<?php echo date("Y-m-d H:i:s", G5_SERVER_TIME); ?>" onclick="chk_invoice_time()">
                        <label for="od_invoice_chk">현재 시간으로 설정</label><br>
                        <input type="text" name="od_invoice_time" id="od_invoice_time" value="<?php echo is_null_time($od['od_invoice_time']) ? "" : $od['od_invoice_time']; ?>" maxlength="19">
                    </td>
                </tr>

                <?php if ($config['cf_email_use']) { ?>
                <tr>
                    <th scope="row"><label for="od_send_mail">메일발송</label></th>
                    <td>
                        <?php echo help("주문자님께 입금, 배송내역을 메일로 발송합니다.\n메일발송시 상점메모에 기록됩니다."); ?>
                        <input type="checkbox" name="od_send_mail" value="1" id="od_send_mail"> 메일발송
                    </td>
                </tr>
                <?php } ?>

                </tbody>
                </table>
            
        </section>

    </div>

    <div>
        <input type="submit" value="결제/배송내역 수정">
        <?php if($od['od_status'] == '주문' && $od['od_misu'] > 0) { ?>
        <a href="./personalpayform.php?popup=yes&amp;od_id=<?php echo $od_id; ?>" id="personalpay_add">개인결제추가</a>
        <?php } ?>
        <?php if($od['od_misu'] < 0 && ($od['od_receipt_price'] - $od['od_refund_price']) > 0 && ($od['od_settle_case'] == '신용카드' || $od['od_settle_case'] == '계좌이체' || $od['od_settle_case'] == 'KAKAOPAY')) { ?>
        <a href="./orderpartcancel.php?od_id=<?php echo $od_id; ?>" id="orderpartcancel"><?php echo $od['od_settle_case']; ?> 부분취소</a>
        <?php } ?>
        <a href="./orderlist.php?<?php echo $qstr; ?>">목록</a>
    </div>
    </form>
</section>
