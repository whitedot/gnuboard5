<section id="anc_cf_cert" class="card">
    <div class="card-header">
        <h2 class="card-title">본인확인 설정</h2>
    </div>
    <div class="card-body">
        <p>
            회원가입 시 본인확인 수단을 설정합니다.<br>
            실명과 휴대폰 번호 그리고 본인확인 당시에 성인인지의 여부를 저장합니다.<br>
            저장된 본인확인 정보는 회원 인증과 권한 정책 판단에 사용할 수 있습니다.
        </p>

        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_cert_use" class="form-label">본인확인</label>
                </div>
                <div class="af-field">
                    <select name="cf_cert_use" id="cf_cert_use" class="form-select">
                        <?php echo option_selected("0", $config['cf_cert_use'], "사용안함"); ?>
                        <?php echo option_selected("1", $config['cf_cert_use'], "테스트"); ?>
                        <?php echo option_selected("2", $config['cf_cert_use'], "실서비스"); ?>
                    </select>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_find" class="form-label">회원정보찾기</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">휴대폰 본인확인을 이용하시다가 간편인증을 이용하시는 경우, 기존 회원은 아이디/비밀번호 찾기에 사용할 수 없을 수 있습니다.</p>
                    <label for="cf_cert_find" class="af-check form-label">
                        <input type="checkbox" name="cf_cert_find" id="cf_cert_find" value="1" <?php if (isset($config['cf_cert_find']) && $config['cf_cert_find'] == 1) { echo "checked"; } ?> class="form-checkbox">
                        <span class="form-label">아이디/비밀번호 찾기에 사용하기</span>
                    </label>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_simple" class="form-label">통합인증(간편인증)</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">KG이니시스의 통합인증(간편인증+전자서명) 서비스에서 전자서명을 제외한 간편인증 서비스 입니다. <a href="https://www.inicis.com/all-auth-service" target="_blank" rel="noopener noreferrer"><u>KG이니시스 통합인증 안내</u></a></p>
                    <select name="cf_cert_simple" id="cf_cert_simple" class="form-select">
                        <?php echo option_selected("", $config['cf_cert_simple'], "사용안함"); ?>
                        <?php echo option_selected("inicis", $config['cf_cert_simple'], "KG이니시스 통합인증(간편인증)"); ?>
                    </select>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_use_seed" class="form-label">통합인증 암호화 적용</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">KG이니시스 통합인증서비스에 암호화를 적용합니다. 만일 글자가 깨지는 문제가 발생하면 사용안함으로 적용해 주세요.</p>
                    <select name="cf_cert_use_seed" id="cf_cert_use_seed" class="form-select">
                        <?php echo option_selected("0", $config['cf_cert_use_seed'], "사용안함"); ?>
                        <?php echo option_selected("1", $config['cf_cert_use_seed'], "사용함"); ?>
                    </select>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_hp" class="form-label">휴대폰 본인확인</label>
                </div>
                <div class="af-field">
                    <select name="cf_cert_hp" id="cf_cert_hp" class="form-select">
                        <?php echo option_selected("", $config['cf_cert_hp'], "사용안함"); ?>
                        <?php echo option_selected("kcp", $config['cf_cert_hp'], "NHN KCP 휴대폰 본인확인"); ?>
                    </select>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_kg_mid" class="form-label">KG이니시스 간편인증 MID</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <span>SRA</span>
                        <input type="text" name="cf_cert_kg_mid" value="<?php echo get_sanitize_input($config['cf_cert_kg_mid']); ?>" id="cf_cert_kg_mid" size="10" minlength="7" maxlength="7" class="form-input">
                    </div>
                    <a href="http://sir.kr/main/service/inicis_cert_form.php" target="_blank" rel="noopener noreferrer" class="btn btn-soft-primary btn-sm">KG이니시스 통합인증(간편인증) 신청페이지</a>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_kg_cd" class="form-label">KG이니시스 간편인증 API KEY</label>
                </div>
                <div class="af-field">
                    <input type="text" name="cf_cert_kg_cd" value="<?php echo get_sanitize_input($config['cf_cert_kg_cd']); ?>" id="cf_cert_kg_cd" size="40" minlength="32" maxlength="32" class="form-input">
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_kcp_cd" class="form-label">NHN KCP 사이트코드</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">SM으로 시작하는 5자리 사이트 코드중 뒤의 3자리만 입력해 주십시오.<br>서비스에 가입되어 있지 않다면, 본인확인 서비스 신청페이지에서 서비스 신청 후 사이트코드를 발급 받으실 수 있습니다.</p>
                    <div class="af-inline">
                        <span>SM</span>
                        <input type="text" name="cf_cert_kcp_cd" value="<?php echo get_sanitize_input($config['cf_cert_kcp_cd']); ?>" id="cf_cert_kcp_cd" size="3" class="form-input">
                    </div>
                    <a href="http://sir.kr/main/service/p_cert.php" target="_blank" rel="noopener noreferrer" class="btn btn-soft-primary btn-sm">NHN KCP 휴대폰 본인확인 서비스 신청페이지</a>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_kcp_enckey" class="form-label">NHN KCP 가맹점 인증키</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">(선택사항, 추후 NHN_KCP 상점관리자에서 인증키 발급 메뉴 오픈일정 이후부터 적용되는 내용입니다.)<br>NHN_KCP 상점관리자 &gt; 기술관리센터 &gt; 인증센터 &gt; 가맹점 인증키관리 에서 인증키 발급 후에 인증키 정보를 입력</p>
                    <div class="af-inline">
                        <input type="text" name="cf_cert_kcp_enckey" value="<?php echo get_sanitize_input($config['cf_cert_kcp_enckey']); ?>" id="cf_cert_kcp_enckey" maxlength="100" size="40" class="form-input">
                        <a href="https://partner.kcp.co.kr" target="_blank" rel="noopener noreferrer" class="btn btn-soft-primary btn-sm">NHN KCP 상점관리자</a>
                    </div>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_limit" class="form-label">본인확인 이용제한</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">1일 단위 본인인증을 시도할 수 있는 최대횟수를 지정합니다. (0으로 설정 시 무한으로 인증시도 가능)<br>휴대폰/간편인증에서 개별 적용됩니다.)</p>
                    <div class="af-inline">
                        <input type="text" name="cf_cert_limit" value="<?php echo (int) $config['cf_cert_limit']; ?>" id="cf_cert_limit" size="3" class="form-input">
                        <span>회</span>
                    </div>
                </div>
            </div>

            <div class="af-row cf_cert_service">
                <div class="af-label">
                    <label for="cf_cert_req" class="form-label">본인확인 필수</label>
                </div>
                <div class="af-field">
                    <p class="hint-text">회원가입 때 본인확인을 필수로 할지 설정합니다. 필수로 설정하시면 본인확인을 하지 않은 경우 회원가입이 안됩니다.</p>
                    <label for="cf_cert_req" class="af-check form-label">
                        <input type="checkbox" name="cf_cert_req" value="1" id="cf_cert_req" <?php echo get_checked($config['cf_cert_req'], 1); ?> class="form-checkbox">
                        <span class="form-label">예</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>
