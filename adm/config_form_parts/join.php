<section id="anc_cf_join" class="card">
    <div class="card-header">
        <h2 class="card-title">회원가입 설정</h2>
    </div>
    <div class="card-body">
        <p>회원가입과 회원정보 수정 시 입력 받을 정보 등을 설정할 수 있습니다.</p>

        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_use_hp" class="form-label">휴대폰번호 입력</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="cf_use_hp" class="af-check form-label">
                            <input type="checkbox" name="cf_use_hp" value="1" id="cf_use_hp" <?php echo $config['cf_use_hp'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">보이기</span>
                        </label>
                        <label for="cf_req_hp" class="af-check form-label">
                            <input type="checkbox" name="cf_req_hp" value="1" id="cf_req_hp" <?php echo $config['cf_req_hp'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">필수입력</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_register_level" class="form-label">회원가입시 권한</label>
                </div>
                <div class="af-field">
                    <select id="cf_register_level" name="cf_register_level" class="form-select">
                        <?php foreach ($config_form_view['register_level_options'] as $option) { ?>
                            <option value="<?php echo $option['value']; ?>"<?php echo $option['selected'] ? ' selected' : ''; ?>><?php echo $option['label']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_leave_day" class="form-label">회원탈퇴후 삭제일</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_leave_day" value="<?php echo (int) $config['cf_leave_day'] ?>" id="cf_leave_day" size="2" class="form-input">
                        <span>일 후 자동 삭제</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_prohibit_id" class="form-label">아이디,닉네임 금지단어</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">회원아이디, 닉네임으로 사용할 수 없는 단어를 정합니다. 쉼표 (,) 로 구분</p>
                    <textarea name="cf_prohibit_id" id="cf_prohibit_id" rows="5" class="form-textarea"><?php echo get_sanitize_input($config['cf_prohibit_id']); ?></textarea>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_prohibit_email" class="form-label">입력 금지 메일</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">입력 받지 않을 도메인을 지정합니다. 엔터로 구분 ex) hotmail.com</p>
                    <textarea name="cf_prohibit_email" id="cf_prohibit_email" rows="5" class="form-textarea"><?php echo get_sanitize_input($config['cf_prohibit_email']); ?></textarea>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_stipulation" class="form-label">회원가입약관</label>
                </div>
                <div class="af-field">
                    <textarea name="cf_stipulation" id="cf_stipulation" rows="10" class="form-textarea"><?php echo html_purifier($config['cf_stipulation']); ?></textarea>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_privacy" class="form-label">개인정보처리방침</label>
                </div>
                <div class="af-field">
                    <textarea id="cf_privacy" name="cf_privacy" rows="10" class="form-textarea"><?php echo html_purifier($config['cf_privacy']); ?></textarea>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_use_promotion" class="form-label">회원가입 약관 동의에<br>광고성 정보 수신 동의 표시 여부</label>
                </div>
                <div class="af-field">
                    <p class="hint-text"><b>광고성 정보 수신 · 마케팅 목적의 개인정보 수집 및 이용</b> 여부를 설정합니다.</p>
                    <p class="hint-text">동의한 회원에게 <b>이메일</b>로 광고성 메시지를 발송할 수 있습니다.</p>
                    <p class="hint-text">* 「정보통신망이용촉진및정보보호등에관한법률」에 따라 <b>광고성 정보 수신 동의</b>를 매 2년마다 반드시 확인해야 합니다.</p>
                    <label for="cf_use_promotion" class="af-check form-label">
                        <input type="checkbox" name="cf_use_promotion" value="1" id="cf_use_promotion" <?php echo $config['cf_use_promotion'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</section>
