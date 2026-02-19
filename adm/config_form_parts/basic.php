    <section id="anc_cf_basic">
        <h2>홈페이지 기본환경 설정</h2>
        <?php echo $pg_anchor ?>

        <div>
            <div>
                                    <div>
                                            <div><label for="cf_title">홈페이지 제목<strong>필수</strong></label>                    </div>
                                            <div><input type="text" name="cf_title" value="<?php echo get_sanitize_input($config['cf_title']); ?>" id="cf_title" required class="required" size="40">                    </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_admin">최고관리자<strong>필수</strong></label>                    </div>
                                            <div><?php echo get_member_id_select('cf_admin', 10, $config['cf_admin'], 'required') ?>                    </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_admin_email">관리자 메일 주소<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo help('관리자가 보내고 받는 용도로 사용하는 메일 주소를 입력합니다. (회원가입, 인증메일, 테스트, 회원메일발송 등에서 사용)') ?>
                            <input type="text" name="cf_admin_email" value="<?php echo get_sanitize_input($config['cf_admin_email']); ?>" id="cf_admin_email" required class="required" size="40">
                            <?php if (function_exists('domain_mail_host') && $config['cf_admin_email'] && stripos($config['cf_admin_email'], domain_mail_host()) === false) { ?>
                            <?php echo help('외부메일설정이나 기타 설정을 하지 않았다면, 도메인과 다른 헤더로 여겨 스팸이나 차단될 가능성이 있습니다.<br>name'.domain_mail_host().' 과 같은 도메인 형식으로 설정할것을 권장합니다.') ?>
                            <?php } ?>
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_admin_email_name">관리자 메일 발송이름<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo help('관리자가 보내고 받는 용도로 사용하는 메일의 발송이름을 입력합니다. (회원가입, 인증메일, 테스트, 회원메일발송 등에서 사용)') ?>
                            <input type="text" name="cf_admin_email_name" value="<?php echo get_sanitize_input($config['cf_admin_email_name']); ?>" id="cf_admin_email_name" required class="required" size="40">
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_use_point">포인트 사용</label>                    </div>
                                            <div><input type="checkbox" name="cf_use_point" value="1" id="cf_use_point" <?php echo $config['cf_use_point'] ? 'checked' : ''; ?>> 사용                    </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_login_point">로그인시 포인트<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo help('회원이 로그인시 하루에 한번만 적립') ?>
                            <input type="text" name="cf_login_point" value="<?php echo (int) $config['cf_login_point'] ?>" id="cf_login_point" required class="required" size="5"> 점
                                            </div>
                                            <div><label for="cf_memo_send_point">쪽지보낼시 차감 포인트<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo help('양수로 입력하십시오. 0점은 쪽지 보낼시 포인트를 차감하지 않습니다.') ?>
                            <input type="text" name="cf_memo_send_point" value="<?php echo (int) $config['cf_memo_send_point']; ?>" id="cf_memo_send_point" required class="required" size="5"> 점
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_cut_name">이름(닉네임) 표시</label>                    </div>
                                            <div>
                            <input type="text" name="cf_cut_name" value="<?php echo (int) $config['cf_cut_name'] ?>" id="cf_cut_name" size="5"> 자리만 표시
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_nick_modify">닉네임 수정</label>                    </div>
                                            <div>수정하면 <input type="text" name="cf_nick_modify" value="<?php echo (int) $config['cf_nick_modify'] ?>" id="cf_nick_modify" size="3"> 일 동안 바꿀 수 없음                    </div>
                                            <div><label for="cf_open_modify">정보공개 수정</label>                    </div>
                                            <div>수정하면 <input type="text" name="cf_open_modify" value="<?php echo (int) $config['cf_open_modify'] ?>" id="cf_open_modify" size="3"> 일 동안 바꿀 수 없음                    </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_new_del">최근게시물 삭제</label>                    </div>
                                            <div>
                            <?php echo help('설정일이 지난 최근게시물 자동 삭제') ?>
                            <input type="text" name="cf_new_del" value="<?php echo (int) $config['cf_new_del'] ?>" id="cf_new_del" size="5"> 일
                                            </div>
                                            <div><label for="cf_memo_del">쪽지 삭제</label>                    </div>
                                            <div>
                            <?php echo help('설정일이 지난 쪽지 자동 삭제') ?>
                            <input type="text" name="cf_memo_del" value="<?php echo (int) $config['cf_memo_del'] ?>" id="cf_memo_del" size="5"> 일
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_visit_del">접속자로그 삭제</label>                    </div>
                                            <div>
                            <?php echo help('설정일이 지난 접속자 로그 자동 삭제') ?>
                            <input type="text" name="cf_visit_del" value="<?php echo (int) $config['cf_visit_del'] ?>" id="cf_visit_del" size="5"> 일
                                            </div>
                                            <div><label for="cf_popular_del">인기검색어 삭제</label>                    </div>
                                            <div>
                            <?php echo help('설정일이 지난 인기검색어 자동 삭제') ?>
                            <input type="text" name="cf_popular_del" value="<?php echo (int) $config['cf_popular_del'] ?>" id="cf_popular_del" size="5"> 일
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_login_minutes">현재 접속자</label>                    </div>
                                            <div>
                            <?php echo help('설정값 이내의 접속자를 현재 접속자로 인정') ?>
                            <input type="text" name="cf_login_minutes" value="<?php echo (int) $config['cf_login_minutes'] ?>" id="cf_login_minutes" size="3"> 분
                                            </div>
                                            <div><label for="cf_new_rows">최근게시물 라인수</label>                    </div>
                                            <div>
                            <?php echo help('목록 한페이지당 라인수') ?>
                            <input type="text" name="cf_new_rows" value="<?php echo (int) $config['cf_new_rows'] ?>" id="cf_new_rows" size="3"> 라인
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_page_rows">한페이지당 라인수</label>                    </div>
                                            <div>
                            <?php echo help('목록(리스트) 한페이지당 라인수') ?>
                            <input type="text" name="cf_page_rows" value="<?php echo (int) $config['cf_page_rows'] ?>" id="cf_page_rows" size="3"> 라인
                                            </div>
                                            <div><label for="cf_mobile_page_rows">모바일 한페이지당 라인수</label>                    </div>
                                            <div>
                            <?php echo help('모바일 목록 한페이지당 라인수') ?>
                            <input type="text" name="cf_mobile_page_rows" value="<?php echo (int) $config['cf_mobile_page_rows'] ?>" id="cf_mobile_page_rows" size="3"> 라인
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_write_pages">페이지 표시 수<strong>필수</strong></label>                    </div>
                                            <div><input type="text" name="cf_write_pages" value="<?php echo (int) $config['cf_write_pages'] ?>" id="cf_write_pages" required class="required" size="3"> 페이지씩 표시                    </div>
                                            <div><label for="cf_mobile_pages">모바일 페이지 표시 수<strong>필수</strong></label>                    </div>
                                            <div><input type="text" name="cf_mobile_pages" value="<?php echo (int) $config['cf_mobile_pages'] ?>" id="cf_mobile_pages" required class="required" size="3"> 페이지씩 표시                    </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_new_skin">최근게시물 스킨<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo get_skin_select('new', 'cf_new_skin', 'cf_new_skin', $config['cf_new_skin'], 'required'); ?>
                                            </div>
                                            <div><label for="cf_search_skin">검색 스킨<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo get_skin_select('search', 'cf_search_skin', 'cf_search_skin', $config['cf_search_skin'], 'required'); ?>
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_connect_skin">접속자 스킨<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo get_skin_select('connect', 'cf_connect_skin', 'cf_connect_skin', $config['cf_connect_skin'], 'required'); ?>
                                            </div>
                                            <div><label for="cf_faq_skin">FAQ 스킨<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo get_skin_select('faq', 'cf_faq_skin', 'cf_faq_skin', $config['cf_faq_skin'], 'required'); ?>
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_editor">에디터 선택</label>                    </div>
                                            <div>
                            <?php echo help(G5_EDITOR_URL . ' 밑의 DHTML 에디터 폴더를 선택합니다.') ?>
                            <select name="cf_editor" id="cf_editor">
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
                                    <div>
                                            <div><label for="cf_captcha">캡챠 선택<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo help('사용할 캡챠를 선택합니다.<br>1) Kcaptcha 는 그누보드5의 기본캡챠입니다. ( 문자입력 )<br>2) reCAPTCHA V2 는 구글에서 서비스하는 원클릭 형식의 간편한 캡챠입니다. ( 모바일 친화적 UI )<br>3) Invisible reCAPTCHA 는 구글에서 서비스하는 안보이는 형식의 캡챠입니다. ( 간혹 퀴즈를 풀어야 합니다. )<br>') ?>
                            <select name="cf_captcha" id="cf_captcha" required class="required">
                                <option value="kcaptcha" <?php echo get_selected($config['cf_captcha'], 'kcaptcha'); ?>>Kcaptcha</option>
                                <option value="recaptcha" <?php echo get_selected($config['cf_captcha'], 'recaptcha'); ?>>reCAPTCHA V2</option>
                                <option value="recaptcha_inv" <?php echo get_selected($config['cf_captcha'], 'recaptcha_inv'); ?>>Invisible reCAPTCHA</option>
                            </select>
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_captcha_mp3">음성캡챠 선택<strong>필수</strong></label>                    </div>
                                            <div>
                            <?php echo help('kcaptcha 사용시 ' . str_replace(array('recaptcha_inv', 'recaptcha'), 'kcaptcha', G5_CAPTCHA_URL) . '/mp3 밑의 음성 폴더를 선택합니다.') ?>
                            <select name="cf_captcha_mp3" id="cf_captcha_mp3" required class="required">
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
                                    <div>
                                            <div><label for="cf_recaptcha_site_key">구글 reCAPTCHA Site key</label>                    </div>
                                            <div>
                            <?php echo help('reCAPTCHA V2와 Invisible reCAPTCHA 캡챠의 sitekey 와 secret 키는 동일하지 않고, 서로 발급받는 키가 다릅니다.') ?>
                            <input type="text" name="cf_recaptcha_site_key" value="<?php echo get_sanitize_input($config['cf_recaptcha_site_key']); ?>" id="cf_recaptcha_site_key" size="52"> <a href="https://www.google.com/recaptcha/admin" target="_blank">reCAPTCHA 등록하기</a>
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_recaptcha_secret_key">구글 reCAPTCHA Secret key</label>                    </div>
                                            <div>
                            <input type="text" name="cf_recaptcha_secret_key" value="<?php echo get_sanitize_input($config['cf_recaptcha_secret_key']); ?>" id="cf_recaptcha_secret_key" size="52">
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_use_copy_log">복사, 이동시 로그</label>                    </div>
                                            <div>
                            <?php echo help('게시물 아래에 누구로 부터 복사, 이동됨 표시') ?>
                            <input type="checkbox" name="cf_use_copy_log" value="1" id="cf_use_copy_log" <?php echo $config['cf_use_copy_log'] ? 'checked' : ''; ?>> 남김
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_point_term">포인트 유효기간</label>                    </div>
                                            <div>
                            <?php echo help('기간을 0으로 설정시 포인트 유효기간이 적용되지 않습니다.') ?>
                            <input type="text" name="cf_point_term" value="<?php echo (int) $config['cf_point_term']; ?>" id="cf_point_term" required class="required" size="5"> 일
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_possible_ip">접근가능 IP</label>                    </div>
                                            <div>
                            <?php echo help('입력된 IP의 컴퓨터만 접근할 수 있습니다.<br>123.123.+ 도 입력 가능. (엔터로 구분)') ?>
                            <textarea name="cf_possible_ip" id="cf_possible_ip"><?php echo get_sanitize_input($config['cf_possible_ip']); ?></textarea>
                                            </div>
                                            <div><label for="cf_intercept_ip">접근차단 IP</label>                    </div>
                                            <div>
                            <?php echo help('입력된 IP의 컴퓨터는 접근할 수 없음.<br>123.123.+ 도 입력 가능. (엔터로 구분)') ?>
                            <textarea name="cf_intercept_ip" id="cf_intercept_ip"><?php echo get_sanitize_input($config['cf_intercept_ip']); ?></textarea>
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_analytics">방문자분석 스크립트</label>                    </div>
                                            <div>
                            <?php echo help('방문자분석 스크립트 코드를 입력합니다. 예) 구글 애널리틱스<br>관리자 페이지에서는 이 코드를 사용하지 않습니다.'); ?>
                            <textarea name="cf_analytics" id="cf_analytics"><?php echo get_text($config['cf_analytics']); ?></textarea>
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_add_meta">추가 메타태그</label>                    </div>
                                            <div>
                            <?php echo help('추가로 사용하실 meta 태그를 입력합니다.<br>관리자 페이지에서는 이 코드를 사용하지 않습니다.'); ?>
                            <textarea name="cf_add_meta" id="cf_add_meta"><?php echo get_text($config['cf_add_meta']); ?></textarea>
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_syndi_token">네이버 신디케이션 연동키</label>                    </div>
                                            <div>
                            <?php if (!function_exists('curl_init')) {
                                echo help('<b>경고) curl이 지원되지 않아 네이버 신디케이션을 사용할수 없습니다.</b>');
                            } ?>
                            <?php echo help('네이버 신디케이션 연동키(token)을 입력하면 네이버 신디케이션을 사용할 수 있습니다.<br>연동키는 <a href="http://webmastertool.naver.com/" target="_blank"><u>네이버 웹마스터도구</u></a> -> 네이버 신디케이션에서 발급할 수 있습니다.') ?>
                            <input type="text" name="cf_syndi_token" value="<?php echo isset($config['cf_syndi_token']) ? get_sanitize_input($config['cf_syndi_token']) : ''; ?>" id="cf_syndi_token" size="70">
                                            </div>
                                    </div>
                                    <div>
                                            <div><label for="cf_syndi_except">네이버 신디케이션 제외게시판</label>                    </div>
                                            <div>
                            <?php echo help('네이버 신디케이션 수집에서 제외할 게시판 아이디를 | 로 구분하여 입력하십시오. 예) notice|adult<br>참고로 그룹접근사용 게시판, 글읽기 권한 2 이상 게시판, 비밀글은 신디케이션 수집에서 제외됩니다.') ?>
                            <input type="text" name="cf_syndi_except" value="<?php echo isset($config['cf_syndi_except']) ? get_sanitize_input($config['cf_syndi_except']) : ''; ?>" id="cf_syndi_except" size="70">
                                            </div>
                                    </div>
            </div>
        </div>
    </section>
    <button type="button" class="get_theme_confc" data-type="conf_skin">테마 스킨설정 가져오기</button>
