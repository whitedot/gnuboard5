<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

add_javascript('<script src="'.G5_JS_URL.'/register_form.js"></script>', 0);
if ($config['cf_cert_use'] && ($config['cf_cert_simple'] || $config['cf_cert_ipin'] || $config['cf_cert_hp']))
    add_javascript('<script src="'.G5_JS_URL.'/certify.js?v='.G5_JS_VER.'"></script>', 0);
?>

<!-- 회원정보 입력/수정 시작 { -->


	<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="url" value="<?php echo $urlencode ?>">
	<input type="hidden" name="agree" value="<?php echo $agree ?>">
	<input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
	<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
	<input type="hidden" name="cert_no" value="">
	<?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
	<?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
	<input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
	<input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
	<?php }  ?>
	
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
	                <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="<?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20" placeholder="아이디">
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
                <?php 
					$desc_name = '';
					$desc_phone = '';
					if ($config['cf_cert_use']) {
                        $desc_name = '<span> 본인확인 시 자동입력</span>';
                        $desc_phone = '<span> 본인확인 시 자동입력</span>';

                        if (!$config['cf_cert_simple'] && !$config['cf_cert_hp']) {
                            $desc_phone = '';
                        }
				?>
				<li>
	                <?php
						if ($config['cf_cert_simple']) {
                            echo '<button type="button" id="win_sa_kakao_cert" class="win_sa_cert" data-type="">간편인증</button>'.PHP_EOL;
						}
						if ($config['cf_cert_hp'])
							echo '<button type="button" id="win_hp_cert">휴대폰 본인확인</button>'.PHP_EOL;
	
                        echo '<span>(필수)</span>';
	                    echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
	                ?>
	                <?php
	                if ($member['mb_certify']) {
						switch ($member['mb_certify']) {
							case "simple": 
								$mb_cert = "간편인증";
								break;
							case "ipin": 
								$mb_cert = "아이핀";
								break;
							case "hp": 
								$mb_cert = "휴대폰";
								break;
						}                 
	                ?>
	                <div id="msg_certify">
	                    <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
	                </div>
					<?php } ?>
				</li>
				<?php } ?>
	            <li>
	                <label for="reg_mb_name">이름 (필수)<?php echo $desc_name ?></label>
	                <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $name_readonly; ?> class="<?php echo $required ?> <?php echo $name_readonly ?>" size="10" placeholder="이름">
	            </li>
	            <?php if ($req_nick) {  ?>
	            <li>
	                <label for="reg_mb_nick">
	                	닉네임 (필수)
	                	<button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
						<span class="tooltip">공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)<br> 닉네임을 바꾸시면 앞으로 <?php echo (int)$config['cf_nick_modify'] ?>일 이내에는 변경 할 수 없습니다.</span>
	                </label>
	                
                    <input type="hidden" name="mb_nick_default" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>">
                    <input type="text" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>" id="reg_mb_nick" required size="10" maxlength="20" placeholder="닉네임">
                    <span id="msg_mb_nick"></span>	                
	            </li>
	            <?php }  ?>
	
	            <li>
	                <label for="reg_mb_email">E-mail (필수)
	                
	                <?php if ($config['cf_use_email_certify']) {  ?>
	                <button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
					<span class="tooltip">
	                    <?php if ($w=='') { echo "E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다."; }  ?>
	                    <?php if ($w=='u') { echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다."; }  ?>
	                </span>
	                <?php }  ?>
					</label>

	                <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
	                <input type="text" name="mb_email" value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required size="70" maxlength="100" placeholder="E-mail">
	            </li>
	
				<li>
	            <?php if ($config['cf_use_hp'] || ($config["cf_cert_use"] && ($config['cf_cert_hp'] || $config['cf_cert_simple']))) {  ?>
	                <label for="reg_mb_hp">휴대폰번호<?php if (!empty($hp_required)) { ?> (필수)<?php } ?><?php echo $desc_phone ?></label>
	                
	                <input type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp" <?php echo $hp_required; ?> <?php echo $hp_readonly; ?> class="<?php echo $hp_required; ?> <?php echo $hp_readonly; ?>" maxlength="20" placeholder="휴대폰번호">
	                <?php if ($config['cf_cert_use'] && ($config['cf_cert_hp'] || $config['cf_cert_simple'])) { ?>
	                <input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
	                <?php } ?>
	            <?php }  ?>
	            </li>
	
	        </ul>
	    </div>
	
	    <div>
	        <h2>기타 개인설정</h2>
	        <ul>
		        <?php if (isset($member['mb_open_date']) && $member['mb_open_date'] <= date("Y-m-d", G5_SERVER_TIME - ($config['cf_open_modify'] * 86400)) || empty($member['mb_open_date'])) { // 정보공개 수정일이 지났다면 수정가능 ?>
		        <li>
		            <input type="checkbox" name="mb_open" value="1" id="reg_mb_open" <?php echo ($w=='' || $member['mb_open'])?'checked':''; ?>>
		      		<label for="reg_mb_open">
		      			<span></span>
		      			<b>정보공개</b>
		      		</label>      
		            <span>다른분들이 나의 정보를 볼 수 있도록 합니다.</span>
		            <button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
		            <span class="tooltip">
		                정보공개를 바꾸시면 앞으로 <?php echo (int)$config['cf_open_modify'] ?>일 이내에는 변경이 안됩니다.
		            </span>
		            <input type="hidden" name="mb_open_default" value="<?php echo $member['mb_open'] ?>"> 
		        </li>		        
		        <?php } else { ?>
	            <li>
	                정보공개
	                <input type="hidden" name="mb_open" value="<?php echo $member['mb_open'] ?>">
	                <button type="button" class="tooltip_icon"><i aria-hidden="true"></i><span>설명보기</span></button>
	                <span class="tooltip">
	                    정보공개는 수정후 <?php echo (int)$config['cf_open_modify'] ?>일 이내, <?php echo date("Y년 m월 j일", isset($member['mb_open_date']) ? strtotime("{$member['mb_open_date']} 00:00:00")+$config['cf_open_modify']*86400:G5_SERVER_TIME+$config['cf_open_modify']*86400); ?> 까지는 변경이 안됩니다.<br>
	                    공개 범위가 자주 바뀌면 운영 정책과 사용자 기대가 어긋날 수 있어 일정 기간 동안 변경을 제한합니다.
	                </span>
	                
	            </li>
	            <?php }  ?>
	
	        </ul>
	    </div>

		<!-- 회원가입 약관 동의에 광고성 정보 수신 동의 표시 여부가 사용시에만 -->
		<?php if($config['cf_use_promotion'] == 1) { ?>
		<div>
			<h2>수신설정</h2>
			<!-- 수신설정만 팝업 및 체크박스 관련 class 적용 -->
			<ul>
				<!-- (선택) 마케팅 목적의 개인정보 수집 및 이용 -->
				<li>
					<div>
						<input type="checkbox" name="mb_marketing_agree" value="1" id="reg_mb_marketing_agree" aria-describedby="desc_marketing" <?php echo $member['mb_marketing_agree'] ? 'checked' : ''; ?>>
						<label for="reg_mb_marketing_agree"><span></span><b>(선택) 마케팅 목적의 개인정보 수집 및 이용</b></label>
						<span>(선택) 마케팅 목적의 개인정보 수집 및 이용</span>
						<button type="button" class="js-open-consent" data-hs-overlay="#consentDialog" data-title="마케팅 목적의 개인정보 수집 및 이용" data-template="#tpl_marketing" data-check="#reg_mb_marketing_agree" aria-controls="consentDialog">자세히보기</button>
					</div>
					<input type="hidden" name="mb_marketing_agree_default" value="<?php echo $member['mb_marketing_agree'] ?>">
					<div id="desc_marketing">마케팅 목적의 개인정보 수집·이용에 대한 안내입니다. 자세히보기를 눌러 전문을 확인할 수 있습니다.</div>
					<?php if ($member['mb_marketing_agree'] == 1 && $member['mb_marketing_date'] != "0000-00-00 00:00:00") echo "(동의일자: ".$member['mb_marketing_date'].")"; ?>

					<template id="tpl_marketing">
						* 목적: 서비스 마케팅 및 프로모션<br>
						* 항목: 이름, 이메일<?php echo ($config['cf_use_hp'] || ($config["cf_cert_use"] && ($config['cf_cert_hp'] || $config['cf_cert_simple']))) ? ", 휴대폰 번호" : "";?><br>
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
				
				<div id="desc_promotion">광고성 정보(이메일) 수신 동의의 상위 항목입니다. 자세히보기를 눌러 전문을 확인할 수 있습니다.</div>

				<!-- 하위 채널(이메일) -->
				<ul>
					<li>
						<input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" <?php echo $member['mb_mailling'] ? 'checked' : ''; ?> class="child-promo">
						<label for="reg_mb_mailling"><span></span><b>광고성 이메일 수신 동의</b></label>
						<span>광고성 이메일 수신 동의</span>
						<input type="hidden" name="mb_mailling_default" value="<?php echo $member['mb_mailling']; ?>">
						<?php if ($w == 'u' && $member['mb_mailling'] == 1 && $member['mb_mailling_date'] != "0000-00-00 00:00:00") echo " (동의일자: ".$member['mb_mailling_date'].")"; ?>
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
	    <a href="<?php echo G5_URL ?>">취소</a>
        <?php if ($w == 'u') { ?>
        <span>|</span>
        <a href="<?php echo G5_MEMBER_URL; ?>/member_confirm.php?url=member_leave.php">회원탈퇴</a>
        <?php } ?>
	    <button type="submit" id="btn_submit" accesskey="s"><?php echo $w==''?'회원가입':'정보수정'; ?></button>
	</div>
	</form>


<?php include_once(__DIR__ . '/consent_modal.inc.php'); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var pageTypeParam = "pageType=register";

	<?php if($config['cf_cert_use'] && $config['cf_cert_simple']) { ?>
	var certifyButtons = document.querySelectorAll(".win_sa_cert");
	var simpleCertUrl = "<?php echo G5_INICERT_URL; ?>/ini_request.php";

	certifyButtons.forEach(function(button) {
		button.addEventListener("click", function() {
			if(!cert_confirm()) return;
			var type = this.dataset.type || "";
			var requestUrl = simpleCertUrl + "?directAgency=" + encodeURIComponent(type) + "&" + pageTypeParam;
			call_sa(requestUrl);
		});
	});
    <?php } ?>
    <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
    var hpCertButton = document.getElementById("win_hp_cert");
    if (hpCertButton) {
        hpCertButton.addEventListener("click", function() {
		    if(!cert_confirm()) return;
            var params = "?" + pageTypeParam;
            <?php     
	            switch($config['cf_cert_hp']) {
	                case 'kcp':
	                    $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
	                    $cert_type = 'kcp-hp';
	                    break;
	                default:
	                    echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                    echo 'return;';
                    break;
            }
            ?>
            
            certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>" + params);
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
            alert("비밀번호를 3글자 이상 입력하십시오.");
            f.mb_password.focus();
            return false;
        }
    }

    if (f.mb_password.value != f.mb_password_re.value) {
        alert("비밀번호가 같지 않습니다.");
        f.mb_password_re.focus();
        return false;
    }

    if (f.mb_password.value.length > 0) {
        if (f.mb_password_re.value.length < 3) {
            alert("비밀번호를 3글자 이상 입력하십시오.");
            f.mb_password_re.focus();
            return false;
        }
    }

    // 이름 검사
    if (f.w.value=="") {
        if (f.mb_name.value.length < 1) {
            alert("이름을 입력하십시오.");
            f.mb_name.focus();
            return false;
        }

        /*
        var pattern = /([^가-힣\x20])/i;
        if (pattern.test(f.mb_name.value)) {
            alert("이름은 한글로 입력하십시오.");
            f.mb_name.select();
            return false;
        }
        */
    }

    <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
    // 본인확인 체크
    if(f.cert_no.value=="") {
        alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
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

    <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
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

<!-- } 회원정보 입력/수정 끝 -->
