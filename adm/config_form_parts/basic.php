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
                    <?php echo get_member_id_select('cf_admin', 10, $config['cf_admin'], 'required') ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_admin_email" class="form-label">관리자 메일 주소<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo help('관리자가 보내고 받는 용도로 사용하는 메일 주소를 입력합니다. (회원가입, 인증메일, 테스트, 회원메일발송 등에서 사용)') ?>
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
                    <?php echo help('관리자가 보내고 받는 용도로 사용하는 메일의 발송이름을 입력합니다. (회원가입, 인증메일, 테스트, 회원메일발송 등에서 사용)') ?>
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

            <?php if (!defined('G5_MEMBER_ONLY') || !G5_MEMBER_ONLY) { ?>
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_new_del" class="form-label">최근게시물 삭제</label>
                </div>
                <div class="af-field">
                    <?php echo help('설정일이 지난 최근게시물 자동 삭제') ?>
                    <div class="af-inline">
                        <input type="text" name="cf_new_del" value="<?php echo (int) $config['cf_new_del'] ?>" id="cf_new_del" size="5" class="form-input">
                        <span>일</span>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (!defined('G5_MEMBER_ONLY') || !G5_MEMBER_ONLY) { ?>
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_visit_del" class="form-label">접속자로그 삭제</label>
                </div>
                <div class="af-field">
                    <?php echo help('설정일이 지난 접속자 로그 자동 삭제') ?>
                    <div class="af-inline">
                        <input type="text" name="cf_visit_del" value="<?php echo (int) $config['cf_visit_del'] ?>" id="cf_visit_del" size="5" class="form-input">
                        <span>일</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_popular_del" class="form-label">인기검색어 삭제</label>
                </div>
                <div class="af-field">
                    <?php echo help('설정일이 지난 인기검색어 자동 삭제') ?>
                    <div class="af-inline">
                        <input type="text" name="cf_popular_del" value="<?php echo (int) $config['cf_popular_del'] ?>" id="cf_popular_del" size="5" class="form-input">
                        <span>일</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_login_minutes" class="form-label">현재 접속자</label>
                </div>
                <div class="af-field">
                    <?php echo help('설정값 이내의 접속자를 현재 접속자로 인정') ?>
                    <div class="af-inline">
                        <input type="text" name="cf_login_minutes" value="<?php echo (int) $config['cf_login_minutes'] ?>" id="cf_login_minutes" size="3" class="form-input">
                        <span>분</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_new_rows" class="form-label">최근게시물 라인수</label>
                </div>
                <div class="af-field">
                    <?php echo help('목록 한페이지당 라인수') ?>
                    <div class="af-inline">
                        <input type="text" name="cf_new_rows" value="<?php echo (int) $config['cf_new_rows'] ?>" id="cf_new_rows" size="3" class="form-input">
                        <span>라인</span>
                    </div>
                </div>
            </div>
            <?php } ?>

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
                    <label for="cf_write_pages" class="form-label">페이지 표시 수<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_write_pages" value="<?php echo (int) $config['cf_write_pages'] ?>" id="cf_write_pages" required size="3" class="form-input">
                        <span>페이지씩 표시</span>
                    </div>
                </div>
            </div>

            <?php if (!defined('G5_MEMBER_ONLY') || !G5_MEMBER_ONLY) { ?>
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_new_skin" class="form-label">최근게시물 스킨<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo get_skin_select('new', 'cf_new_skin', 'cf_new_skin', $config['cf_new_skin'], 'required'); ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_search_skin" class="form-label">검색 스킨<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo get_skin_select('search', 'cf_search_skin', 'cf_search_skin', $config['cf_search_skin'], 'required'); ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_connect_skin" class="form-label">접속자 스킨<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo get_skin_select('connect', 'cf_connect_skin', 'cf_connect_skin', $config['cf_connect_skin'], 'required'); ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_faq_skin" class="form-label">FAQ 스킨<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <?php echo get_skin_select('faq', 'cf_faq_skin', 'cf_faq_skin', $config['cf_faq_skin'], 'required'); ?>
                </div>
            </div>
            <?php } ?>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_editor" class="form-label">에디터 선택</label>
                </div>
                <div class="af-field">
                    <?php echo help(G5_EDITOR_URL . ' 밑의 DHTML 에디터 폴더를 선택합니다.') ?>
                    <select name="cf_editor" id="cf_editor" class="form-select">
                    <?php
                    $arr = get_skin_dir('', G5_EDITOR_PATH);
                    for ($i = 0; $i < count($arr); $i++) {
                        if ($i == 0) {
                            echo "<option value=\"\">사용안함</option>";
                        }
                        echo "<option value=\"" . $arr[$i] . "\"" . get_selected($config['cf_editor'], $arr[$i]) . ">" . $arr[$i] . "</option>\n";
                    }
                    ?>
                    </select>
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

            <?php if (!defined('G5_MEMBER_ONLY') || !G5_MEMBER_ONLY) { ?>
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_use_copy_log" class="form-label">복사, 이동시 로그</label>
                </div>
                <div class="af-field">
                    <?php echo help('게시물 아래에 누구로 부터 복사, 이동됨 표시') ?>
                    <label for="cf_use_copy_log" class="af-check form-label">
                        <input type="checkbox" name="cf_use_copy_log" value="1" id="cf_use_copy_log" <?php echo $config['cf_use_copy_log'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">남김</span>
                    </label>
                </div>
            </div>
            <?php } ?>

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

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_analytics" class="form-label">방문자분석 스크립트</label>
                </div>
                <div class="af-field">
                    <?php echo help('방문자분석 스크립트 코드를 입력합니다. 예) 구글 애널리틱스<br>관리자 페이지에서는 이 코드를 사용하지 않습니다.'); ?>
                    <textarea name="cf_analytics" id="cf_analytics" class="form-textarea"><?php echo get_text($config['cf_analytics']); ?></textarea>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_add_meta" class="form-label">추가 메타태그</label>
                </div>
                <div class="af-field">
                    <?php echo help('추가로 사용하실 meta 태그를 입력합니다.<br>관리자 페이지에서는 이 코드를 사용하지 않습니다.'); ?>
                    <textarea name="cf_add_meta" id="cf_add_meta" class="form-textarea"><?php echo get_text($config['cf_add_meta']); ?></textarea>
                </div>
            </div>

            <?php if (!defined('G5_MEMBER_ONLY') || !G5_MEMBER_ONLY) { ?>
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_syndi_token" class="form-label">네이버 신디케이션 연동키</label>
                </div>
                <div class="af-field">
                    <?php if (!function_exists('curl_init')) { ?>
                    <?php echo help('<b>경고) curl이 지원되지 않아 네이버 신디케이션을 사용할수 없습니다.</b>'); ?>
                    <?php } ?>
                    <?php echo help('네이버 신디케이션 연동키(token)을 입력하면 네이버 신디케이션을 사용할 수 있습니다.<br>연동키는 <a href="http://webmastertool.naver.com/" target="_blank" rel="noopener noreferrer"><u>네이버 웹마스터도구</u></a> -> 네이버 신디케이션에서 발급할 수 있습니다.') ?>
                    <input type="text" name="cf_syndi_token" value="<?php echo isset($config['cf_syndi_token']) ? get_sanitize_input($config['cf_syndi_token']) : ''; ?>" id="cf_syndi_token" size="70" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_syndi_except" class="form-label">네이버 신디케이션 제외게시판</label>
                </div>
                <div class="af-field">
                    <?php echo help('네이버 신디케이션 수집에서 제외할 게시판 아이디를 | 로 구분하여 입력하십시오. 예) notice|adult<br>참고로 그룹접근사용 게시판, 글읽기 권한 2 이상 게시판, 비밀글은 신디케이션 수집에서 제외됩니다.') ?>
                    <input type="text" name="cf_syndi_except" value="<?php echo isset($config['cf_syndi_except']) ? get_sanitize_input($config['cf_syndi_except']) : ''; ?>" id="cf_syndi_except" size="70" class="form-input">
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php if (!defined('G5_MEMBER_ONLY') || !G5_MEMBER_ONLY) { ?>
<button type="button" data-type="conf_skin" class="btn btn-soft-secondary btn-sm">테마 스킨설정 가져오기</button>
<?php } ?>
