<section id="sod_frm_orderer">
    <h2>주문하시는 분</h2>

    <div>
        <table>
        <tbody>
        <tr>
            <th scope="row"><label for="od_name">이름<strong> 필수</strong></label></th>
            <td><input type="text" name="od_name" value="<?php echo isset($member['mb_name']) ? get_text($member['mb_name']) : ''; ?>" id="od_name" required maxlength="20"></td>
        </tr>

        <?php if (!$is_member) { // 비회원이면 ?>
        <tr>
            <th scope="row"><label for="od_pwd">비밀번호</label></th>
            <td>
                <span>영,숫자 3~20자 (주문서 조회시 필요)</span>
                <input type="password" name="od_pwd" id="od_pwd" required maxlength="20">
            </td>
        </tr>
        <?php } ?>

        <tr>
            <th scope="row"><label for="od_tel">전화번호<strong> 필수</strong></label></th>
            <td><input type="text" name="od_tel" value="<?php echo get_text($member['mb_tel']); ?>" id="od_tel" required maxlength="20"></td>
        </tr>
        <tr>
            <th scope="row"><label for="od_hp">핸드폰</label></th>
            <td><input type="text" name="od_hp" value="<?php echo get_text($member['mb_hp']); ?>" id="od_hp" maxlength="20"></td>
        </tr>
        <tr>
            <th scope="row">주소</th>
            <td>
                <label for="od_zip">우편번호<strong> 필수</strong></label>
                <input type="text" name="od_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" required size="8" maxlength="6" placeholder="우편번호">
                <button type="button" onclick="win_zip('forderform', 'od_zip', 'od_addr1', 'od_addr2', 'od_addr3', 'od_addr_jibeon');">주소 검색</button><br>
                <input type="text" name="od_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="od_addr1" required size="60" placeholder="기본주소">
                <label for="od_addr1">기본주소<strong> 필수</strong></label><br>
                <input type="text" name="od_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="od_addr2" size="60" placeholder="상세주소">
                <label for="od_addr2">상세주소</label>
                <br>
                <input type="text" name="od_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="od_addr3" size="60" readonly="readonly" placeholder="참고항목">
                <label for="od_addr3">참고항목</label><br>
                <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="od_email">E-mail<strong> 필수</strong></label></th>
            <td><input type="text" name="od_email" value="<?php echo $member['mb_email']; ?>" id="od_email" required size="35" maxlength="100"></td>
        </tr>

        <?php if ($default['de_hope_date_use']) { // 배송희망일 사용 ?>
        <tr>
            <th scope="row"><label for="od_hope_date">희망배송일</label></th>
            <td>
                <input type="text" name="od_hope_date" value="" id="od_hope_date" required size="11" maxlength="10" readonly="readonly"> 이후로 배송 바랍니다.
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>
