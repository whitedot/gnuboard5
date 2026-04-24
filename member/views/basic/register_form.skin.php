<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

add_javascript('<script src="' . $register_form_script_url . '"></script>', 0);
if ($use_certify_js)
    add_javascript('<script src="' . $certify_script_url . '"></script>', 0);
?>
		<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="w" value="<?php echo $form_mode ?>">
	<input type="hidden" name="url" value="<?php echo isset($urlencode) ? $urlencode : ''; ?>">
	<input type="hidden" name="agree" value="<?php echo $agree ?>">
	<input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
	<input type="hidden" name="cert_type" value="<?php echo $cert_type_value; ?>">
	<input type="hidden" name="cert_no" value="">
	<?php if ($show_hidden_mb_sex) { ?><input type="hidden" name="mb_sex" value="<?php echo $hidden_mb_sex_value ?>"><?php } ?>
	<?php if ($show_locked_nick_hidden_fields) { ?>
	<input type="hidden" name="mb_nick_default" value="<?php echo $locked_nick_default_value; ?>">
	<input type="hidden" name="mb_nick" value="<?php echo $locked_nick_value; ?>">
	<?php } ?>
	
	<div id="register_form">   
	    <div>
	        <h2>사이트 이용정보 입력</h2>
	        <ul>
	            <li>
	                <label for="reg_mb_id">
	                	아이디 (필수)
	                	<button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
						<span class="tooltip">영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</span>
	                </label>
	                <input type="text" name="mb_id" value="<?php echo $member_id_value ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="<?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20" placeholder="아이디">
	                <span id="msg_mb_id"></span>
	            </li>
	            <li>
	                <label for="reg_mb_password">비밀번호 (필수)</label>
	                <input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="<?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호">
				</li>
	            <li>
	                <label for="reg_mb_password_re">비밀번호 확인 (필수)</label>
	                <input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="<?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호 확인">
	            </li>
	        </ul>
	    </div>
	
	    <div>
	        <h2>개인정보 입력</h2>
	        <ul>
                <?php if ($show_certify_section) { ?>
				<li>
	                <?php if ($show_simple_cert_button) { ?>
                    <button type="button" id="win_sa_kakao_cert" class="win_sa_cert" data-type="">간편인증</button>
                    <?php } ?>
                    <?php if ($show_hp_cert_button) { ?>
                    <button type="button" id="win_hp_cert">휴대폰 본인확인</button>
                    <?php } ?>
                    <?php if ($show_certify_required_text) { ?>
                    <span>(필수)</span>
                    <?php } ?>
                    <noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>
		                <?php if ($show_certify_status) { ?>
		                <div id="msg_certify">
		                    <strong><?php echo $certify_status_text; ?></strong>
		                </div>
						<?php } ?>
				</li>
				<?php } ?>
	            <li>
	                <label for="reg_mb_name">이름 (필수)<?php echo $desc_name_text !== '' ? '<span>' . $desc_name_text . '</span>' : ''; ?></label>
	                <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo $member_name_value ?>" <?php echo $required ?> <?php echo $name_readonly; ?> class="<?php echo $required ?> <?php echo $name_readonly ?>" size="10" placeholder="이름">
	            </li>
	            <?php if ($req_nick) {  ?>
	            <li>
	                <label for="reg_mb_nick">
	                	닉네임 (필수)
	                	<button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
						<span class="tooltip"><?php echo $nick_tooltip_text; ?></span>
	                </label>
	                
                    <input type="hidden" name="mb_nick_default" value="<?php echo $member_nick_value; ?>">
                    <input type="text" name="mb_nick" value="<?php echo $member_nick_value; ?>" id="reg_mb_nick" required size="10" maxlength="20" placeholder="닉네임">
                    <span id="msg_mb_nick"></span>	                
	            </li>
	            <?php }  ?>
	
	            <li>
	                <label for="reg_mb_email">E-mail (필수)
	                
	                <?php if ($show_email_certify_help) { ?>
	                <button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
					<span class="tooltip">
	                    <?php echo $email_certify_help_text; ?>
	                </span>
	                <?php }  ?>
					</label>

	                <input type="hidden" name="old_email" value="<?php echo $member_email_value ?>">
	                <input type="text" name="mb_email" value="<?php echo $member_email_value; ?>" id="reg_mb_email" required size="70" maxlength="100" placeholder="E-mail">
	            </li>
	
				<li>
	            <?php if ($show_hp_field) {  ?>
	                <label for="reg_mb_hp">휴대폰번호<?php if (!empty($hp_required)) { ?> (필수)<?php } ?><?php echo $desc_phone_text !== '' ? '<span>' . $desc_phone_text . '</span>' : ''; ?></label>
	                
	                <input type="text" name="mb_hp" value="<?php echo $member_hp_value; ?>" id="reg_mb_hp" <?php echo $hp_required; ?> <?php echo $hp_readonly; ?> class="<?php echo $hp_required; ?> <?php echo $hp_readonly; ?>" maxlength="20" placeholder="휴대폰번호">
	                <?php if ($show_old_hp_field) { ?>
	                <input type="hidden" name="old_mb_hp" value="<?php echo $member_hp_value; ?>">
	                <?php } ?>
	            <?php }  ?>
	            </li>
	
	        </ul>
	    </div>
	
	    <div>
	        <h2>기타 개인설정</h2>
	        <ul>
		        <?php if ($show_open_checkbox) { ?>
		        <li>
		            <input type="checkbox" name="mb_open" value="1" id="reg_mb_open" <?php echo $mb_open_checked; ?>>
		      		<label for="reg_mb_open">
		      			<span></span>
		      			<b>정보공개</b>
		      		</label>      
		            <span>다른분들이 나의 정보를 볼 수 있도록 합니다.</span>
		            <button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
		            <span class="tooltip">
		                <?php echo $open_modify_tooltip_text; ?>
		            </span>
		            <input type="hidden" name="mb_open_default" value="<?php echo $member_open_value ?>"> 
		        </li>		        
		        <?php } else { ?>
	            <li>
	                정보공개
	                <input type="hidden" name="mb_open" value="<?php echo $member_open_value ?>">
	                <button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
	                <span class="tooltip">
	                    <?php echo $open_locked_tooltip_text; ?>
	                </span>
	                
	            </li>
	            <?php }  ?>
	
	        </ul>
	    </div>

		<!-- 회원가입 약관 동의에 광고성 정보 수신 동의 표시 여부가 사용시에만 -->
		<?php if($show_promotion_section) { ?>
		<div>
			<h2>수신설정</h2>
			<!-- 수신설정만 팝업 및 체크박스 관련 class 적용 -->
			<ul>
				<!-- (선택) 마케팅 목적의 개인정보 수집 및 이용 -->
				<li>
					<div>
						<input type="checkbox" name="mb_marketing_agree" value="1" id="reg_mb_marketing_agree" aria-describedby="desc_marketing" <?php echo $marketing_agree_checked; ?>>
						<label for="reg_mb_marketing_agree"><span></span><b>(선택) 마케팅 목적의 개인정보 수집 및 이용</b></label>
						<span>(선택) 마케팅 목적의 개인정보 수집 및 이용</span>
						<button type="button" class="js-open-consent" data-hs-overlay="#consentDialog" data-title="마케팅 목적의 개인정보 수집 및 이용" data-template="#tpl_marketing" data-check="#reg_mb_marketing_agree" aria-controls="consentDialog">자세히보기</button>
					</div>
					<input type="hidden" name="mb_marketing_agree_default" value="<?php echo $member_marketing_agree_value ?>">
					<div id="desc_marketing"><?php echo $consent_marketing_description; ?></div>
					<?php echo $marketing_agree_date_text; ?>

					<template id="tpl_marketing">
						* 목적: 서비스 마케팅 및 프로모션<br>
						* 항목: 이름, 이메일<?php echo $marketing_template_phone_text; ?><br>
						* 보유기간: 회원 탈퇴 시까지<br>
						동의를 거부하셔도 서비스 기본 이용은 가능하나, 맞춤형 혜택 제공은 제한될 수 있습니다.
					</template>
				</li>

				<!-- (선택) 광고성 정보 수신 동의 (상위) -->
				<li>
				<div>
					<input type="checkbox" name="mb_promotion_agree" value="1" id="reg_mb_promotion_agree" aria-describedby="desc_promotion">
					<label for="reg_mb_promotion_agree"><span></span><b>(선택) 광고성 정보 수신 동의</b></label>
					<span>(선택) 광고성 정보 수신 동의</span>
					<button type="button" class="js-open-consent" data-hs-overlay="#consentDialog" data-title="광고성 정보 수신 동의" data-template="#tpl_promotion" data-check="#reg_mb_promotion_agree" data-check-group=".child-promo" aria-controls="consentDialog">자세히보기</button>
				</div>
				
				<div id="desc_promotion"><?php echo $consent_promotion_description; ?></div>

				<!-- 하위 채널(이메일) -->
				<ul>
					<li>
						<input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" <?php echo $mailling_checked; ?> class="child-promo">
						<label for="reg_mb_mailling"><span></span><b>광고성 이메일 수신 동의</b></label>
						<span>광고성 이메일 수신 동의</span>
						<input type="hidden" name="mb_mailling_default" value="<?php echo $member_mailling_value; ?>">
						<?php echo $mailling_agree_date_text; ?>
					</li>
				</ul>

				<template id="tpl_promotion">
					수집·이용에 동의한 개인정보를 이용하여 이메일로 오전 8시~오후 9시에 광고성 정보를 전송할 수 있습니다.<br>
					동의는 언제든지 마이페이지에서 철회할 수 있습니다.
				</template>
				</li>
			</ul>
		</div>
		<?php } ?>

		<div>
			<h2>자동등록방지</h2>
			<ul>
				<li>
					자동등록방지
					<?php echo captcha_html(); ?>
				</li>
			</ul>
		</div>
	</div>
	<div>
	    <a href="<?php echo $cancel_url ?>">취소</a>
        <?php if ($show_leave_link) { ?>
        <span>|</span>
        <a href="<?php echo $leave_url; ?>">회원탈퇴</a>
        <?php } ?>
	    <button type="submit" id="btn_submit" accesskey="s"><?php echo $submit_label; ?></button>
	</div>
	</form>


<?php include_once(__DIR__ . '/consent_modal.inc.php'); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var pageTypeParam = "pageType=<?php echo $register_page_type; ?>";

	<?php if($show_simple_cert_button) { ?>
	var certifyButtons = document.querySelectorAll(".win_sa_cert");
	var simpleCertUrl = "<?php echo $simple_cert_url; ?>";

	certifyButtons.forEach(function(button) {
		button.addEventListener("click", function() {
			if(!cert_confirm()) return;
			var type = this.dataset.type || "";
			var requestUrl = simpleCertUrl + "?directAgency=" + encodeURIComponent(type) + "&" + pageTypeParam;
			call_sa(requestUrl);
		});
	});
    <?php } ?>
    <?php if($show_hp_cert_button) { ?>
    var hpCertButton = document.getElementById("win_hp_cert");
    if (hpCertButton) {
        hpCertButton.addEventListener("click", function() {
		    if(!cert_confirm()) return;
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

// submit 최종 폼체크
function fregisterform_submit(f)
{
    // 회원아이디 검사
    if (f.w.value == "") {
        var msg = reg_mb_id_check();
        if (msg) {
            alert(msg);
            f.mb_id.select();
            return false;
        }
    }

    if (f.w.value == "") {
        if (f.mb_password.value.length < 3) {
            alert(<?php echo json_encode($password_length_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
            f.mb_password.focus();
            return false;
        }
    }

    if (f.mb_password.value != f.mb_password_re.value) {
        alert(<?php echo json_encode($password_mismatch_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        f.mb_password_re.focus();
        return false;
    }

    if (f.mb_password.value.length > 0) {
        if (f.mb_password_re.value.length < 3) {
            alert(<?php echo json_encode($password_length_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
            f.mb_password_re.focus();
            return false;
        }
    }

    // 이름 검사
    if (f.w.value=="") {
        if (f.mb_name.value.length < 1) {
            alert(<?php echo json_encode($name_required_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
            f.mb_name.focus();
            return false;
        }

    }

    <?php if ($require_certification_on_submit) { ?>
    // 본인확인 체크
    if(f.cert_no.value=="") {
        alert(<?php echo json_encode($certify_prompt_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        return false;
    }
    <?php } ?>

    // 닉네임 검사
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
        var msg = reg_mb_nick_check();
        if (msg) {
            alert(msg);
            f.mb_nick.select();
            return false;
        }
    }

    // E-mail 검사
    if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
        var msg = reg_mb_email_check();
        if (msg) {
            alert(msg);
            f.mb_email.select();
            return false;
        }
    }

    <?php if ($require_hp_validation_on_submit) { ?>
    // 휴대폰번호 체크
    var msg = reg_mb_hp_check();
    if (msg) {
        alert(msg);
        f.mb_hp.select();
        return false;
    }
    <?php } ?>

    <?php echo chk_captcha_js();  ?>

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

document.addEventListener("DOMContentLoaded", function() {
    function hideAllTooltips() {
        document.querySelectorAll(".tooltip").forEach(function(tooltip) {
            tooltip.style.display = "none";
        });
    }

    document.addEventListener("click", function(event) {
        var trigger = event.target.closest(".tooltip_icon");
        if (!trigger) {
            hideAllTooltips();
            return;
        }

        event.preventDefault();
        hideAllTooltips();

        var tooltip = trigger.nextElementSibling;
        if (tooltip && tooltip.classList.contains("tooltip")) {
            tooltip.style.display = "inline-block";
        }
    });

    document.addEventListener("mouseout", function(event) {
        var trigger = event.target.closest(".tooltip_icon");
        if (!trigger) {
            return;
        }

        var tooltip = trigger.nextElementSibling;
        if (tooltip && tooltip.classList.contains("tooltip")) {
            tooltip.style.display = "none";
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
  const parentPromo = document.getElementById('reg_mb_promotion_agree');
  const childPromo  = Array.from(document.querySelectorAll('.child-promo'));
  if (!parentPromo || childPromo.length === 0) return;

  const syncParentFromChildren = () => {
    const anyChecked = childPromo.some(cb => cb.checked);
    parentPromo.checked = anyChecked; // 하나라도 체크되면 부모 체크
  };

  const syncChildrenFromParent = () => {
    const isChecked = parentPromo.checked;
    childPromo.forEach(cb => {
      cb.checked = isChecked;
      cb.dispatchEvent(new Event('change', { bubbles: true }));
    });
  };

  syncParentFromChildren();

  parentPromo.addEventListener('change', syncChildrenFromParent);
  childPromo.forEach(cb => cb.addEventListener('change', syncParentFromChildren));
});


</script>
