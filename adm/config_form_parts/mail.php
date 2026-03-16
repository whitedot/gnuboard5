<section id="anc_cf_mail" class="card">
    <div class="card-header">
        <h2 class="card-title">기본 메일 환경 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_use" class="form-label">메일발송 사용</label>
                </div>
                <div class="af-field">
                    <?php echo help('체크하지 않으면 메일발송을 아예 사용하지 않습니다. 메일 테스트도 불가합니다.') ?>
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
                    <?php $tmp = !(defined('G5_SOCIAL_CERTIFY_MAIL') && G5_SOCIAL_CERTIFY_MAIL) ? '<br>( SNS를 이용한 소셜로그인 한 회원은 회원메일인증을 하지 않습니다. 일반회원에게만 해당됩니다. )' : ''; ?>
                    <?php echo help('메일에 배달된 인증 주소를 클릭하여야 회원으로 인정합니다.' . $tmp); ?>
                    <label for="cf_use_email_certify" class="af-check form-label">
                        <input type="checkbox" name="cf_use_email_certify" value="1" id="cf_use_email_certify" <?php echo $config['cf_use_email_certify'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

        </div>
    </div>
</section>

<section id="anc_cf_join_mail" class="card">
    <div class="card-header">
        <h2 class="card-title">회원가입 시 메일 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_mb_super_admin" class="form-label">최고관리자 메일발송</label>
                </div>
                <div class="af-field">
                    <?php echo help('최고관리자에게 메일을 발송합니다.') ?>
                    <label for="cf_email_mb_super_admin" class="af-check form-label">
                        <input type="checkbox" name="cf_email_mb_super_admin" value="1" id="cf_email_mb_super_admin" <?php echo $config['cf_email_mb_super_admin'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_mb_member" class="form-label">회원님께 메일발송</label>
                </div>
                <div class="af-field">
                    <?php echo help('회원가입한 회원님께 메일을 발송합니다.') ?>
                    <label for="cf_email_mb_member" class="af-check form-label">
                        <input type="checkbox" name="cf_email_mb_member" value="1" id="cf_email_mb_member" <?php echo $config['cf_email_mb_member'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>

