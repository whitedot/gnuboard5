<section id="anc_mb_consent">
    <h2 class="h2_frm">수신 및 공개 설정</h2>
    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption>수신 및 공개 설정</caption>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row">광고성 이메일 수신</th>
                    <td>
                        <input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
                        <label for="mb_mailling_yes">예</label>
                        <input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
                        <label for="mb_mailling_no">아니오</label>
                        
                        <?php if($w == "u" && $mb['mb_mailling_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_mailling'] == 1 ? "<br>(동의 일자: ".$mb['mb_mailling_date'].")" : '';
                        } ?>
                    </td>
                    <th scope="row"><label for="mb_sms_yes">광고성 SMS/카카오톡 수신</label></th>
                    <td>
                        <input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
                        <label for="mb_sms_yes">예</label>
                        <input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
                        <label for="mb_sms_no">아니오</label>
                        <?php if($w == "u" && $mb['mb_sms_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_sms'] == 1 ? "<br>(동의 일자: ".$mb['mb_sms_date'].")" : '';
                        } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">마케팅 목적의<br>개인정보 수집 및 이용</th>
                    <td>
                        <input type="radio" name="mb_marketing_agree" value="1" id="mb_marketing_agree_yes" <?php echo $mb_marketing_agree_yes; ?>>
                        <label for="mb_marketing_agree_yes">예</label>
                        <input type="radio" name="mb_marketing_agree" value="0" id="mb_marketing_agree_no" <?php echo $mb_marketing_agree_no; ?>>
                        <label for="mb_marketing_agree_no">아니오</label>
                        
                        <?php if($w == "u" && $mb['mb_marketing_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_marketing_agree'] == 1 ? "<br>(동의 일자: ".$mb['mb_marketing_date'].")" : '';
                        } ?>
                    </td>
                    <th scope="row"><label for="mb_thirdparty_agree_yes">개인정보 제3자 제공</label></th>
                    <td>
                        <input type="radio" name="mb_thirdparty_agree" value="1" id="mb_thirdparty_agree_yes" <?php echo $mb_thirdparty_agree_yes; ?>>
                        <label for="mb_thirdparty_agree_yes">예</label>
                        <input type="radio" name="mb_thirdparty_agree" value="0" id="mb_thirdparty_agree_no" <?php echo $mb_thirdparty_agree_no; ?>>
                        <label for="mb_thirdparty_agree_no">아니오</label>
                        
                        <?php if($w == "u" && $mb['mb_thirdparty_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_thirdparty_agree'] == 1 ? "<br>(동의 일자: ".$mb['mb_thirdparty_date'].")" : '';
                        } ?>
                    </td>
                </tr>
                <?php if($w == "u"){?>
                <tr>
                    <th scope="row">약관동의 변경내역</th>
                    <td colspan="3">
                        <section id="sodr_request_log_wrap" class="ad_agree_log">
                            <div>
                                <?php echo conv_content($mb['mb_agree_log'], 0); ?>
                            </div>
                        </section>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="row">정보 공개</th>
                    <td colspan="3">
                        <input type="radio" name="mb_open" value="1" id="mb_open_yes" <?php echo $mb_open_yes; ?>>
                        <label for="mb_open_yes">예</label>
                        <input type="radio" name="mb_open" value="0" id="mb_open_no" <?php echo $mb_open_no; ?>>
                        <label for="mb_open_no">아니오</label>
                        <?php if($w == "u" && $mb['mb_open_date'] != "0000-00-00 00:00:00"){
                                echo $mb['mb_open'] == 1 ? "<br>(동의 일자: ".$mb['mb_open_date'].")" : '';
                        } ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
