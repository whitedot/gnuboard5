    <section id="anc_cf_join">
        <h2 class="section-title">회원가입 설정</h2>
        <?php echo $pg_anchor ?>
        <div class="hint-text">
            <p>회원가입 시 사용할 스킨과 입력 받을 정보 등을 설정할 수 있습니다.</p>
        </div>

        <div class="card">
            <div class="grid grid-cols-1 gap-4">
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_member_skin" class="form-label py-2 mb-0!">회원 스킨<strong class="sr-only">필수</strong></label>                    </div>
                                            <div class="lg:col-span-3">
                            <?php echo get_skin_select('member', 'cf_member_skin', 'cf_member_skin', $config['cf_member_skin'], 'required'); ?>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1">홈페이지 입력                    </div>
                                            <div class="lg:col-span-1">
                            <input type="checkbox" name="cf_use_homepage" value="1" id="cf_use_homepage" <?php echo $config['cf_use_homepage'] ? 'checked' : ''; ?>> <label for="cf_use_homepage" class="form-label py-2 mb-0!">보이기</label>
                            <input type="checkbox" name="cf_req_homepage" value="1" id="cf_req_homepage" <?php echo $config['cf_req_homepage'] ? 'checked' : ''; ?>> <label for="cf_req_homepage" class="form-label py-2 mb-0!">필수입력</label>
                                            </div>
                                            <div class="lg:col-span-1">주소 입력                    </div>
                                            <div class="lg:col-span-1">
                            <input type="checkbox" name="cf_use_addr" value="1" id="cf_use_addr" <?php echo $config['cf_use_addr'] ? 'checked' : ''; ?>> <label for="cf_use_addr" class="form-label py-2 mb-0!">보이기</label>
                            <input type="checkbox" name="cf_req_addr" value="1" id="cf_req_addr" <?php echo $config['cf_req_addr'] ? 'checked' : ''; ?>> <label for="cf_req_addr" class="form-label py-2 mb-0!">필수입력</label>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1">전화번호 입력                    </div>
                                            <div class="lg:col-span-1">
                            <input type="checkbox" name="cf_use_tel" value="1" id="cf_use_tel" <?php echo $config['cf_use_tel'] ? 'checked' : ''; ?>> <label for="cf_use_tel" class="form-label py-2 mb-0!">보이기</label>
                            <input type="checkbox" name="cf_req_tel" value="1" id="cf_req_tel" <?php echo $config['cf_req_tel'] ? 'checked' : ''; ?>> <label for="cf_req_tel" class="form-label py-2 mb-0!">필수입력</label>
                                            </div>
                                            <div class="lg:col-span-1">휴대폰번호 입력                    </div>
                                            <div class="lg:col-span-1">
                            <input type="checkbox" name="cf_use_hp" value="1" id="cf_use_hp" <?php echo $config['cf_use_hp'] ? 'checked' : ''; ?>> <label for="cf_use_hp" class="form-label py-2 mb-0!">보이기</label>
                            <input type="checkbox" name="cf_req_hp" value="1" id="cf_req_hp" <?php echo $config['cf_req_hp'] ? 'checked' : ''; ?>> <label for="cf_req_hp" class="form-label py-2 mb-0!">필수입력</label>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1">서명 입력                    </div>
                                            <div class="lg:col-span-1">
                            <input type="checkbox" name="cf_use_signature" value="1" id="cf_use_signature" <?php echo $config['cf_use_signature'] ? 'checked' : ''; ?>> <label for="cf_use_signature" class="form-label py-2 mb-0!">보이기</label>
                            <input type="checkbox" name="cf_req_signature" value="1" id="cf_req_signature" <?php echo $config['cf_req_signature'] ? 'checked' : ''; ?>> <label for="cf_req_signature" class="form-label py-2 mb-0!">필수입력</label>
                                            </div>
                                            <div class="lg:col-span-1">자기소개 입력                    </div>
                                            <div class="lg:col-span-1">
                            <input type="checkbox" name="cf_use_profile" value="1" id="cf_use_profile" <?php echo $config['cf_use_profile'] ? 'checked' : ''; ?>> <label for="cf_use_profile" class="form-label py-2 mb-0!">보이기</label>
                            <input type="checkbox" name="cf_req_profile" value="1" id="cf_req_profile" <?php echo $config['cf_req_profile'] ? 'checked' : ''; ?>> <label for="cf_req_profile" class="form-label py-2 mb-0!">필수입력</label>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_register_level" class="form-label py-2 mb-0!">회원가입시 권한</label>                    </div>
                                            <div class="lg:col-span-1"><?php echo get_member_level_select('cf_register_level', 1, 9, $config['cf_register_level']) ?>                    </div>
                                            <div class="lg:col-span-1"><label for="cf_register_point" class="form-label py-2 mb-0!">회원가입시 포인트</label>                    </div>
                                            <div class="lg:col-span-1"><input type="text" name="cf_register_point" value="<?php echo (int) $config['cf_register_point'] ?>" id="cf_register_point" class="form-input" size="5"> 점                    </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_leave_day" class="form-label py-2 mb-0!">회원탈퇴후 삭제일</label>                    </div>
                                            <div class="lg:col-span-3"><input type="text" name="cf_leave_day" value="<?php echo (int) $config['cf_leave_day'] ?>" id="cf_leave_day" class="form-input" size="2"> 일 후 자동 삭제                    </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_use_member_icon" class="form-label py-2 mb-0!">회원아이콘 사용</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('게시물에 게시자 닉네임 대신 아이콘 사용') ?>
                            <select name="cf_use_member_icon" id="cf_use_member_icon" class="form-select">
                                <option value="0" <?php echo get_selected($config['cf_use_member_icon'], '0') ?>>미사용
                                <option value="1" <?php echo get_selected($config['cf_use_member_icon'], '1') ?>>아이콘만 표시
                                <option value="2" <?php echo get_selected($config['cf_use_member_icon'], '2') ?>>아이콘+이름 표시
                            </select>
                                            </div>
                                            <div class="lg:col-span-1"><label for="cf_icon_level" class="form-label py-2 mb-0!">회원 아이콘, 이미지 업로드 권한</label>                    </div>
                                            <div class="lg:col-span-1"><?php echo get_member_level_select('cf_icon_level', 1, 9, $config['cf_icon_level']) ?> 이상                    </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_member_icon_size" class="form-label py-2 mb-0!">회원아이콘 용량</label>                    </div>
                                            <div class="lg:col-span-1"><input type="text" name="cf_member_icon_size" value="<?php echo (int) $config['cf_member_icon_size'] ?>" id="cf_member_icon_size" class="form-input" size="10"> 바이트 이하                    </div>
                                            <div class="lg:col-span-1">회원아이콘 사이즈                    </div>
                                            <div class="lg:col-span-1">
                            <label for="cf_member_icon_width" class="form-label py-2 mb-0!">가로</label>
                            <input type="text" name="cf_member_icon_width" value="<?php echo (int) $config['cf_member_icon_width'] ?>" id="cf_member_icon_width" class="form-input" size="2">
                            <label for="cf_member_icon_height" class="form-label py-2 mb-0!">세로</label>
                            <input type="text" name="cf_member_icon_height" value="<?php echo (int) $config['cf_member_icon_height'] ?>" id="cf_member_icon_height" class="form-input" size="2">
                            픽셀 이하
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_member_img_size" class="form-label py-2 mb-0!">회원이미지 용량</label>                    </div>
                                            <div class="lg:col-span-1"><input type="text" name="cf_member_img_size" value="<?php echo (int) $config['cf_member_img_size'] ?>" id="cf_member_img_size" class="form-input" size="10"> 바이트 이하                    </div>
                                            <div class="lg:col-span-1">회원이미지 사이즈                    </div>
                                            <div class="lg:col-span-1">
                            <label for="cf_member_img_width" class="form-label py-2 mb-0!">가로</label>
                            <input type="text" name="cf_member_img_width" value="<?php echo (int) $config['cf_member_img_width'] ?>" id="cf_member_img_width" class="form-input" size="2">
                            <label for="cf_member_img_height" class="form-label py-2 mb-0!">세로</label>
                            <input type="text" name="cf_member_img_height" value="<?php echo (int) $config['cf_member_img_height'] ?>" id="cf_member_img_height" class="form-input" size="2">
                            픽셀 이하
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_use_recommend" class="form-label py-2 mb-0!">추천인제도 사용</label>                    </div>
                                            <div class="lg:col-span-1"><input type="checkbox" name="cf_use_recommend" value="1" id="cf_use_recommend" <?php echo $config['cf_use_recommend'] ? 'checked' : ''; ?>> 사용                    </div>
                                            <div class="lg:col-span-1"><label for="cf_recommend_point" class="form-label py-2 mb-0!">추천인 포인트</label>                    </div>
                                            <div class="lg:col-span-1"><input type="text" name="cf_recommend_point" value="<?php echo (int) $config['cf_recommend_point'] ?>" id="cf_recommend_point" class="form-input"> 점                    </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_prohibit_id" class="form-label py-2 mb-0!">아이디,닉네임 금지단어</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('회원아이디, 닉네임으로 사용할 수 없는 단어를 정합니다. 쉼표 (,) 로 구분') ?>
                            <textarea name="cf_prohibit_id" id="cf_prohibit_id" rows="5" class="form-textarea min-h-24 w-full"><?php echo get_sanitize_input($config['cf_prohibit_id']); ?></textarea>
                                            </div>
                                            <div class="lg:col-span-1"><label for="cf_prohibit_email" class="form-label py-2 mb-0!">입력 금지 메일</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('입력 받지 않을 도메인을 지정합니다. 엔터로 구분 ex) hotmail.com') ?>
                            <textarea name="cf_prohibit_email" id="cf_prohibit_email" rows="5" class="form-textarea min-h-24 w-full"><?php echo get_sanitize_input($config['cf_prohibit_email']); ?></textarea>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_stipulation" class="form-label py-2 mb-0!">회원가입약관</label>                    </div>
                                            <div class="lg:col-span-3"><textarea name="cf_stipulation" id="cf_stipulation" rows="10" class="form-textarea min-h-24 w-full"><?php echo html_purifier($config['cf_stipulation']); ?></textarea>                    </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_privacy" class="form-label py-2 mb-0!">개인정보처리방침</label>                    </div>
                                            <div class="lg:col-span-3"><textarea id="cf_privacy" name="cf_privacy" rows="10" class="form-textarea min-h-24 w-full"><?php echo html_purifier($config['cf_privacy']); ?></textarea>                    </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_use_promotion" class="form-label py-2 mb-0!">회원가입 약관 동의에<br>광고성 정보 수신 동의 표시 여부</label>                    </div>
                                            <div class="lg:col-span-3">
                            <?php echo help('<b>광고성 정보 수신 · 마케팅 목적의 개인정보 수집 및 이용 · 개인정보 제 3자 제공</b> 여부를 설정합니다. <b>SMS 또는 카카오톡</b> 사용 시 <b>개인정보 제3자 제공</b>이 활성화됩니다.'); ?>
                            <?php echo help('동의한 회원에게 <b>카카오톡(친구톡)·문자</b>로 광고성 메시지를 발송할 수 있습니다.'); ?>
                            <?php echo help('<b>휴대전화번호</b> 사용을 위해서는 <b>기본환경설정 > 회원가입 > 휴대전화번호 입력</b>을 <b>[보이기]</b> 또는 <b>[필수입력]</b>으로 설정해야 하며, 미설정 시 수집이 불가합니다.'); ?>
                            <?php echo help('* 「정보통신망이용촉진및정보보호등에관한법률」에 따라 <b>광고성 정보 수신 동의</b>를 매 2년마다 반드시 확인해야 합니다.'); ?>
                            <input type="checkbox" name="cf_use_promotion" value="1" id="cf_use_promotion" <?php echo $config['cf_use_promotion'] ? 'checked' : ''; ?>> 
                            <label for="cf_use_promotion" class="form-label py-2 mb-0!">사용</label>
                                            </div>
                                    </div>
                            </div>
        </div>
        <button type="button" class="get_theme_confc btn btn-secondary" data-type="conf_member">테마 회원스킨설정 가져오기</button>
    </section>
