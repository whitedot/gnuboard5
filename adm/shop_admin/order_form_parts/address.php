<section>
    <h2 class="section-title">주문자/배송지 정보</h2>
    <?php echo $pg_anchor; ?>

    <form name="frmorderform3" action="./orderformupdate.php" method="post">
    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    <input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
    <input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
    <input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
    <input type="hidden" name="search" value="<?php echo $search; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="mod_type" value="info">

    <div class="compare_wrap">

        <section id="anc_sodr_orderer" class="compare_left">
            <h3>주문하신 분</h3>

            <div class="form-card">
                <table>
                <caption>주문자/배송지 정보</caption>
                <colgroup>
                    <col class="col-4">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label for="od_name"><span class="sr-only">주문하신 분 </span>이름</label></th>
                    <td><input type="text" name="od_name" value="<?php echo get_text($od['od_name']); ?>" id="od_name" required class="form-input required"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_tel"><span class="sr-only">주문하신 분 </span>전화번호</label></th>
                    <td><input type="text" name="od_tel" value="<?php echo get_text($od['od_tel']); ?>" id="od_tel" required class="form-input required"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_hp"><span class="sr-only">주문하신 분 </span>핸드폰</label></th>
                    <td><input type="text" name="od_hp" value="<?php echo get_text($od['od_hp']); ?>" id="od_hp" class="form-input"></td>
                </tr>
                <tr>
                    <th scope="row"><span class="sr-only">주문하시는 분 </span>주소</th>
                    <td>
                        <label for="od_zip" class="sr-only">우편번호</label>
                        <input type="text" name="od_zip" value="<?php echo get_text($od['od_zip1']).get_text($od['od_zip2']); ?>" id="od_zip" required class="form-input required" size="5">
                        <button type="button" class="btn-inline" onclick="win_zip('frmorderform3', 'od_zip', 'od_addr1', 'od_addr2', 'od_addr3', 'od_addr_jibeon');">주소 검색</button><br>
                        <span id="od_win_zip" style="display:block"></span>
                        <input type="text" name="od_addr1" value="<?php echo get_text($od['od_addr1']); ?>" id="od_addr1" required class="form-input required" size="35">
                        <label for="od_addr1">기본주소</label><br>
                        <input type="text" name="od_addr2" value="<?php echo get_text($od['od_addr2']); ?>" id="od_addr2" class="form-input" size="35">
                        <label for="od_addr2">상세주소</label>
                        <br>
                        <input type="text" name="od_addr3" value="<?php echo get_text($od['od_addr3']); ?>" id="od_addr3" class="form-input" size="35">
                        <label for="od_addr3">참고항목</label>
                        <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($od['od_addr_jibeon']); ?>"><br>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_email"><span class="sr-only">주문하신 분 </span>E-mail</label></th>
                    <td><input type="text" name="od_email" value="<?php echo $od['od_email']; ?>" id="od_email" required class="form-input required" size="30"></td>
                </tr>
                <tr>
                    <th scope="row"><span class="sr-only">주문하신 분 </span>IP Address</th>
                    <td><?php echo $od['od_ip']; ?></td>
                </tr>
                </tbody>
                </table>
            </div>
        </section>

        <section id="anc_sodr_taker" class="compare_right">
            <h3>받으시는 분</h3>

            <div class="form-card">
                <table>
                <caption>받으시는 분 정보</caption>
                <colgroup>
                    <col class="col-4">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label for="od_b_name"><span class="sr-only">받으시는 분 </span>이름</label></th>
                    <td><input type="text" name="od_b_name" value="<?php echo get_text($od['od_b_name']); ?>" id="od_b_name" required class="form-input required"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_b_tel"><span class="sr-only">받으시는 분 </span>전화번호</label></th>
                    <td><input type="text" name="od_b_tel" value="<?php echo get_text($od['od_b_tel']); ?>" id="od_b_tel" required class="form-input required"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_b_hp"><span class="sr-only">받으시는 분 </span>핸드폰</label></th>
                    <td><input type="text" name="od_b_hp" value="<?php echo get_text($od['od_b_hp']); ?>" id="od_b_hp" class="form-input required"></td>
                </tr>
                <tr>
                    <th scope="row"><span class="sr-only">받으시는 분 </span>주소</th>
                    <td>
                        <label for="od_b_zip" class="sr-only">우편번호</label>
                        <input type="text" name="od_b_zip" value="<?php echo get_text($od['od_b_zip1']).get_text($od['od_b_zip2']); ?>" id="od_b_zip" required class="form-input required" size="5">
                        <button type="button" class="btn-inline" onclick="win_zip('frmorderform3', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소 검색</button><br>
                        <input type="text" name="od_b_addr1" value="<?php echo get_text($od['od_b_addr1']); ?>" id="od_b_addr1" required class="form-input required" size="35">
                        <label for="od_b_addr1">기본주소</label>
                        <input type="text" name="od_b_addr2" value="<?php echo get_text($od['od_b_addr2']); ?>" id="od_b_addr2" class="form-input" size="35">
                        <label for="od_b_addr2">상세주소</label>
                        <input type="text" name="od_b_addr3" value="<?php echo get_text($od['od_b_addr3']); ?>" id="od_b_addr3" class="form-input" size="35">
                        <label for="od_b_addr3">참고항목</label>
                        <input type="hidden" name="od_b_addr_jibeon" value="<?php echo get_text($od['od_b_addr_jibeon']); ?>"><br>
                    </td>
                </tr>

                <?php if ($default['de_hope_date_use']) { ?>
                <tr>
                    <th scope="row"><label for="od_hope_date">희망배송일</label></th>
                    <td>
                        <input type="text" name="od_hope_date" value="<?php echo $od['od_hope_date']; ?>" id="od_hopedate" required class="form-input required" maxlength="10" minlength="10"> (<?php echo get_yoil($od['od_hope_date']); ?>)
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <th scope="row">전달 메세지</th>
                    <td><?php if ($od['od_memo']) echo get_text($od['od_memo'], 1);else echo "없음";?></td>
                </tr>
                </tbody>
                </table>
            </div>
        </section>

    </div>

    <div class="action-bar">
        <input type="submit" value="주문자/배송지 정보 수정" class="btn-primary btn">
        <a href="./orderlist.php?<?php echo $qstr; ?>" class="btn">목록</a>
    </div>

    </form>
</section>
