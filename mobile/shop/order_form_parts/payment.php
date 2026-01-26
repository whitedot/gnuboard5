<?php
$oc_cnt = $sc_cnt = 0;
if($is_member) {
    // 주문쿠폰
    $sql = " select cp_id
                from {$g5['g5_shop_coupon_table']}
                where mb_id IN ( '{$member['mb_id']}', '전체회원' )
                    and cp_method = '2'
                    and cp_start <= '".G5_TIME_YMD."'
                    and cp_end >= '".G5_TIME_YMD."'
                    and cp_minimum <= '$tot_sell_price' ";
    $res = sql_query($sql);

    for($k=0; $cp=sql_fetch_array($res); $k++) {
        if(is_used_coupon($member['mb_id'], $cp['cp_id']))
            continue;

        $oc_cnt++;
    }

    if($send_cost > 0) {
        // 배송비쿠폰
        $sql = " select cp_id
                    from {$g5['g5_shop_coupon_table']}
                    where mb_id IN ( '{$member['mb_id']}', '전체회원' )
                        and cp_method = '3'
                        and cp_start <= '".G5_TIME_YMD."'
                        and cp_end >= '".G5_TIME_YMD."'
                        and cp_minimum <= '$tot_sell_price' ";
        $res = sql_query($sql);

        for($k=0; $cp=sql_fetch_array($res); $k++) {
            if(is_used_coupon($member['mb_id'], $cp['cp_id']))
                continue;

            $sc_cnt++;
        }
    }
}
?>

<section id="sod_frm_pay">
    <h2>결제정보 입력</h2>


    <div class="odf_tbl">
        <table>
        <tbody>
        <?php if($oc_cnt > 0) { ?>
        <tr>
            <th scope="row">주문할인쿠폰</th>
            <td>
                <input type="hidden" name="od_cp_id" value="">
                <button type="button" id="od_coupon_btn" class="cp_btn1">쿠폰적용</button>
            </td>
        </tr>
        <tr>
            <th scope="row">주문할인금액</th>
            <td><span id="od_cp_price">0</span>원</td>
        </tr>
        <?php } ?>
        <?php if($sc_cnt > 0) { ?>
        <tr>
            <th scope="row">배송비할인쿠폰</th>
            <td>
                <input type="hidden" name="sc_cp_id" value="">
                <button type="button" id="sc_coupon_btn" class="cp_btn1">쿠폰적용</button>
            </td>
        </tr>
        <tr>
            <th scope="row">배송비할인금액</th>
            <td><span id="sc_cp_price">0</span>원</td>
        </tr>
        <?php } ?>
        <tr>
            <th>총 주문금액</th>
            <td><span id="od_tot_price"><?php echo number_format($tot_price); ?></span>원</td>
        </tr>
            <tr>
            <th>추가배송비</th>
            <td><span id="od_send_cost2">0</span>원 (지역에 따라 추가되는 도선료 등의 배송비입니다.)</td>
        </tr>
        </tbody>
        </table>
    </div>

    <?php
    if (!$default['de_card_point'])
        echo '<p id="sod_frm_pt_alert"><strong>무통장입금</strong> 이외의 결제 수단으로 결제하시는 경우 포인트를 적립해드리지 않습니다.</p>';

    $multi_settle = 0;
    $checked = '';

    $escrow_title = "";
    if ($default['de_escrow_use']) {
        $escrow_title = "에스크로 ";
    }

    if ($is_kakaopay_use || $default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay()) {
        echo '<div id="m_sod_frm_paysel"><ul>';
    }

    // 카카오페이
    if($is_kakaopay_use) {
        $multi_settle++;
        echo '<li><input type="radio" id="od_settle_kakaopay" name="od_settle_case" value="KAKAOPAY" '.$checked.'> <label for="od_settle_kakaopay" class="kakaopay_icon lb_icon">KAKAOPAY</label></li>'.PHP_EOL;
        $checked = '';
    }

    // 무통장입금 사용
    if ($default['de_bank_use']) {
        $multi_settle++;
        echo '<li><input type="radio" id="od_settle_bank" name="od_settle_case" value="무통장" '.$checked.'> <label for="od_settle_bank" class="lb_icon  bank_icon">무통장입금</label></li>'.PHP_EOL;
        $checked = '';
    }

    // 가상계좌 사용
    if ($default['de_vbank_use']) {
        $multi_settle++;
        echo '<li><input type="radio" id="od_settle_vbank" name="od_settle_case" value="가상계좌" '.$checked.'> <label for="od_settle_vbank" class="lb_icon vbank_icon">'.$escrow_title.'가상계좌</label></li>'.PHP_EOL;
        $checked = '';
    }

    // 계좌이체 사용
    if ($default['de_iche_use']) {
        $multi_settle++;
        echo '<li><input type="radio" id="od_settle_iche" name="od_settle_case" value="계좌이체" '.$checked.'> <label for="od_settle_iche" class="lb_icon iche_icon">'.$escrow_title. ($default['de_pg_service'] == 'toss' ? '퀵계좌이체' :'계좌이체') . '</label></li>'.PHP_EOL;
        $checked = '';
    }

    // 휴대폰 사용
    if ($default['de_hp_use']) {
        $multi_settle++;
        echo '<li><input type="radio" id="od_settle_hp" name="od_settle_case" value="휴대폰" '.$checked.'> <label for="od_settle_hp" class="lb_icon hp_icon">휴대폰</label></li>'.PHP_EOL;
        $checked = '';
    }

    // 신용카드 사용
    if ($default['de_card_use']) {
        $multi_settle++;
        echo '<li><input type="radio" id="od_settle_card" name="od_settle_case" value="신용카드" '.$checked.'> <label for="od_settle_card" class="lb_icon card_icon">신용카드</label></li>'.PHP_EOL;
        $checked = '';
    }
    
    $easypay_prints = array();

    // PG 간편결제
    if($default['de_easy_pay_use']) {
        switch($default['de_pg_service']) {
            case 'lg':
                $pg_easy_pay_name = 'PAYNOW';
                break;
            case 'inicis':
                $pg_easy_pay_name = 'KPAY';
                break;
            default:
                $pg_easy_pay_name = 'PAYCO';
                break;
        }

        $multi_settle++;

        if (in_array($default['de_pg_service'], array('kcp', 'nicepay')) && isset($default['de_easy_pay_services']) && $default['de_easy_pay_services']) {
            $de_easy_pay_service_array = explode(',', $default['de_easy_pay_services']);

            if ($default['de_pg_service'] === 'kcp') {
                if( in_array('nhnkcp_payco', $de_easy_pay_service_array) ){
                    $easypay_prints['nhnkcp_payco'] = '<li><input type="radio" id="od_settle_nhnkcp_payco" name="od_settle_case" data-pay="payco" value="간편결제"> <label for="od_settle_nhnkcp_payco" class="PAYCO nhnkcp_payco lb_icon" title="NHN_KCP - PAYCO">PAYCO</label></li>';
                }
                if( in_array('nhnkcp_naverpay', $de_easy_pay_service_array) ){
                    if(isset($default['de_easy_pay_services']) && in_array('used_nhnkcp_naverpay_point', explode(',', $default['de_easy_pay_services'])) ){
                        $easypay_prints['nhnkcp_naverpay_card'] = '<li><input type="radio" id="od_settle_nhnkcp_naverpay" name="od_settle_case" data-pay="naverpay" value="간편결제" > <label for="od_settle_nhnkcp_naverpay" class="naverpay_icon nhnkcp_naverpay lb_icon nhnkcp_icon nhnkcp_card" title="NHN_KCP - 네이버페이 카드결제">카드</label></li>';
                        
                        $easypay_prints['nhnkcp_naverpay_money'] = '<li><input type="radio" id="od_settle_nhnkcp_naverpay_money" name="od_settle_case" data-pay="naverpay" data-money="1" value="간편결제" > <label for="od_settle_nhnkcp_naverpay_money" class="naverpay_icon nhnkcp_naverpay lb_icon nhnkcp_icon nhnkcp_money" title="NHN_KCP - 네이버페이 머니/포인트 결제">머니</label></li>';
                    } else {
                        $easypay_prints['nhnkcp_naverpay_card'] = '<li><input type="radio" id="od_settle_nhnkcp_naverpay" name="od_settle_case" data-pay="naverpay" value="간편결제" > <label for="od_settle_nhnkcp_naverpay" class="naverpay_icon nhnkcp_naverpay lb_icon" title="NHN_KCP - 네이버페이 카드결제">네이버페이</label></li>';
                    }
                }
                if( in_array('nhnkcp_kakaopay', $de_easy_pay_service_array) ){
                    $easypay_prints['nhnkcp_kakaopay'] = '<li><input type="radio" id="od_settle_nhnkcp_kakaopay" name="od_settle_case" data-pay="kakaopay" value="간편결제" > <label for="od_settle_nhnkcp_kakaopay" class="kakaopay_icon nhnkcp_kakaopay lb_icon" title="NHN_KCP - 카카오페이">카카오페이</label></li>';
                }
            } else if ($default['de_pg_service'] === 'nicepay') {
                if( in_array('nicepay_samsungpay', $de_easy_pay_service_array) ){
                    $easypay_prints['nicepay_samsungpay'] = '<li><input type="radio" id="od_settle_nicepay_samsungpay" name="od_settle_case" data-pay="nice_samsungpay" value="간편결제"> <label for="od_settle_nicepay_samsungpay" class="samsung_pay nice_samsungpay lb_icon" title="NICEPAY - 삼성페이">삼성페이</label></li>';
                }
                if( in_array('nicepay_naverpay', $de_easy_pay_service_array) ){
                    $easypay_prints['nicepay_naverpay'] = '<li><input type="radio" id="od_settle_nicepay_naverpay" name="od_settle_case" data-pay="nice_naverpay" value="간편결제" > <label for="od_settle_nicepay_naverpay" class="naverpay_icon nicepay_naverpay lb_icon" title="NICEPAY - 네이버페이">네이버페이</label></li>';
                }
                if( in_array('nicepay_kakaopay', $de_easy_pay_service_array) ){
                    $easypay_prints['nicepay_kakaopay'] = '<li><input type="radio" id="od_settle_nicepay_kakaopay" name="od_settle_case" data-pay="nice_kakaopay" value="간편결제" > <label for="od_settle_nicepay_kakaopay" class="kakaopay_icon nicepay_kakaopay lb_icon" title="NICEPAY - 카카오페이">카카오페이</label></li>';
                }
                if( in_array('nicepay_paycopay', $de_easy_pay_service_array) ){
                    $easypay_prints['nicepay_paycopay'] = '<li><input type="radio" id="od_settle_nicepay_paycopay" name="od_settle_case" data-pay="nice_paycopay" value="간편결제" > <label for="od_settle_nicepay_paycopay" class="paycopay_icon nicepay_paycopay lb_icon" title="NICEPAY - 페이코">페이코</label></li>';
                }
                if( in_array('nicepay_skpay', $de_easy_pay_service_array) ){
                    $easypay_prints['nicepay_skpay'] = '<li><input type="radio" id="od_settle_nicepay_skpay" name="od_settle_case" data-pay="nice_skpay" value="간편결제" > <label for="od_settle_nicepay_skpay" class="skpay_icon nicepay_skpay lb_icon" title="NICEPAY - SK페이">SK페이</label></li>';
                }
                if( in_array('nicepay_ssgpay', $de_easy_pay_service_array) ){
                    $easypay_prints['nicepay_ssgpay'] = '<li><input type="radio" id="od_settle_nicepay_ssgpay" name="od_settle_case" data-pay="nice_ssgpay" value="간편결제" > <label for="od_settle_nicepay_ssgpay" class="ssgpay_icon nicepay_ssgpay lb_icon" title="NICEPAY - SSGPAY">SSGPAY</label></li>';
                }
                if( in_array('nicepay_lpay', $de_easy_pay_service_array) ){
                    $easypay_prints['nicepay_lpay'] = '<li><input type="radio" id="od_settle_nicepay_lpay" name="od_settle_case" data-pay="nice_lpay" value="간편결제" > <label for="od_settle_nicepay_lpay" class="lpay_icon nicepay_lpay lb_icon" title="NICEPAY - LPAY">LPAY</label></li>';
                }
            }

            if( (in_array('nhnkcp_applepay', $de_easy_pay_service_array) || in_array('nicepay_applepay', $de_easy_pay_service_array)) && preg_match('~^(?:(?:(?:Mozilla/\d\.\d\s*\()+|Mobile\s*Safari\s*\d+\.\d+(\.\d+)?\s*)(?:iPhone(?:\s+Simulator)?|iPad|iPod);\s*(?:U;\s*)?(?:[a-z]+(?:-[a-z]+)?;\s*)?CPU\s*(?:iPhone\s*)?(?:OS\s*\d+_\d+(?:_\d+)?\s*)?(?:like|comme)\s*Mac\s*O?S?\s*X(?:;\s*[a-z]+(?:-[a-z]+)?)?\)\s*)?(?:AppleWebKit/\d+(?:\.\d+(?:\.\d+)?|\s*\+)?\s*)?(?:\(KHTML,\s*(?:like|comme)\s*Gecko\s*\)\s*)?(?:Version/\d+\.\d+(?:\.\d+)?\s*)?(?:Mobile/\w+\s*)?(?:Safari/\d+\.\d+(?:\.\d+)?.*)?$~', $_SERVER['HTTP_USER_AGENT']) ){
                if ($default['de_pg_service'] === 'kcp' && in_array('nhnkcp_applepay', $de_easy_pay_service_array)) {
                    $easypay_prints['nhnkcp_applepay'] = '<li><input type="radio" id="od_settle_nhnkcp_applepay" name="od_settle_case" data-pay="applepay" value="간편결제" > <label for="od_settle_nhnkcp_applepay" class="applepay_icon nhnkcp_applepay lb_icon" title="NHN_KCP - 애플페이">애플페이</label></li>';
                } else if ($default['de_pg_service'] === 'nicepay' && in_array('nicepay_applepay', $de_easy_pay_service_array)) {
                    $easypay_prints['nicepay_applepay'] = '<li><input type="radio" id="od_settle_nicepay_applepay" name="od_settle_case" data-pay="nice_applepay" value="간편결제" > <label for="od_settle_nicepay_applepay" class="applepay_icon nicepay_applepay lb_icon" title="NICEPAY - 애플페이">애플페이</label></li>';
                }
            }
        } else {
            $easypay_prints[strtolower($pg_easy_pay_name)] = '<li><input type="radio" id="od_settle_easy_pay" name="od_settle_case" value="간편결제" '.$checked.'> <label for="od_settle_easy_pay" class="'.$pg_easy_pay_name.' lb_icon">'.$pg_easy_pay_name.'</label></li>';
        }
    }

    if( ! isset($easypay_prints['nhnkcp_naverpay']) && function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp') ){
        
        if(isset($default['de_easy_pay_services']) && in_array('used_nhnkcp_naverpay_point', explode(',', $default['de_easy_pay_services'])) ){
            $easypay_prints['nhnkcp_naverpay_card'] = '<li><input type="radio" id="od_settle_nhnkcp_naverpay" name="od_settle_case" data-pay="naverpay" value="간편결제" > <label for="od_settle_nhnkcp_naverpay" class="naverpay_icon nhnkcp_naverpay lb_icon nhnkcp_icon nhnkcp_card" title="NHN_KCP - 네이버페이 카드결제">카드</label></li>';
            
            $easypay_prints['nhnkcp_naverpay_money'] = '<li><input type="radio" id="od_settle_nhnkcp_naverpay_money" name="od_settle_case" data-pay="naverpay" data-money="1" value="간편결제" > <label for="od_settle_nhnkcp_naverpay_money" class="naverpay_icon nhnkcp_naverpay lb_icon nhnkcp_icon nhnkcp_money" title="NHN_KCP - 네이버페이 머니/포인트 결제">머니</label></li>';
        } else {
            $easypay_prints['nhnkcp_naverpay'] = '<li><input type="radio" id="od_settle_nhnkcp_naverpay" name="od_settle_case" data-pay="naverpay" value="간편결제" > <label for="od_settle_nhnkcp_naverpay" class="naverpay_icon nhnkcp_naverpay lb_icon" title="NHN_KCP - 네이버페이">네이버페이</label></li>';
        }
    }

    if($easypay_prints) {
        $multi_settle++;
        echo run_replace('shop_orderform_easypay_buttons', implode(PHP_EOL, $easypay_prints), $easypay_prints, $multi_settle);
    }

    //이니시스 삼성페이
    if($default['de_samsung_pay_use']) {
        echo '<li><input type="radio" id="od_settle_samsungpay" data-case="samsungpay" name="od_settle_case" value="삼성페이" '.$checked.'> <label for="od_settle_samsungpay" class="samsung_pay lb_icon">삼성페이</label></li>'.PHP_EOL;
        $checked = '';
    }

    //이니시스 Lpay
    if($default['de_inicis_lpay_use']) {
        echo '<li><input type="radio" id="od_settle_inicislpay" data-case="lpay" name="od_settle_case" value="lpay" '.$checked.'> <label for="od_settle_inicislpay" class="inicis_lpay">L.pay</label></li>'.PHP_EOL;
        $checked = '';
    }

    //이니시스 카카오페이
    if($default['de_inicis_kakaopay_use']) {
        echo '<li><input type="radio" id="od_settle_inicis_kakaopay" data-case="inicis_kakaopay" name="od_settle_case" value="inicis_kakaopay" '.$checked.'> <label for="od_settle_inicis_kakaopay" title="KG 이니시스 카카오페이" class="inicis_kakaopay">KG 이니시스 카카오페이</label></li>'.PHP_EOL;
        $checked = '';
    }

    echo '</ul>';

    $temp_point = 0;
    // 회원이면서 포인트사용이면
    if ($is_member && $config['cf_use_point'])
    {
        // 포인트 결제 사용 포인트보다 회원의 포인트가 크다면
        if ($member['mb_point'] >= $default['de_settle_min_point'])
        {
            $temp_point = (int)$default['de_settle_max_point'];

            if($temp_point > (int)$tot_sell_price)
                $temp_point = (int)$tot_sell_price;

            if($temp_point > (int)$member['mb_point'])
                $temp_point = (int)$member['mb_point'];

            $point_unit = (int)$default['de_settle_point_unit'];
            $temp_point = (int)((int)($temp_point / $point_unit) * $point_unit);

            echo '<div class="sod_frm_point">';
            echo '<div><input type="hidden" name="max_temp_point" value="'.$temp_point.'"><label for="od_temp_point">사용 포인트('.$point_unit.'점 단위)</label> <input type="text" id="od_temp_point" name="od_temp_point" value="0" size="10"> 점</div>';
            echo '<div id="sod_frm_pt_info"><span><strong>보유포인트</strong>'.display_point($member['mb_point']).'</span><span class="max_point_box"><strong>최대사용가능포인트</strong><em id="use_max_point">'.display_point($temp_point).'</em></span></div>';
            echo '</div>';
            $multi_settle++;
        }
    }

    if ($default['de_bank_use']) {
        // 은행계좌를 배열로 만든후
        $str = explode("\n", trim($default['de_bank_account']));
        if (count($str) <= 1)
        {
            $bank_account = '<input type="hidden" name="od_bank_account" value="'.$str[0].'">'.$str[0].PHP_EOL;
        }
        else
        {
            $bank_account = '<select name="od_bank_account" id="od_bank_account">'.PHP_EOL;
            $bank_account .= '<option value="">선택하십시오.</option>';
            for ($i=0; $i<count($str); $i++)
            {
                //$str[$i] = str_replace("\r", "", $str[$i]);
                $str[$i] = trim($str[$i]);
                $bank_account .= '<option value="'.$str[$i].'">'.$str[$i].'</option>'.PHP_EOL;
            }
            $bank_account .= '</select>'.PHP_EOL;
        }
        echo '<div id="settle_bank" style="display:none">';
        echo '<label for="od_bank_account" class="sound_only">입금할 계좌</label>';
        echo $bank_account;
        echo '<br><label for="od_deposit_name">입금자명</label> ';
        echo '<input type="text" name="od_deposit_name" id="od_deposit_name" size="10" maxlength="20">';
        echo '</div>';
    }

    if ($default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay() ) {
        echo '</div>';
    }

    if ($multi_settle == 0)
        echo '<p>결제할 방법이 없습니다.<br>운영자에게 알려주시면 감사하겠습니다.</p>';
    ?>
</section>

<?php
// 결제대행사별 코드 include (결제대행사 정보 필드 및 주분버튼)
require_once(G5_MSHOP_PATH.'/'.$default['de_pg_service'].'/orderform.2.php');

if( is_inicis_simple_pay() ){   //삼성페이 또는 L.pay 사용시
    require_once(G5_MSHOP_PATH.'/samsungpay/orderform.2.php');
}

if(function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')){  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_MSHOP_PATH.'/kcp/easypay_form.2.php');
}

if($is_kakaopay_use) {
    require_once(G5_SHOP_PATH.'/kakaopay/orderform.2.php');
}
?>
