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
                    <?php foreach ($history_view['cert_history_rows'] as $row) { ?>
                    <div>
                        [<?php echo $row['datetime']; ?>]
                        <?php echo $row['display_mb_id']; ?> /
                        <?php echo $row['name']; ?> /
                        <?php echo $row['hp']; ?> /
                        <?php echo $row['cert_type_label']; ?>
                    </div>
                    <?php } ?>
                    <?php if (!$history_view['cert_history_rows']) { ?>
                    <p class="hint-text">본인인증 내역이 없습니다.</p>
                    <?php } ?>
                </div>
            </div>

            <?php if ($history_view['is_update']) { ?>
            <div class="af-row">
                <div class="af-label"><label class="form-label">회원가입일</label></div>
                <div class="af-field"><?php echo $history_view['member_joined_at'] ?></div>
            </div>

            <div class="af-row">
                <div class="af-label"><label class="form-label">최근접속일</label></div>
                <div class="af-field"><?php echo $history_view['member_last_login_at'] ?></div>
            </div>

            <div class="af-row">
                <div class="af-label"><label class="form-label">IP</label></div>
                <div class="af-field"><?php echo $history_view['member_ip'] ?></div>
            </div>

            <?php if ($history_view['show_email_certify']) { ?>
            <div class="af-row">
                <div class="af-label"><span class="form-label">인증일시</span></div>
                <div class="af-field">
                    <?php if ($history_view['email_certify_at'] == '0000-00-00 00:00:00') { ?>
                        <p class="hint-text">회원님이 메일을 수신할 수 없는 경우 등에 직접 인증처리를 하실 수 있습니다.</p>
                        <label for="passive_certify" class="af-check form-label">
                            <input type="checkbox" name="passive_certify" id="passive_certify" class="form-checkbox">
                            <span class="form-label">수동인증</span>
                        </label>
                    <?php } else { ?>
                        <?php echo $history_view['email_certify_at'] ?>
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
                        <input type="text" name="mb_leave_date" value="<?php echo $history_view['leave_date'] ?>" id="mb_leave_date" maxlength="8" class="form-input">
                        <label for="mb_leave_date_set_today" class="af-check form-label">
                            <input type="checkbox" value="<?php echo $history_view['today_ymd']; ?>" id="mb_leave_date_set_today" class="form-checkbox" data-date-toggle-target="mb_leave_date">
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
                        <input type="text" name="mb_intercept_date" value="<?php echo $history_view['intercept_date'] ?>" id="mb_intercept_date" maxlength="8" class="form-input">
                        <label for="mb_intercept_date_set_today" class="af-check form-label">
                            <input type="checkbox" value="<?php echo $history_view['today_ymd']; ?>" id="mb_intercept_date_set_today" class="form-checkbox" data-date-toggle-target="mb_intercept_date">
                            <span class="form-label">접근차단일을 오늘로 지정</span>
                        </label>
                    </div>
                </div>
            </div>
            <?php run_event('admin_member_form_add', $history_view['event_member'], $history_view['event_mode'], 'div'); ?>
        </div>
    </div>
</section>
