<section id="anc_mb_consent" class="card">
    <div class="card-header">
        <h2 class="card-title">수신 및 공개 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="mb_mailling_yes" class="form-label">광고성 이메일 수신</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <?php foreach ($consent_view['mailing_options'] as $option) { ?>
                        <label for="mb_mailling_<?php echo $option['value']; ?>" class="af-check form-label">
                            <input type="radio" name="mb_mailling" value="<?php echo $option['value']; ?>" id="mb_mailling_<?php echo $option['value']; ?>"<?php echo $option['checked'] ? ' checked="checked"' : ''; ?> class="form-radio">
                            <span class="form-label"><?php echo $option['label']; ?></span>
                        </label>
                        <?php } ?>
                    </div>
                    <?php if ($consent_view['mailing_agree_date'] !== '') { ?>
                    <p class="hint-text">(동의 일자: <?php echo $consent_view['mailing_agree_date']; ?>)</p>
                    <?php } ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_marketing_agree_yes" class="form-label">마케팅 목적의 개인정보 수집 및 이용</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <?php foreach ($consent_view['marketing_agree_options'] as $option) { ?>
                        <label for="mb_marketing_agree_<?php echo $option['value']; ?>" class="af-check form-label">
                            <input type="radio" name="mb_marketing_agree" value="<?php echo $option['value']; ?>" id="mb_marketing_agree_<?php echo $option['value']; ?>"<?php echo $option['checked'] ? ' checked="checked"' : ''; ?> class="form-radio">
                            <span class="form-label"><?php echo $option['label']; ?></span>
                        </label>
                        <?php } ?>
                    </div>
                    <?php if ($consent_view['marketing_agree_date'] !== '') { ?>
                    <p class="hint-text">(동의 일자: <?php echo $consent_view['marketing_agree_date']; ?>)</p>
                    <?php } ?>
                </div>
            </div>

            <?php if ($consent_view['is_update']) { ?>
            <div class="af-row">
                <div class="af-label">
                    <label class="form-label">약관동의 변경내역</label>
                </div>
                <div class="af-field">
                    <section id="sodr_request_log_wrap">
                        <?php echo $consent_view['agree_log_html']; ?>
                    </section>
                </div>
            </div>
            <?php } ?>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_open_yes" class="form-label">정보공개</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <?php foreach ($consent_view['open_options'] as $option) { ?>
                        <label for="mb_open_<?php echo $option['value']; ?>" class="af-check form-label">
                            <input type="radio" name="mb_open" value="<?php echo $option['value']; ?>" id="mb_open_<?php echo $option['value']; ?>"<?php echo $option['checked'] ? ' checked="checked"' : ''; ?> class="form-radio">
                            <span class="form-label"><?php echo $option['label']; ?></span>
                        </label>
                        <?php } ?>
                    </div>
                    <?php if ($consent_view['open_date'] !== '') { ?>
                    <p class="hint-text">(동의 일자: <?php echo $consent_view['open_date']; ?>)</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
