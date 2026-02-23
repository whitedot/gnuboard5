<section id="anc_mb_consent">
    <h2>수신 및 공개 설정</h2>
    
        
            
            
            
                        <div class="ui-form-row">
            <div class="ui-form-label">광고성 이메일 수신</div>
            <div class="ui-form-field"><input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
                        <label for="mb_mailling_yes">예</label>
                        <input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
                        <label for="mb_mailling_no">아니오</label>
                        
                        <?php if($w == "u" && $mb['mb_mailling_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_mailling'] == 1 ? "<br>(동의 일자: ".$mb['mb_mailling_date'].")" : '';
                        } ?></div>
        </div>
        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_sms_yes">광고성 SMS/카카오톡 수신</label></div>
            <div class="ui-form-field"><input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
                        <label for="mb_sms_yes">예</label>
                        <input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
                        <label for="mb_sms_no">아니오</label>
                        <?php if($w == "u" && $mb['mb_sms_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_sms'] == 1 ? "<br>(동의 일자: ".$mb['mb_sms_date'].")" : '';
                        } ?></div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label">마케팅 목적의<br>개인정보 수집 및 이용</div>
            <div class="ui-form-field"><input type="radio" name="mb_marketing_agree" value="1" id="mb_marketing_agree_yes" <?php echo $mb_marketing_agree_yes; ?>>
                        <label for="mb_marketing_agree_yes">예</label>
                        <input type="radio" name="mb_marketing_agree" value="0" id="mb_marketing_agree_no" <?php echo $mb_marketing_agree_no; ?>>
                        <label for="mb_marketing_agree_no">아니오</label>
                        
                        <?php if($w == "u" && $mb['mb_marketing_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_marketing_agree'] == 1 ? "<br>(동의 일자: ".$mb['mb_marketing_date'].")" : '';
                        } ?></div>
        </div>
        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_thirdparty_agree_yes">개인정보 제3자 제공</label></div>
            <div class="ui-form-field"><input type="radio" name="mb_thirdparty_agree" value="1" id="mb_thirdparty_agree_yes" <?php echo $mb_thirdparty_agree_yes; ?>>
                        <label for="mb_thirdparty_agree_yes">예</label>
                        <input type="radio" name="mb_thirdparty_agree" value="0" id="mb_thirdparty_agree_no" <?php echo $mb_thirdparty_agree_no; ?>>
                        <label for="mb_thirdparty_agree_no">아니오</label>
                        
                        <?php if($w == "u" && $mb['mb_thirdparty_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_thirdparty_agree'] == 1 ? "<br>(동의 일자: ".$mb['mb_thirdparty_date'].")" : '';
                        } ?></div>
        </div>
                <?php if($w == "u"){?>
                        <div class="ui-form-row">
            <div class="ui-form-label">약관동의 변경내역</div>
            <div class="ui-form-field"><section id="sodr_request_log_wrap">
                            
                                <?php echo conv_content($mb['mb_agree_log'], 0); ?>
                            
                        </section></div>
        </div>
                <?php } ?>
                        <div class="ui-form-row">
            <div class="ui-form-label">정보 공개</div>
            <div class="ui-form-field"><input type="radio" name="mb_open" value="1" id="mb_open_yes" <?php echo $mb_open_yes; ?>>
                        <label for="mb_open_yes">예</label>
                        <input type="radio" name="mb_open" value="0" id="mb_open_no" <?php echo $mb_open_no; ?>>
                        <label for="mb_open_no">아니오</label>
                        <?php if($w == "u" && $mb['mb_open_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_open'] == 1 ? "<br>(동의 일자: ".$mb['mb_open_date'].")" : '';
                        } ?></div>
        </div>
            
        
    
</section>
