<section id="anc_mb_contact" class="card">
    <div class="card-header">
        <h2 class="card-title">인증 연락처</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="mb_hp" class="form-label">휴대폰번호</label>
                </div>
                <div class="af-field">
                    <input type="text" name="mb_hp" value="<?php echo $member_form_view['mb']['mb_hp'] ?>" id="mb_hp" size="15" maxlength="20" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_certify_sa" class="form-label">본인확인 방법</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="mb_certify_sa" class="af-check form-label">
                            <input type="radio" name="mb_certify_case" value="simple" id="mb_certify_sa" <?php if ($member_form_view['mb']['mb_certify'] == 'simple') { echo 'checked="checked"'; } ?> class="form-radio">
                            <span class="form-label">간편인증</span>
                        </label>
                        <label for="mb_certify_hp" class="af-check form-label">
                            <input type="radio" name="mb_certify_case" value="hp" id="mb_certify_hp" <?php if ($member_form_view['mb']['mb_certify'] == 'hp') { echo 'checked="checked"'; } ?> class="form-radio">
                            <span class="form-label">휴대폰</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_certify_yes" class="form-label">본인확인</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="mb_certify_yes" class="af-check form-label">
                            <input type="radio" name="mb_certify" value="1" id="mb_certify_yes" <?php echo $member_form_view['mb_certify_yes']; ?> class="form-radio">
                            <span class="form-label">예</span>
                        </label>
                        <label for="mb_certify_no" class="af-check form-label">
                            <input type="radio" name="mb_certify" value="0" id="mb_certify_no" <?php echo $member_form_view['mb_certify_no']; ?> class="form-radio">
                            <span class="form-label">아니오</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_adult_yes" class="form-label">성인인증</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="mb_adult_yes" class="af-check form-label">
                            <input type="radio" name="mb_adult" value="1" id="mb_adult_yes" <?php echo $member_form_view['mb_adult_yes']; ?> class="form-radio">
                            <span class="form-label">예</span>
                        </label>
                        <label for="mb_adult_no" class="af-check form-label">
                            <input type="radio" name="mb_adult" value="0" id="mb_adult_no" <?php echo $member_form_view['mb_adult_no']; ?> class="form-radio">
                            <span class="form-label">아니오</span>
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
