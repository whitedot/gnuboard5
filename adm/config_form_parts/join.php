<section id="anc_cf_join" class="card">
    <div class="card-header">
        <h2 class="card-title">회원가입 설정</h2>
    </div>
    <div class="card-body">
        <p>회원가입 시 사용할 스킨과 입력 받을 정보 등을 설정할 수 있습니다.</p>

        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_member_skin" class="form-label">회원 스킨<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo get_skin_select('member', 'cf_member_skin', 'cf_member_skin', $config['cf_member_skin'], 'required'); ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_use_homepage" class="form-label">홈페이지 입력</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="cf_use_homepage" class="af-check form-label">
                            <input type="checkbox" name="cf_use_homepage" value="1" id="cf_use_homepage" <?php echo $config['cf_use_homepage'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">보이기</span>
                        </label>
                        <label for="cf_req_homepage" class="af-check form-label">
                            <input type="checkbox" name="cf_req_homepage" value="1" id="cf_req_homepage" <?php echo $config['cf_req_homepage'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">필수입력</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_use_addr" class="form-label">주소 입력</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="cf_use_addr" class="af-check form-label">
                            <input type="checkbox" name="cf_use_addr" value="1" id="cf_use_addr" <?php echo $config['cf_use_addr'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">보이기</span>
                        </label>
                        <label for="cf_req_addr" class="af-check form-label">
                            <input type="checkbox" name="cf_req_addr" value="1" id="cf_req_addr" <?php echo $config['cf_req_addr'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">필수입력</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_use_tel" class="form-label">전화번호 입력</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="cf_use_tel" class="af-check form-label">
                            <input type="checkbox" name="cf_use_tel" value="1" id="cf_use_tel" <?php echo $config['cf_use_tel'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">보이기</span>
                        </label>
                        <label for="cf_req_tel" class="af-check form-label">
                            <input type="checkbox" name="cf_req_tel" value="1" id="cf_req_tel" <?php echo $config['cf_req_tel'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">필수입력</span>
                        </label>
                    </div>
                </div>
            </div>

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
                    <?php echo get_member_level_select('cf_register_level', 1, 9, $config['cf_register_level']) ?>
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
                    <?php echo help('회원아이디, 닉네임으로 사용할 수 없는 단어를 정합니다. 쉼표 (,) 로 구분') ?>
                    <textarea name="cf_prohibit_id" id="cf_prohibit_id" rows="5" class="form-textarea"><?php echo get_sanitize_input($config['cf_prohibit_id']); ?></textarea>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_prohibit_email" class="form-label">입력 금지 메일</label>
                </div>
                <div class="af-field">
                    <?php echo help('입력 받지 않을 도메인을 지정합니다. 엔터로 구분 ex) hotmail.com') ?>
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
                    <?php echo help('<b>광고성 정보 수신 · 마케팅 목적의 개인정보 수집 및 이용</b> 여부를 설정합니다.'); ?>
                    <?php echo help('동의한 회원에게 <b>이메일</b>로 광고성 메시지를 발송할 수 있습니다.'); ?>
                    <?php echo help('* 「정보통신망이용촉진및정보보호등에관한법률」에 따라 <b>광고성 정보 수신 동의</b>를 매 2년마다 반드시 확인해야 합니다.'); ?>
                    <label for="cf_use_promotion" class="af-check form-label">
                        <input type="checkbox" name="cf_use_promotion" value="1" id="cf_use_promotion" <?php echo $config['cf_use_promotion'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>
        </div>

        <div>
            <button type="button" data-type="conf_member" class="btn btn-soft-secondary btn-sm">테마 회원스킨설정 가져오기</button>
        </div>
    </div>
</section>
