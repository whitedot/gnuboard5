    <section id="anc_cf_sms">
        <h2 class="h2_frm">SMS</h2>
        <?php echo $pg_anchor ?>

        <div class="card">
            <div class="grid grid-cols-1 gap-4">
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_sms_use" class="form-label py-2 mb-0!">SMS 사용</label>                    </div>
                                            <div class="lg:col-span-1">
                            <select id="cf_sms_use" name="cf_sms_use" class="form-select">
                                <option value="" <?php echo get_selected($config['cf_sms_use'], ''); ?>>사용안함</option>
                                <option value="icode" <?php echo get_selected($config['cf_sms_use'], 'icode'); ?>>아이코드</option>
                            </select>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_sms_type" class="form-label py-2 mb-0!">SMS 전송유형</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help("전송유형을 SMS로 선택하시면 최대 80바이트까지 전송하실 수 있으며<br>LMS로 선택하시면 90바이트 이하는 SMS로, 그 이상은 " . G5_ICODE_LMS_MAX_LENGTH . "바이트까지 LMS로 전송됩니다.<br>요금은 건당 SMS는 16원, LMS는 48원입니다."); ?>
                            <select id="cf_sms_type" name="cf_sms_type" class="form-select">
                                <option value="" <?php echo get_selected($config['cf_sms_type'], ''); ?>>SMS</option>
                                <option value="LMS" <?php echo get_selected($config['cf_sms_type'], 'LMS'); ?>>LMS</option>
                            </select>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_icode_id" class="form-label py-2 mb-0!">아이코드 회원아이디<br>(구버전)</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help("아이코드에서 사용하시는 회원아이디를 입력합니다."); ?>
                            <input type="text" name="cf_icode_id" value="<?php echo get_sanitize_input($config['cf_icode_id']); ?>" id="cf_icode_id" class="frm_input form-input" size="20">
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_icode_pw" class="form-label py-2 mb-0!">아이코드 비밀번호<br>(구버전)</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help("아이코드에서 사용하시는 비밀번호를 입력합니다."); ?>
                            <input type="password" name="cf_icode_pw" value="<?php echo get_sanitize_input($config['cf_icode_pw']); ?>" id="cf_icode_pw" class="frm_input form-input">
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">">
                                            <div class="lg:col-span-1">요금제<br>(구버전)                    </div>
                                            <div class="lg:col-span-1">
                            <input type="hidden" name="cf_icode_server_ip" value="<?php echo get_sanitize_input($config['cf_icode_server_ip']); ?>">
                            <?php
                            if ($userinfo['payment'] == 'A') {
                                echo '충전제';
                                echo '<input type="hidden" name="cf_icode_server_port" value="7295">';
                            } elseif ($userinfo['payment'] == 'C') {
                                echo '정액제';
                                echo '<input type="hidden" name="cf_icode_server_port" value="7296">';
                            } else {
                                echo '가입해주세요.';
                                echo '<input type="hidden" name="cf_icode_server_port" value="7295">';
                            }
                            ?>
                                            </div>
                                    </div>
                    <?php if ($userinfo['payment'] == 'A') { ?>
                                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                                <div class="lg:col-span-1">충전 잔액<br>(구버전)                    </div>
                                                <div class="lg:col-span-1">
                                <?php echo number_format($userinfo['coin']); ?> 원.
                                <a href="http://www.icodekorea.com/smsbiz/credit_card_amt.php?icode_id=<?php echo get_text($config['cf_icode_id']); ?>&amp;icode_passwd=<?php echo get_text($config['cf_icode_pw']); ?>" target="_blank" class="btn_frmline">충전하기</a>
                                                </div>
                                        </div>
                    <?php } ?>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1"><label for="cf_icode_token_key" class="form-label py-2 mb-0!">아이코드 토큰키<br>(JSON버전)</label>                    </div>
                                            <div class="lg:col-span-1">
                            <?php echo help("아이코드 JSON 버전의 경우 아이코드 토큰키를 입력시 실행됩니다.<br>SMS 전송유형을 LMS로 설정시 90바이트 이내는 SMS, 90 ~ 2000 바이트는 LMS 그 이상은 절삭 되어 LMS로 발송됩니다."); ?>
                            <input type="text" name="cf_icode_token_key" value="<?php echo isset($config['cf_icode_token_key']) ? get_sanitize_input($config['cf_icode_token_key']) : ''; ?>" id="cf_icode_token_key" class="frm_input form-input" size="40">
                            <?php echo help("아이코드 사이트 -> 토큰키관리 메뉴에서 생성한 토큰키를 입력합니다."); ?>
                            <br>
                            서버아이피 : <?php echo $_SERVER['SERVER_ADDR']; ?>
                                            </div>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-6 border-b border-dashed border-default-300 pb-4">
                                            <div class="lg:col-span-1">아이코드 SMS 신청<br>회원가입                    </div>
                                            <div class="lg:col-span-1">
                            <a href="http://icodekorea.com/res/join_company_fix_a.php?sellid=sir2" target="_blank" class="btn_frmline">아이코드 회원가입</a>
                                            </div>
                                    </div>
            </div>
        </div>
    </section>
