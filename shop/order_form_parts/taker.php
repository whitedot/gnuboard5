<section id="sod_frm_taker">
    <h2>받으시는 분</h2>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
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
                $addr_list .= '<input type="radio" name="ad_sel_addr" value="'.get_text($val1).'" id="ad_sel_addr_def">'.PHP_EOL;
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
                $addr_list .= '<input type="radio" name="ad_sel_addr" value="'.get_text($val1).'" id="ad_sel_addr_'.($i+1).'"> '.PHP_EOL.$val2.PHP_EOL;
            }

            $addr_list .= '<input type="radio" name="ad_sel_addr" value="new" id="od_sel_addr_new">'.PHP_EOL;
            $addr_list .= '<label for="od_sel_addr_new">신규배송지</label>'.PHP_EOL;

            $addr_list .='<a href="'.G5_SHOP_URL.'/orderaddress.php" id="order_address" class="btn_frmline">배송지목록</a>';
        } else {
            // 주문자와 동일
            $addr_list .= '<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">'.PHP_EOL;
            $addr_list .= '<label for="ad_sel_addr_same">주문자와 동일</label>'.PHP_EOL;
        }
        ?>
        <tr>
            <th scope="row">배송지선택</th>
            <td>
                <div class="order_choice_place">
                <?php echo $addr_list; ?>
                </div>
            </td>
        </tr>
        <?php if($is_member) { ?>
        <tr>
            <th scope="row"><label for="ad_subject">배송지명</label></th>
            <td>
                <input type="text" name="ad_subject" id="ad_subject" class="frm_input" maxlength="20">
                <input type="checkbox" name="ad_default" id="ad_default" value="1">
                <label for="ad_default">기본배송지로 설정</label>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <th scope="row"><label for="od_b_name">이름<strong class="sound_only"> 필수</strong></label></th>
            <td><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20"></td>
        </tr>
        <tr>
            <th scope="row"><label for="od_b_tel">전화번호<strong class="sound_only"> 필수</strong></label></th>
            <td><input type="text" name="od_b_tel" id="od_b_tel" required class="frm_input required" maxlength="20"></td>
        </tr>
        <tr>
            <th scope="row"><label for="od_b_hp">핸드폰</label></th>
            <td><input type="text" name="od_b_hp" id="od_b_hp" class="frm_input" maxlength="20"></td>
        </tr>
        <tr>
            <th scope="row">주소</th>
            <td id="sod_frm_addr">
                <label for="od_b_zip" class="sound_only">우편번호<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="8" maxlength="6" placeholder="우편번호">
                <button type="button" class="btn_address" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소 검색</button><br>
                <input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required" size="60" placeholder="기본주소">
                <label for="od_b_addr1" class="sound_only">기본주소<strong> 필수</strong></label><br>
                <input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input " size="60" placeholder="상세주소">
                <label for="od_b_addr2" class="sound_only">상세주소</label>
                <br>
                <input type="text" name="od_b_addr3" id="od_b_addr3" readonly="readonly" class="frm_input " size="60" placeholder="참고항목">
                <label for="od_b_addr3" class="sound_only">참고항목</label><br>
                <input type="hidden" name="od_b_addr_jibeon" value="">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="od_memo">전하실말씀</label></th>
            <td><textarea name="od_memo" id="od_memo"></textarea></td>
        </tr>
        </tbody>
        </table>
    </div>
</section>
