<section id="anc_cf_mail" class="card">
    <div class="card-header">
        <h2 class="card-title">인증 메일 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_use" class="form-label">메일발송 사용</label>
                </div>
                <div class="af-field">
                    <?php echo help('체크하지 않으면 인증 메일과 비밀번호 재설정 메일도 발송되지 않습니다.') ?>
                    <label for="cf_email_use" class="af-check form-label">
                        <input type="checkbox" name="cf_email_use" value="1" id="cf_email_use" <?php echo $config['cf_email_use'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_use_email_certify" class="form-label">메일인증 사용</label>
                </div>
                <div class="af-field">
                    <?php echo help('메일에 배달된 인증 주소를 클릭해야 회원 가입이 완료됩니다.') ?>
                    <label for="cf_use_email_certify" class="af-check form-label">
                        <input type="checkbox" name="cf_use_email_certify" value="1" id="cf_use_email_certify" <?php echo $config['cf_use_email_certify'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

        </div>
    </div>
</section>

