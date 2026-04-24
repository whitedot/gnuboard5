<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

if ($use_certify_js)
    add_javascript('<script src="' . $certify_script_url . '"></script>', 0);
?>
<div>
    <form name="fcertrefreshform" id="member_cert_refresh" action="<?php echo $action_url ?>" onsubmit="return fcertrefreshform_submit(this);" method="POST" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $form_mode ?>">
	<input type="hidden" name="url" value="<?php echo $return_url_encoded ?>">
	<input type="hidden" name="cert_type" value="<?php echo $cert_type_value; ?>">
    <input type="hidden" name="mb_id" value="<?php echo $member_id_value; ?>">
    <input type="hidden" name="mb_hp" value="<?php echo $member_hp_value; ?>">
    <input type="hidden" name="mb_name" value="<?php echo $member_name_value; ?>">
	<input type="hidden" name="cert_no" value="">
        <section id="member_cert_refresh_private">
            <h2>(필수) 추가 개인정보처리방침 안내</h2>
            
                
                    <table>
                        <caption>추가 개인정보처리방침 안내</caption>
                        <thead>
                            <tr>
                                <th>목적</th>
                                <th>항목</th>
                                <th>보유기간</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>이용자 식별 및 본인여부 확인</td>
                                <td><?php echo $privacy_fields_text; ?></td>
                                <td>회원 탈퇴 시까지</td>
                            </tr>
                        </tbody>
                    </table>
                
            

            <fieldset>
                <input type="checkbox" name="agree2" value="1" id="agree21">
                <label for="agree21"><span></span><b>추가 개인정보처리방침에 동의합니다.</b></label>
            </fieldset>
        </section>

        <section id="find_info">
            <h2>인증수단 선택하기</h2>
            
            <?php if ($show_certify_options) { ?>
            <div>
                <?php if ($show_simple_cert_button) { ?>
                <button type="button" id="win_sa_kakao_cert" class="win_sa_cert" data-type="">간편인증</button>
                <?php } ?>
                <?php if ($show_hp_cert_button) { ?>
                <button type="button" id="win_hp_cert">휴대폰 본인확인</button>
                <?php } ?>
            </div>
            <noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>
            <?php } ?>
            
        </section>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var pageTypeParam = "pageType=<?php echo $page_type; ?>";
            var f = document.fcertrefreshform;

            <?php if ($show_simple_cert_button) { ?>
                var simpleCertButtons = document.querySelectorAll(".win_sa_cert");
                var simpleCertUrl = "<?php echo $simple_cert_url; ?>";

                simpleCertButtons.forEach(function(button) {
                    button.addEventListener("click", function() {
                        if (!fcertrefreshform_submit(f)) return;
                        var type = this.dataset.type || "";
                        var requestUrl = simpleCertUrl + "?directAgency=" + encodeURIComponent(type) + "&" + pageTypeParam;
                        call_sa(requestUrl);
                    });
                });
            <?php } ?>

            <?php if ($show_hp_cert_button) { ?>
                var hpCertButton = document.getElementById("win_hp_cert");
                if (hpCertButton) {
                    hpCertButton.addEventListener("click", function() {
                        if (!fcertrefreshform_submit(f)) return;
                        var params = "?" + pageTypeParam;
                        <?php if ($hp_cert_error_message !== '') { ?>
                        alert("<?php echo $hp_cert_error_message; ?>");
                        return;
                        <?php } ?>

                        certify_win_open("<?php echo $hp_cert_type; ?>", "<?php echo $hp_cert_url; ?>" + params);
                    });
                }
            <?php } ?>
        });
        
        function fcertrefreshform_submit(f) {
            if (!f.agree2.checked) {
                alert(<?php echo json_encode($privacy_agree_required_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
                f.agree2.focus();
                return false;
            }

            return true;
        }
    </script>
</div>
