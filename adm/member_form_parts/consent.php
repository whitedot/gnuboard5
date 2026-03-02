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
                        <label for="mb_mailling_yes" class="af-check form-label">
                            <input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?> class="form-radio">
                            <span class="form-label">예</span>
                        </label>
                        <label for="mb_mailling_no" class="af-check form-label">
                            <input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?> class="form-radio">
                            <span class="form-label">아니오</span>
                        </label>
                    </div>
                    <?php if ($w == "u" && $mb['mb_mailling_date'] != "0000-00-00 00:00:00" && $mb['mb_mailling'] == 1) { ?>
                    <p class="hint-text">(동의 일자: <?php echo $mb['mb_mailling_date']; ?>)</p>
                    <?php } ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_sms_yes" class="form-label">광고성 SMS/카카오톡 수신</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="mb_sms_yes" class="af-check form-label">
                            <input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?> class="form-radio">
                            <span class="form-label">예</span>
                        </label>
                        <label for="mb_sms_no" class="af-check form-label">
                            <input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?> class="form-radio">
                            <span class="form-label">아니오</span>
                        </label>
                    </div>
                    <?php if ($w == "u" && $mb['mb_sms_date'] != "0000-00-00 00:00:00" && $mb['mb_sms'] == 1) { ?>
                    <p class="hint-text">(동의 일자: <?php echo $mb['mb_sms_date']; ?>)</p>
                    <?php } ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_marketing_agree_yes" class="form-label">마케팅 목적의 개인정보 수집 및 이용</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="mb_marketing_agree_yes" class="af-check form-label">
                            <input type="radio" name="mb_marketing_agree" value="1" id="mb_marketing_agree_yes" <?php echo $mb_marketing_agree_yes; ?> class="form-radio">
                            <span class="form-label">예</span>
                        </label>
                        <label for="mb_marketing_agree_no" class="af-check form-label">
                            <input type="radio" name="mb_marketing_agree" value="0" id="mb_marketing_agree_no" <?php echo $mb_marketing_agree_no; ?> class="form-radio">
                            <span class="form-label">아니오</span>
                        </label>
                    </div>
                    <?php if ($w == "u" && $mb['mb_marketing_date'] != "0000-00-00 00:00:00" && $mb['mb_marketing_agree'] == 1) { ?>
                    <p class="hint-text">(동의 일자: <?php echo $mb['mb_marketing_date']; ?>)</p>
                    <?php } ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_thirdparty_agree_yes" class="form-label">개인정보 제3자 제공</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <label for="mb_thirdparty_agree_yes" class="af-check form-label">
                            <input type="radio" name="mb_thirdparty_agree" value="1" id="mb_thirdparty_agree_yes" <?php echo $mb_thirdparty_agree_yes; ?> class="form-radio">
                            <span class="form-label">예</span>
                        </label>
                        <label for="mb_thirdparty_agree_no" class="af-check form-label">
                            <input type="radio" name="mb_thirdparty_agree" value="0" id="mb_thirdparty_agree_no" <?php echo $mb_thirdparty_agree_no; ?> class="form-radio">
                            <span class="form-label">아니오</span>
                        </label>
                    </div>
                    <?php if ($w == "u" && $mb['mb_thirdparty_date'] != "0000-00-00 00:00:00" && $mb['mb_thirdparty_agree'] == 1) { ?>
                    <p class="hint-text">(동의 일자: <?php echo $mb['mb_thirdparty_date']; ?>)</p>
                    <?php } ?>
                </div>
            </div>

            <?php if ($w == "u") { ?>
            <div class="af-row">
                <div class="af-label">
                    <label class="form-label">약관동의 변경내역</label>
                </div>
                <div class="af-field">
                    <section id="sodr_request_log_wrap">
                        <?php echo conv_content($mb['mb_agree_log'], 0); ?>
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
                        <label for="mb_open_yes" class="af-check form-label">
                            <input type="radio" name="mb_open" value="1" id="mb_open_yes" <?php echo $mb_open_yes; ?> class="form-radio">
                            <span class="form-label">예</span>
                        </label>
                        <label for="mb_open_no" class="af-check form-label">
                            <input type="radio" name="mb_open" value="0" id="mb_open_no" <?php echo $mb_open_no; ?> class="form-radio">
                            <span class="form-label">아니오</span>
                        </label>
                    </div>
                    <?php if ($w == "u" && $mb['mb_open_date'] != "0000-00-00 00:00:00" && $mb['mb_open'] == 1) { ?>
                    <p class="hint-text">(동의 일자: <?php echo $mb['mb_open_date']; ?>)</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
