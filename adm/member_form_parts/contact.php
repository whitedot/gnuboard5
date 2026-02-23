<section id="anc_mb_contact">
    <h2>연락처 및 주소</h2>
    
        
            
            
            
                        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_hp">휴대폰번호</label></div>
            <div class="ui-form-field"><input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" size="15" maxlength="20"></div>
        </div>
        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_tel">전화번호</label></div>
            <div class="ui-form-field"><input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" size="15" maxlength="20"></div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label">본인확인방법</div>
            <div class="ui-form-field"><input type="radio" name="mb_certify_case" value="simple" id="mb_certify_sa" <?php if ($mb['mb_certify'] == 'simple') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_sa">간편인증</label>
                        <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" <?php if ($mb['mb_certify'] == 'hp') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_hp">휴대폰</label>
                        <input type="radio" name="mb_certify_case" value="ipin" id="mb_certify_ipin" <?php if ($mb['mb_certify'] == 'ipin') { echo 'checked="checked"'; } ?>>
                        <label for="mb_certify_ipin">아이핀</label></div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label">본인확인</div>
            <div class="ui-form-field"><input type="radio" name="mb_certify" value="1" id="mb_certify_yes" <?php echo $mb_certify_yes; ?>>
                        <label for="mb_certify_yes">예</label>
                        <input type="radio" name="mb_certify" value="0" id="mb_certify_no" <?php echo $mb_certify_no; ?>>
                        <label for="mb_certify_no">아니오</label></div>
        </div>
        <div class="ui-form-row">
            <div class="ui-form-label">성인인증</div>
            <div class="ui-form-field"><input type="radio" name="mb_adult" value="1" id="mb_adult_yes" <?php echo $mb_adult_yes; ?>>
                        <label for="mb_adult_yes">예</label>
                        <input type="radio" name="mb_adult" value="0" id="mb_adult_no" <?php echo $mb_adult_no; ?>>
                        <label for="mb_adult_no">아니오</label></div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label">주소</div>
            <div class="ui-form-field"><label for="mb_zip">우편번호</label>
                        <input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'] . $mb['mb_zip2']; ?>" id="mb_zip" size="5" maxlength="6">
                        <button type="button" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                        <input type="text" name="mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" id="mb_addr1" size="60">
                        <label for="mb_addr1">기본주소</label><br>
                        <input type="text" name="mb_addr2" value="<?php echo $mb['mb_addr2'] ?>" id="mb_addr2" size="60">
                        <label for="mb_addr2">상세주소</label>
                        <br>
                        <input type="text" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>" id="mb_addr3" size="60">
                        <label for="mb_addr3">참고항목</label>
                        <input type="hidden" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon']; ?>"><br></div>
        </div>
            
        
    
</section>
