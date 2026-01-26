<section id="sod_frm_taker">
    <h2>받으시는 분</h2>

    <div class="odf_list">
        <ul>
            <?php
            $addr_list = '';
            if($is_member) {
                // 배송지 이력
                $sep = chr(30);

                // 주문자와 동일
                $addr_list .= '<input type="radio" name="ad_sel_addr" value="same" id="ad_sel_addr_same">'.PHP_EOL;
                $addr_list .= '<label for="ad_sel_addr_same">주문자와 동일</label>'.PHP_EOL;

                // 기본배송지
                $sql = " select *
                            from {$g5['g5_shop_order_address_table']}
                            where mb_id = '{$member['mb_id']}'
                              and ad_default = '1' ";
                $row = sql_fetch($sql);
                if(isset($row['ad_id']) && $row['ad_id']) {
                    $val1 = $row['ad_name'].$sep.$row['ad_tel'].$sep.$row['ad_hp'].$sep.$row['ad_zip1'].$sep.$row['ad_zip2'].$sep.$row['ad_addr1'].$sep.$row['ad_addr2'].$sep.$row['ad_addr3'].$sep.$row['ad_jibeon'].$sep.$row['ad_subject'];
                    $addr_list .= '<br><input type="radio" name="ad_sel_addr" value="'.get_text($val1).'" id="ad_sel_addr_def">'.PHP_EOL;
                    $addr_list .= '<label for="ad_sel_addr_def">기본배송지</label>'.PHP_EOL;
                }

                // 최근배송지
                $sql = " select *
                            from {$g5['g5_shop_order_address_table']}
                            where mb_id = '{$member['mb_id']}'
                              and ad_default = '0'
                            order by ad_id desc
                            limit 1 ";
                $result = sql_query($sql);
                for($i=0; $row=sql_fetch_array($result); $i++) {
                    $val1 = $row['ad_name'].$sep.$row['ad_tel'].$sep.$row['ad_hp'].$sep.$row['ad_zip1'].$sep.$row['ad_zip2'].$sep.$row['ad_addr1'].$sep.$row['ad_addr2'].$sep.$row['ad_addr3'].$sep.$row['ad_jibeon'].$sep.$row['ad_subject'];
                    $val2 = '<label for="ad_sel_addr_'.($i+1).'">최근배송지('.($row['ad_subject'] ? get_text($row['ad_subject']) : get_text($row['ad_name'])).')</label>';
                    $addr_list .= '<br><input type="radio" name="ad_sel_addr" value="'.get_text($val1).'" id="ad_sel_addr_'.($i+1).'"> '.PHP_EOL.$val2.PHP_EOL;
                }

                $addr_list .= '<br><input type="radio" name="ad_sel_addr" value="new" id="od_sel_addr_new">'.PHP_EOL;
                $addr_list .= '<label for="od_sel_addr_new">신규배송지</label>'.PHP_EOL;

                $addr_list .='<a href="'.G5_SHOP_URL.'/orderaddress.php" id="order_address">배송지목록</a>';
            } else {
                // 주문자와 동일
                $addr_list .= '<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">'.PHP_EOL;
                $addr_list .= '<label for="ad_sel_addr_same">주문자와 동일</label>'.PHP_EOL;
            }
            ?>
            <li class="dlv_slt">
                <strong>배송지선택</strong>
                <div><?php echo $addr_list; ?></div>
            </li>
            <?php if($is_member) { ?>
            <li>
                <label for="ad_subject">배송지명</label>
                <input type="text" name="ad_subject" id="ad_subject" class="frm_input" maxlength="20">
                <input type="checkbox" name="ad_default" id="ad_default" value="1">
                <label for="ad_default" class="ad_default">기본배송지로 설정</label>
                
            </li>
            <?php
            }
            ?>
            <li>
                <label for="od_b_name">이름<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20">
            </li>
            <li>
                <label for="od_b_tel">전화번호<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="od_b_tel" id="od_b_tel" required class="frm_input required" maxlength="20">
            </li>
            <li>
                <label for="od_b_hp">핸드폰</label>
                <input type="text" name="od_b_hp" id="od_b_hp" class="frm_input" maxlength="20">
            </li>
            <li>
                <strong>주소</strong>
                <label for="od_b_zip" class="sound_only">우편번호<strong class="sound_only"> 필수</strong></label>
                <span class="add_num"><input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="5" maxlength="6">
                <button type="button" class="btn_frmline  btn_addsch" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소 검색</button></span>
                <label for="od_b_addr1" class="sound_only">기본주소<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required">
                <label for="od_b_addr2" class="sound_only">상세주소</label>
                <input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address">
                <label for="od_b_addr3" class="sound_only">참고항목</label>
                <input type="text" name="od_b_addr3" id="od_b_addr3" class="frm_input frm_address" readonly="readonly">
                <input type="hidden" name="od_b_addr_jibeon" value="">
            </li>
            <li>
                <label for="od_memo">전하실 말씀</label>
                <textarea name="od_memo" id="od_memo"></textarea>
            </li>
        </ul>
    </div>
</section>
