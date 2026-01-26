<section id="sod_frm_orderer" >
    <h2>주문하시는 분</h2>

    <div class="odf_list">
        <ul>
            <li>
                <label for="od_name">이름<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="od_name" value="<?php echo isset($member['mb_name']) ? get_text($member['mb_name']) : ''; ?>" id="od_name" required class="frm_input required" maxlength="20">
            </li>

            <?php if (!$is_member) { // 비회원이면 ?>
            <li>
                <label for="od_pwd">비밀번호<strong class="sound_only"> 필수</strong></label>
                
                    <input type="password" name="od_pwd" id="od_pwd" required class="frm_input required" maxlength="20">
                    영,숫자 3~20자 (주문서 조회시 필요)
                
            </li>
            <?php } ?>

            <li>
                <label for="od_tel">전화번호<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="od_tel" value="<?php echo get_text($member['mb_tel']); ?>" id="od_tel" required class="frm_input required" maxlength="20">
            </li>
            <li>
                <label for="od_hp">핸드폰</label>
                <input type="text" name="od_hp" value="<?php echo get_text($member['mb_hp']); ?>" id="od_hp" class="frm_input" maxlength="20">
            </li>
            <li>
                <strong>주소</strong>
                
                <span class="add_num"><label for="od_zip" class="sound_only">우편번호<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="od_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" required class="frm_input required" size="5" maxlength="6">
                <button type="button" class="btn_frmline btn_addsch" onclick="win_zip('forderform', 'od_zip', 'od_addr1', 'od_addr2', 'od_addr3', 'od_addr_jibeon');">주소검색</button></span>
                <label for="od_addr1" class="sound_only">기본주소<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="od_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="od_addr1" required class="frm_input frm_address required">
                <label for="od_addr2" class="sound_only">상세주소</label>
                <input type="text" name="od_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="od_addr2" class="frm_input frm_address">
                <label for="od_addr3" class="sound_only">참고항목</label>
                <input type="text" name="od_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="od_addr3" class="frm_input frm_address" readonly="readonly">
                <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>"><br>
                
            </li>
            <li>
                <label for="od_email">E-mail<strong class="sound_only"> 필수</strong></label>
                <input type="email" name="od_email" value="<?php echo $member['mb_email']; ?>" id="od_email" required class="frm_input required" maxlength="100">
            </li>

            <?php if ($default['de_hope_date_use']) { // 배송희망일 사용 ?>
            <li>
                <label for="od_hope_date">희망배송일</label>
                    <input type="text" name="od_hope_date" value="" id="od_hope_date" required class="frm_input required" size="11" maxlength="10" readonly> 이후로 배송 바랍니다.
                
            </li>
            <?php } ?>
        </ul>
    </div>
</section>
