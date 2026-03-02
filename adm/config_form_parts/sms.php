<section id="anc_cf_sms" class="card">
    <div class="card-header">
        <h2 class="card-title">SMS</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_sms_use" class="form-label">SMS 사용</label>
                </div>
                <div class="af-field">
                    <select id="cf_sms_use" name="cf_sms_use" class="form-select">
                        <option value="" <?php echo get_selected($config['cf_sms_use'], ''); ?>>사용안함</option>
                        <option value="icode" <?php echo get_selected($config['cf_sms_use'], 'icode'); ?>>아이코드</option>
                    </select>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_sms_type" class="form-label">SMS 전송유형</label>
                </div>
                <div class="af-field">
                    <?php echo help("전송유형을 SMS로 선택하시면 최대 80바이트까지 전송하실 수 있으며<br>LMS로 선택하시면 90바이트 이하는 SMS로, 그 이상은 " . G5_ICODE_LMS_MAX_LENGTH . "바이트까지 LMS로 전송됩니다.<br>요금은 건당 SMS는 16원, LMS는 48원입니다."); ?>
                    <select id="cf_sms_type" name="cf_sms_type" class="form-select">
                        <option value="" <?php echo get_selected($config['cf_sms_type'], ''); ?>>SMS</option>
                        <option value="LMS" <?php echo get_selected($config['cf_sms_type'], 'LMS'); ?>>LMS</option>
                    </select>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_icode_id" class="form-label">아이코드 회원아이디<br>(구버전)</label>
                </div>
                <div class="af-field">
                    <?php echo help("아이코드에서 사용하시는 회원아이디를 입력합니다."); ?>
                    <input type="text" name="cf_icode_id" value="<?php echo get_sanitize_input($config['cf_icode_id']); ?>" id="cf_icode_id" size="20" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_icode_pw" class="form-label">아이코드 비밀번호<br>(구버전)</label>
                </div>
                <div class="af-field">
                    <?php echo help("아이코드에서 사용하시는 비밀번호를 입력합니다."); ?>
                    <input type="password" name="cf_icode_pw" value="<?php echo get_sanitize_input($config['cf_icode_pw']); ?>" id="cf_icode_pw" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label class="form-label">요금제<br>(구버전)</label>
                </div>
                <div class="af-field">
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
            <div class="af-row">
                <div class="af-label">
                    <label class="form-label">충전 잔액<br>(구버전)</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <span><?php echo number_format($userinfo['coin']); ?> 원.</span>
                        <a href="http://www.icodekorea.com/smsbiz/credit_card_amt.php?icode_id=<?php echo get_text($config['cf_icode_id']); ?>&amp;icode_passwd=<?php echo get_text($config['cf_icode_pw']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-soft-primary btn-sm">충전하기</a>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_icode_token_key" class="form-label">아이코드 토큰키<br>(JSON버전)</label>
                </div>
                <div class="af-field">
                    <?php echo help("아이코드 JSON 버전의 경우 아이코드 토큰키를 입력시 실행됩니다.<br>SMS 전송유형을 LMS로 설정시 90바이트 이내는 SMS, 90 ~ 2000 바이트는 LMS 그 이상은 절삭 되어 LMS로 발송됩니다."); ?>
                    <input type="text" name="cf_icode_token_key" value="<?php echo isset($config['cf_icode_token_key']) ? get_sanitize_input($config['cf_icode_token_key']) : ''; ?>" id="cf_icode_token_key" size="40" class="form-input">
                    <?php echo help("아이코드 사이트 -> 토큰키관리 메뉴에서 생성한 토큰키를 입력합니다."); ?>
                    <span>서버아이피 : <?php echo $_SERVER['SERVER_ADDR']; ?></span>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label class="form-label">아이코드 SMS 신청<br>회원가입</label>
                </div>
                <div class="af-field">
                    <a href="http://icodekorea.com/res/join_company_fix_a.php?sellid=sir2" target="_blank" rel="noopener noreferrer" class="btn btn-soft-primary btn-sm">아이코드 회원가입</a>
                </div>
            </div>
        </div>
    </div>
</section>
