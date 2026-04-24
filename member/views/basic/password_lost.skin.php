<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

if ($use_certify_js)
    add_javascript('<script src="' . $certify_script_url . '"></script>', 0);
?>
<div id="find_info">
    
        <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
        <input type="hidden" name="cert_no" value="">
        <h3>이메일로 찾기</h3>
        <fieldset id="info_fs">
            <p>
                <?php echo nl2br($email_description_text); ?>
            </p>
            <label for="mb_email">E-mail 주소<strong>필수</strong></label>
            <input type="text" name="mb_email" id="mb_email" required size="30" placeholder="E-mail 주소">
        </fieldset>
        <?php echo captcha_html();  ?>

        
            <button type="submit"><?php echo $email_submit_label; ?></button>
        
        </form>
    
    <?php if ($show_certify_options) { ?> 
    <div>
        <h3>본인인증으로 찾기</h3>
        <div>
        <?php if ($show_simple_cert_button) { ?>
            <button type="button" id="win_sa_kakao_cert" class="win_sa_cert" data-type="">간편인증</button>
        <?php } ?>
        <?php if ($show_hp_cert_button) { ?>
            <button type="button" id="win_hp_cert">휴대폰 본인확인</button>
        <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
<script>    
document.addEventListener("DOMContentLoaded", function() {
    var pageTypeParam = "pageType=<?php echo $page_type; ?>";

	<?php if ($show_simple_cert_button) { ?>
	var simpleButtons = document.querySelectorAll(".win_sa_cert");
	var simpleUrl = "<?php echo $simple_cert_url; ?>";

	simpleButtons.forEach(function(button) {
		button.addEventListener("click", function() {
			var type = this.dataset.type || "";
			var requestUrl = simpleUrl + "?directAgency=" + encodeURIComponent(type) + "&" + pageTypeParam;
            call_sa(requestUrl);
		});
	});
    <?php } ?>
    <?php if ($show_hp_cert_button) { ?>
    var hpButton = document.getElementById("win_hp_cert");
    if (hpButton) {
        hpButton.addEventListener("click", function() {
            var params = "?" + pageTypeParam;
        <?php if ($hp_cert_error_message !== '') { ?>
            alert("<?php echo $hp_cert_error_message; ?>");
            return false;
        <?php } ?>
        
            certify_win_open("<?php echo $hp_cert_type; ?>", "<?php echo $hp_cert_url; ?>" + params);
        });
    }
    <?php } ?>
});
function fpasswordlost_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}
</script>
