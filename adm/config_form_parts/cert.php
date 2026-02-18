    <section id="anc_cf_cert">
        <h2 class="h2_frm">본인확인 설정</h2>
        <?php echo $pg_anchor ?>
        <div class="hint-text">
            <p>
                회원가입 시 본인확인 수단을 설정합니다.<br>
                실명과 휴대폰 번호 그리고 본인확인 당시에 성인인지의 여부를 저장합니다.<br>
                게시판의 경우 본인확인 또는 성인여부를 따져 게시물 조회 및 쓰기 권한을 줄 수 있습니다.
            </p>
        </div>

        <div class="card">
            <div class="grid grid-cols-1 gap-4">
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_use" class="form-label py-2 mb-0!">본인확인</label>                    </div>
                                            <div class="lg:col-span-1">
                            <select name="cf_cert_use" id="cf_cert_use" class="form-select">
                                <?php echo option_selected("0", $config['cf_cert_use'], "사용안함"); ?>
                                <?php echo option_selected("1", $config['cf_cert_use'], "테스트"); ?>
                                <?php echo option_selected("2", $config['cf_cert_use'], "실서비스"); ?>
                            </select>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_find" class="form-label py-2 mb-0!">회원정보찾기</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('휴대폰/아이핀 본인확인을 이용하시다가 간편인증을 이용하시는 경우, 기존 회원은 아이디/비밀번호 찾기에 사용할 수 없을 수 있습니다.') ?>
                            <input type="checkbox" name="cf_cert_find" id="cf_cert_find" value="1" <?php if (isset($config['cf_cert_find']) && $config['cf_cert_find'] == 1) { echo "checked"; } ?>><label for="cf_cert_find" class="form-label py-2 mb-0!"> 아이디/비밀번호 찾기에 사용하기</label>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_simple" class="form-label py-2 mb-0!">통합인증(간편인증)</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('KG이니시스의 통합인증(간편인증+전자서명) 서비스에서 전자서명을 제외한 간편인증 서비스 입니다. <a href="https://www.inicis.com/all-auth-service" target="_blank"><u>KG이니시스 통합인증 안내</u></a>') ?>
                            <select name="cf_cert_simple" id="cf_cert_simple" class="form-select">
                                <?php echo option_selected("", $config['cf_cert_simple'], "사용안함"); ?>
                                <?php echo option_selected("inicis", $config['cf_cert_simple'], "KG이니시스 통합인증(간편인증)"); ?>
                            </select>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_use_seed" class="form-label py-2 mb-0!">통합인증 암호화 적용</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('KG이니시스 통합인증서비스에 암호화를 적용합니다. 만일 글자가 깨지는 문제가 발생하면 사용안함으로 적용해 주세요.') ?>
                            <select name="cf_cert_use_seed" id="cf_cert_use_seed" class="form-select">
                                <?php echo option_selected("0", $config['cf_cert_use_seed'], "사용안함"); ?>
                                <?php echo option_selected("1", $config['cf_cert_use_seed'], "사용함"); ?>
                            </select>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_hp" class="form-label py-2 mb-0!">휴대폰 본인확인</label>                    </div>
                                            <div class="lg:col-span-1">
                            <select name="cf_cert_hp" id="cf_cert_hp" class="form-select">
                                <?php echo option_selected("", $config['cf_cert_hp'], "사용안함"); ?>
                                <?php echo option_selected("kcb", $config['cf_cert_hp'], "코리아크레딧뷰로(KCB) 휴대폰 본인확인"); ?>
                                <?php echo option_selected("kcp", $config['cf_cert_hp'], "NHN KCP 휴대폰 본인확인"); ?>
                            </select>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_ipin" class="form-label py-2 mb-0!">아이핀 본인확인</label>                    </div>
                                            <div class="lg:col-span-1">
                            <select name="cf_cert_ipin" id="cf_cert_ipin" class="form-select">
                                <?php echo option_selected("", $config['cf_cert_ipin'], "사용안함"); ?>
                                <?php echo option_selected("kcb", $config['cf_cert_ipin'], "코리아크레딧뷰로(KCB) 아이핀"); ?>
                            </select>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_kg_cd" class="form-label py-2 mb-0!">KG이니시스 간편인증 MID</label>                    </div>
                                            <div class="lg:col-span-1">
                            <span class="sitecode">SRA</span>
                            <input type="text" name="cf_cert_kg_mid" value="<?php echo get_sanitize_input($config['cf_cert_kg_mid']); ?>" id="cf_cert_kg_mid" class="frm_input form-input" size="10" minlength="7" maxlength="7">
                            <a href="http://sir.kr/main/service/inicis_cert_form.php" target="_blank" class="btn_frmline">KG이니시스 통합인증(간편인증) 신청페이지</a>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_kg_cd" class="form-label py-2 mb-0!">KG이니시스 간편인증 API KEY</label>                    </div>
                                            <div class="lg:col-span-1">
                            <input type="text" name="cf_cert_kg_cd" value="<?php echo get_sanitize_input($config['cf_cert_kg_cd']); ?>" id="cf_cert_kg_cd" class="frm_input form-input" size="40" minlength="32" maxlength="32">
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_kcb_cd" class="form-label py-2 mb-0!">코리아크레딧뷰로<br>KCB 회원사ID</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('KCB 회원사ID를 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, KCB와 계약체결 후 회원사ID를 발급 받으실 수 있습니다.<br>이용하시려는 서비스에 대한 계약을 아이핀, 휴대폰 본인확인 각각 체결해주셔야 합니다.<br>아이핀 본인확인 테스트의 경우에는 KCB 회원사ID가 필요 없으나,<br>휴대폰 본인확인 테스트의 경우 KCB 에서 따로 발급 받으셔야 합니다.') ?>
                            <input type="text" name="cf_cert_kcb_cd" value="<?php echo get_sanitize_input($config['cf_cert_kcb_cd']); ?>" id="cf_cert_kcb_cd" class="frm_input form-input" size="20">
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_kcp_cd" class="form-label py-2 mb-0!">NHN KCP 사이트코드</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('SM으로 시작하는 5자리 사이트 코드중 뒤의 3자리만 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, 본인확인 서비스 신청페이지에서 서비스 신청 후 사이트코드를 발급 받으실 수 있습니다.') ?>
                            <span class="sitecode">SM</span>
                            <input type="text" name="cf_cert_kcp_cd" value="<?php echo get_sanitize_input($config['cf_cert_kcp_cd']); ?>" id="cf_cert_kcp_cd" class="frm_input form-input" size="3"> <a href="http://sir.kr/main/service/p_cert.php" target="_blank" class="btn_frmline">NHN KCP 휴대폰 본인확인 서비스 신청페이지</a>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_kcp_enckey" class="form-label py-2 mb-0!">NHN KCP 가맹점 인증키</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('(선택사항, 추후 NHN_KCP 상점관리자에서 인증키 발급 메뉴 오픈일정 이후부터 적용되는 내용입니다.)<br>NHN_KCP 상점관리자 > 기술관리센터 > 인증센터 > 가맹점 인증키관리 에서 인증키 발급 후에 인증키 정보를 입력') ?>
                            <input type="text" name="cf_cert_kcp_enckey" value="<?php echo get_sanitize_input($config['cf_cert_kcp_enckey']); ?>" id="cf_cert_kcp_enckey" class="frm_input form-input" maxlength="100" size="40"> <a href="https://partner.kcp.co.kr" target="_blank" class="btn_frmline">NHN KCP 상점관리자</a>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_limit" class="form-label py-2 mb-0!">본인확인 이용제한</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('1일 단위 본인인증을 시도할 수 있는 최대횟수를 지정합니다. (0으로 설정 시 무한으로 인증시도 가능)<br>아이핀/휴대폰/간편인증에서 개별 적용됩니다.)'); ?>
                            <input type="text" name="cf_cert_limit" value="<?php echo (int) $config['cf_cert_limit']; ?>" id="cf_cert_limit" class="frm_input form-input" size="3"> 회
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_cert_req" class="form-label py-2 mb-0!">본인확인 필수</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help('회원가입 때 본인확인을 필수로 할지 설정합니다. 필수로 설정하시면 본인확인을 하지 않은 경우 회원가입이 안됩니다.'); ?>
                            <input type="checkbox" name="cf_cert_req" value="1" id="cf_cert_req" <?php echo get_checked($config['cf_cert_req'], 1); ?>> 예
                                            </div>
                                    </div>
            </div>
        </div>
    </section>
