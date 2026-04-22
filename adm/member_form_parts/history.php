<section id="anc_mb_history" class="card">
    <div class="card-header">
        <h2 class="card-title">인증 및 활동 내역</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label class="form-label">본인인증 내역</label>
                </div>
                <div class="af-field">
                    <?php
                    $cnt = 0;
                    if ($mb_cert_history) {
                        sql_data_seek($mb_cert_history, 0);
                        while ($row = sql_fetch_array($mb_cert_history)) {
                            $cnt++;
                            $cert_type = '';
                            switch ($row['ch_type']) {
                                case 'simple':
                                    $cert_type = '간편인증';
                                    break;
                                case 'hp':
                                    $cert_type = '휴대폰';
                                    break;
                                default:
                                    $cert_type = '기타';
                                    break;
                            }
                    ?>
                    <div>
                        [<?php echo $row['ch_datetime']; ?>]
                        <?php echo $row['mb_id']; ?> /
                        <?php echo $row['ch_name']; ?> /
                        <?php echo $row['ch_hp']; ?> /
                        <?php echo $cert_type; ?>
                    </div>
                    <?php
                        }
                    }
                    ?>
                    <?php if ($cnt == 0) { ?>
                    <p class="hint-text">본인인증 내역이 없습니다.</p>
                    <?php } ?>
                </div>
            </div>

            <?php if ($w == 'u') { ?>
            <div class="af-row">
                <div class="af-label"><label class="form-label">회원가입일</label></div>
                <div class="af-field"><?php echo $mb['mb_datetime'] ?></div>
            </div>

            <div class="af-row">
                <div class="af-label"><label class="form-label">최근접속일</label></div>
                <div class="af-field"><?php echo $mb['mb_today_login'] ?></div>
            </div>

            <div class="af-row">
                <div class="af-label"><label class="form-label">IP</label></div>
                <div class="af-field"><?php echo $mb['mb_ip'] ?></div>
            </div>

            <?php if ($config['cf_use_email_certify']) { ?>
            <div class="af-row">
                <div class="af-label"><span class="form-label">인증일시</span></div>
                <div class="af-field">
                    <?php if ($mb['mb_email_certify'] == '0000-00-00 00:00:00') { ?>
                        <?php echo help('회원님이 메일을 수신할 수 없는 경우 등에 직접 인증처리를 하실 수 있습니다.') ?>
                        <label for="passive_certify" class="af-check form-label">
                            <input type="checkbox" name="passive_certify" id="passive_certify" class="form-checkbox">
                            <span class="form-label">수동인증</span>
                        </label>
                    <?php } else { ?>
                        <?php echo $mb['mb_email_certify'] ?>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php } ?>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_leave_date" class="form-label">탈퇴일자</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="mb_leave_date" value="<?php echo $mb['mb_leave_date'] ?>" id="mb_leave_date" maxlength="8" class="form-input">
                        <label for="mb_leave_date_set_today" class="af-check form-label">
                            <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_leave_date_set_today" class="form-checkbox" onclick="if (this.form.mb_leave_date.value==this.form.mb_leave_date.defaultValue) { this.form.mb_leave_date.value=this.value; } else { this.form.mb_leave_date.value=this.form.mb_leave_date.defaultValue; }">
                            <span class="form-label">탈퇴일을 오늘로 지정</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_intercept_date" class="form-label">접근차단일자</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="mb_intercept_date" value="<?php echo $mb['mb_intercept_date'] ?>" id="mb_intercept_date" maxlength="8" class="form-input">
                        <label for="mb_intercept_date_set_today" class="af-check form-label">
                            <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_intercept_date_set_today" class="form-checkbox" onclick="if (this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else { this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; }">
                            <span class="form-label">접근차단일을 오늘로 지정</span>
                        </label>
                    </div>
                </div>
            </div>
            <?php run_event('admin_member_form_add', $mb, $w, 'div'); ?>
        </div>
    </div>
</section>
