<section id="anc_mb_basic" class="card">
    <div class="card-header">
        <h2 class="card-title">기본 정보</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="mb_id" class="form-label">아이디<?php echo $sound_only ?></label>
                </div>
                <div class="af-field">
                    <?php if (!empty($mask_preserved_id)) { ?>
                        <input type="hidden" name="mb_id" value="<?php echo $mb['mb_id'] ?>">
                        <input type="text" value="<?php echo $display_mb_id ?>" id="mb_id" readonly class="form-input" size="15" maxlength="20">
                        <p class="hint-text">탈퇴 회원 아이디는 내부 식별용으로만 유지되고 화면에서는 일부 마스킹됩니다.</p>
                    <?php } else { ?>
                        <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="<?php echo $required_mb_id_class ?> form-input" size="15" maxlength="20">
                    <?php } ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_password" class="form-label">비밀번호<?php echo $sound_only ?></label>
                </div>
                <div class="af-field">
                    <div>
                        <input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="<?php echo $required_mb_password ?> form-input" size="15" maxlength="20" autocomplete="new-password" readonly data-lpignore="true" data-1p-ignore="true">
                    </div>
                    <div id="mb_password_captcha_wrap" style="display:none">
                        <?php
                        require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
                        $captcha_html = captcha_html();
                        $captcha_js   = chk_captcha_js();
                        echo $captcha_html;
                        ?>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_name" class="form-label">이름(실명)<strong class="caption-sr-only">필수</strong></label>
                </div>
                <div class="af-field">
                    <input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required form-input" size="15" maxlength="20">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_nick" class="form-label">닉네임<strong class="caption-sr-only">필수</strong></label>
                </div>
                <div class="af-field">
                    <input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick" required class="required form-input" size="15" maxlength="20">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_level" class="form-label">회원 권한</label>
                </div>
                <div class="af-field">
                    <select id="mb_level" name="mb_level" class="form-select">
                        <?php foreach ($member_level_options as $option) { ?>
                            <option value="<?php echo $option['value']; ?>"<?php echo $option['selected'] ? ' selected="selected"' : ''; ?>><?php echo $option['label']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_email" class="form-label">E-mail<strong class="caption-sr-only">필수</strong></label>
                </div>
                <div class="af-field">
                    <input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required form-input" size="30">
                </div>
            </div>

        </div>
    </div>
</section>
