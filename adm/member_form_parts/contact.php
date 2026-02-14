<section id="anc_mb_contact">
    <h2 class="h2_frm">연락처 및 주소</h2>
    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption>연락처 및 주소</caption>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="mb_hp">휴대폰번호</label></th>
                    <td><input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" class="frm_input" size="15" maxlength="20"></td>
                    <th scope="row"><label for="mb_tel">전화번호</label></th>
                    <td><input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" class="frm_input" size="15" maxlength="20"></td>
                </tr>
                <tr>
                    <th scope="row">본인확인방법</th>
                    <td colspan="3">
                        <input type="radio" name="mb_certify_case" value="simple" id="mb_certify_sa" <?php if ($mb['mb_certify'] == 'simple') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_sa">간편인증</label>
                        <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" <?php if ($mb['mb_certify'] == 'hp') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_hp">휴대폰</label>
                        <input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin" <?php if ($mb['mb_certify'] == 'ipin') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_ipin">아이핀</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">본인확인</th>
                    <td>
                        <input type="radio" name="mb_certify" value="1" id="mb_certify_yes" <?php echo $mb_certify_yes; ?>>
                        <label for="mb_certify_yes">예</label>
                        <input type="radio" name="mb_certify" value="0" id="mb_certify_no" <?php echo $mb_certify_no; ?>>
                        <label for="mb_certify_no">아니오</label>
                    </td>
                    <th scope="row">성인인증</th>
                    <td>
                        <input type="radio" name="mb_adult" value="1" id="mb_adult_yes" <?php echo $mb_adult_yes; ?>>
                        <label for="mb_adult_yes">예</label>
                        <input type="radio" name="mb_adult" value="0" id="mb_adult_no" <?php echo $mb_adult_no; ?>>
                        <label for="mb_adult_no">아니오</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">주소</th>
                    <td colspan="3" class="td_addr_line">
                        <label for="mb_zip" class="sr-only">우편번호</label>
                        <input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'] . $mb['mb_zip2']; ?>" id="mb_zip" class="frm_input readonly" size="5" maxlength="6">
                        <button type="button" class="btn_frmline" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                        <input type="text" name="mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" id="mb_addr1" class="frm_input readonly" size="60">
                        <label for="mb_addr1">기본주소</label><br>
                        <input type="text" name="mb_addr2" value="<?php echo $mb['mb_addr2'] ?>" id="mb_addr2" class="frm_input" size="60">
                        <label for="mb_addr2">상세주소</label>
                        <br>
                        <input type="text" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>" id="mb_addr3" class="frm_input" size="60">
                        <label for="mb_addr3">참고항목</label>
                        <input type="hidden" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon']; ?>"><br>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
