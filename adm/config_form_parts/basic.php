<section id="anc_cf_basic" class="card">
    <div class="card-header">
        <h2 class="card-title">홈페이지 기본환경 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_title" class="form-label">홈페이지 제목<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <input type="text" name="cf_title" value="<?php echo get_sanitize_input($config['cf_title']); ?>" id="cf_title" required size="40" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_admin" class="form-label">최고관리자<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo get_member_id_select('cf_admin', 10, $config['cf_admin'], 'required class="form-select"') ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_admin_email" class="form-label">관리자 메일 주소<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo help('관리자가 보내고 받는 용도로 사용하는 메일 주소를 입력합니다. (회원가입, 인증메일, 테스트 등에서 사용)') ?>
                    <input type="text" name="cf_admin_email" value="<?php echo get_sanitize_input($config['cf_admin_email']); ?>" id="cf_admin_email" required size="40" class="form-input">
                    <?php if (function_exists('domain_mail_host') && $config['cf_admin_email'] && stripos($config['cf_admin_email'], domain_mail_host()) === false) { ?>
                    <?php echo help('외부메일설정이나 기타 설정을 하지 않았다면, 도메인과 다른 헤더로 여겨 스팸이나 차단될 가능성이 있습니다.<br>name'.domain_mail_host().' 과 같은 도메인 형식으로 설정할것을 권장합니다.') ?>
                    <?php } ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_admin_email_name" class="form-label">관리자 메일 발송이름<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo help('관리자가 보내고 받는 용도로 사용하는 메일의 발송이름을 입력합니다. (회원가입, 인증메일, 테스트 등에서 사용)') ?>
                    <input type="text" name="cf_admin_email_name" value="<?php echo get_sanitize_input($config['cf_admin_email_name']); ?>" id="cf_admin_email_name" required size="40" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_cut_name" class="form-label">이름(닉네임) 표시</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_cut_name" value="<?php echo (int) $config['cf_cut_name'] ?>" id="cf_cut_name" size="5" class="form-input">
                        <span>자리만 표시</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_nick_modify" class="form-label">닉네임 수정</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <span>수정하면</span>
                        <input type="text" name="cf_nick_modify" value="<?php echo (int) $config['cf_nick_modify'] ?>" id="cf_nick_modify" size="3" class="form-input">
                        <span>일 동안 바꿀 수 없음</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_open_modify" class="form-label">정보공개 수정</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <span>수정하면</span>
                        <input type="text" name="cf_open_modify" value="<?php echo (int) $config['cf_open_modify'] ?>" id="cf_open_modify" size="3" class="form-input">
                        <span>일 동안 바꿀 수 없음</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_page_rows" class="form-label">한페이지당 라인수</label>
                </div>
                <div class="af-field">
                    <?php echo help('목록(리스트) 한페이지당 라인수') ?>
                    <div class="af-inline">
                        <input type="text" name="cf_page_rows" value="<?php echo (int) $config['cf_page_rows'] ?>" id="cf_page_rows" size="3" class="form-input">
                        <span>라인</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_captcha" class="form-label">캡챠 선택<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo help('사용할 캡챠를 선택합니다.<br>1) Kcaptcha 는 그누보드5의 기본캡챠입니다. ( 문자입력 )<br>2) reCAPTCHA V2 는 구글에서 서비스하는 원클릭 형식의 간편한 캡챠입니다. ( 모바일 친화적 UI )<br>3) Invisible reCAPTCHA 는 구글에서 서비스하는 안보이는 형식의 캡챠입니다. ( 간혹 퀴즈를 풀어야 합니다. )<br>') ?>
                    <select name="cf_captcha" id="cf_captcha" required class="form-select">
                        <option value="kcaptcha" <?php echo get_selected($config['cf_captcha'], 'kcaptcha'); ?>>Kcaptcha</option>
                        <option value="recaptcha" <?php echo get_selected($config['cf_captcha'], 'recaptcha'); ?>>reCAPTCHA V2</option>
                        <option value="recaptcha_inv" <?php echo get_selected($config['cf_captcha'], 'recaptcha_inv'); ?>>Invisible reCAPTCHA</option>
                    </select>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_captcha_mp3" class="form-label">음성캡챠 선택<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo help('kcaptcha 사용시 ' . str_replace(array('recaptcha_inv', 'recaptcha'), 'kcaptcha', G5_CAPTCHA_URL) . '/mp3 밑의 음성 폴더를 선택합니다.') ?>
                    <select name="cf_captcha_mp3" id="cf_captcha_mp3" required class="form-select">
                    <?php
                    $arr = get_skin_dir('mp3', str_replace(array('recaptcha_inv', 'recaptcha'), 'kcaptcha', G5_CAPTCHA_PATH));
                    for ($i = 0; $i < count($arr); $i++) {
                        if ($i == 0) {
                            echo "<option value=\"\">선택</option>";
                        }
                        echo "<option value=\"" . $arr[$i] . "\"" . get_selected($config['cf_captcha_mp3'], $arr[$i]) . ">" . $arr[$i] . "</option>\n";
                    }
                    ?>
                    </select>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_recaptcha_site_key" class="form-label">구글 reCAPTCHA Site key</label>
                </div>
                <div class="af-field">
                    <?php echo help('reCAPTCHA V2와 Invisible reCAPTCHA 캡챠의 sitekey 와 secret 키는 동일하지 않고, 서로 발급받는 키가 다릅니다.') ?>
                    <input type="text" name="cf_recaptcha_site_key" value="<?php echo get_sanitize_input($config['cf_recaptcha_site_key']); ?>" id="cf_recaptcha_site_key" size="52" class="form-input">
                    <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener noreferrer" class="btn btn-soft-primary btn-sm">reCAPTCHA 등록하기</a>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_recaptcha_secret_key" class="form-label">구글 reCAPTCHA Secret key</label>
                </div>
                <div class="af-field">
                    <input type="text" name="cf_recaptcha_secret_key" value="<?php echo get_sanitize_input($config['cf_recaptcha_secret_key']); ?>" id="cf_recaptcha_secret_key" size="52" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_possible_ip" class="form-label">접근가능 IP</label>
                </div>
                <div class="af-field">
                    <?php echo help('입력된 IP의 컴퓨터만 접근할 수 있습니다.<br>123.123.+ 도 입력 가능. (엔터로 구분)') ?>
                    <textarea name="cf_possible_ip" id="cf_possible_ip" class="form-textarea"><?php echo get_sanitize_input($config['cf_possible_ip']); ?></textarea>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_intercept_ip" class="form-label">접근차단 IP</label>
                </div>
                <div class="af-field">
                    <?php echo help('입력된 IP의 컴퓨터는 접근할 수 없음.<br>123.123.+ 도 입력 가능. (엔터로 구분)') ?>
                    <textarea name="cf_intercept_ip" id="cf_intercept_ip" class="form-textarea"><?php echo get_sanitize_input($config['cf_intercept_ip']); ?></textarea>
                </div>
            </div>
        </div>
    </div>
</section>
