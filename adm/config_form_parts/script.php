<script>
    var initialValues = {
        cf_admin: ""
    };

    function toggleConfigCaptchaFields(isRecaptcha) {
        document.querySelectorAll("[class^='kcaptcha_']").forEach(function(element) {
            element.style.display = isRecaptcha ? "none" : "";
        });
    }

    function check_config_captcha_open() {
        var isChanged = false;
        var adminField = document.getElementById("cf_admin");
        var wrap = document.getElementById("config_captcha_wrap");
        var captcha = document.getElementById("captcha");
        var tooptipid = "mp_captcha_tooltip";
        var children = wrap ? wrap.firstElementChild : null;
        var is_invisible_recaptcha = captcha ? captcha.classList.contains("invisible_recaptcha") : false;

        // 현재 값이 있는 경우에만 변경 여부 체크
        if (adminField && adminField.value) {
            isChanged = isChanged || adminField.value !== initialValues.cf_admin;
        }

        if (!wrap) {
            return isChanged;
        }

        if(isChanged) {
            wrap.style.display = "";
            if(! is_invisible_recaptcha) {
                wrap.style.marginTop = "1em";
                if (!document.getElementById(tooptipid)) {
                    var tooltip = document.createElement("p");
                    tooltip.id = tooptipid;
                    tooltip.style.fontSize = "0.95em";
                    tooltip.style.letterSpacing = "-0.1em";
                    tooltip.innerHTML = "중요정보를 수정할 경우 캡챠를 입력해야 합니다.";
                    if (children) {
                        children.insertAdjacentElement("afterend", tooltip);
                    } else {
                        wrap.appendChild(tooltip);
                    }
                }
            }
        } else {
            wrap.style.display = "none";
            var existingTooltip = document.getElementById(tooptipid);
            if(existingTooltip && !is_invisible_recaptcha) {
                existingTooltip.remove();
            }
        }
        
        return isChanged;
    }
        
    function fconfigform_submit(f) {
        var current_user_ip = "<?php echo $_SERVER['REMOTE_ADDR']; ?>";
        var cf_intercept_ip_val = f.cf_intercept_ip.value;
        
        if (check_config_captcha_open()){
            var captchaWrap = document.getElementById("config_captcha_wrap");
            if (captchaWrap) {
                window.scrollTo(0, captchaWrap.getBoundingClientRect().top + window.pageYOffset);
            }
            
            <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>
        }
        
        if (cf_intercept_ip_val && current_user_ip) {
            var cf_intercept_ips = cf_intercept_ip_val.split("\n");

            for (var i = 0; i < cf_intercept_ips.length; i++) {
                if (cf_intercept_ips[i].trim()) {
                    cf_intercept_ips[i] = cf_intercept_ips[i].replace(".", "\.");
                    cf_intercept_ips[i] = cf_intercept_ips[i].replace("+", "[0-9\.]+");

                    var re = new RegExp(cf_intercept_ips[i]);
                    if (re.test(current_user_ip)) {
                        alert("현재 접속 IP : " + current_user_ip + " 가 차단될수 있기 때문에, 다른 IP를 입력해 주세요.");
                        return false;
                    }
                }
            }
        }

        f.action = "./config_form_update.php";
        return true;
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        var certUseField = document.getElementById("cf_cert_use");
        var captchaField = document.getElementById("cf_captcha");
        var adminField = document.getElementById("cf_admin");
        var captchaKey = document.getElementById("captcha_key");

        <?php
        if (!$config['cf_cert_use']) {
            echo 'document.querySelectorAll(".cf_cert_service").forEach(function(element) { element.classList.add("cf_cert_hide"); });';
        }
        ?>

        if (adminField) {
            initialValues.cf_admin = adminField.value;
            adminField.addEventListener("change", check_config_captcha_open);
        }

        if (certUseField) {
            certUseField.addEventListener("change", function() {
                var hideServices = this.value === "0";
                document.querySelectorAll(".cf_cert_service").forEach(function(element) {
                    element.classList.toggle("cf_cert_hide", hideServices);
                });
            });
        }

        if (captchaField) {
            var syncCaptchaFields = function() {
                var isRecaptcha = captchaField.value === "recaptcha" || captchaField.value === "recaptcha_inv";
                toggleConfigCaptchaFields(isRecaptcha);
            };

            captchaField.addEventListener("change", syncCaptchaFields);
            syncCaptchaFields();
        }

        if (captchaKey) {
            captchaKey.required = false;
            captchaKey.removeAttribute("required");
            captchaKey.classList.remove("required");
        }

        if (window.CommonUI && typeof window.CommonUI.initStickyAnchorTabs === "function") {
            window.CommonUI.initStickyAnchorTabs({
                tabBarSelector: "#config_tabs_nav",
                tabNavSelector: "#config_tabs_nav",
                tabLinkSelector: "a.js-config-tab-link[href^='#']",
                topbarSelector: "#hd_top",
                heightVarName: "--config-tabs-height",
                scrollGap: 8,
                scrollDuration: 180,
                namespace: "configTabs"
            });
        }

        check_config_captcha_open();
    });
</script>

<?php
// 본인확인 모듈 실행권한 체크
if ($config['cf_cert_use']) {
    // kcp일 때
	    if ($config['cf_cert_hp'] == 'kcp') {
        
        $bin_path = ((int)$config['cf_cert_use'] === 2 && !$config['cf_cert_kcp_enckey']) ? 'bin_old' : 'bin';
        
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            if (PHP_INT_MAX == 2147483647) { // 32-bit
                $exe = G5_KCPCERT_PATH . '/'.$bin_path.'/ct_cli';
            } else {
                $exe = G5_KCPCERT_PATH . '/'.$bin_path.'/ct_cli_x64';
            }
        } else {
            $exe = G5_KCPCERT_PATH . '/'.$bin_path.'/ct_cli_exe.exe';
        }
        
	        echo module_exec_check($exe, 'ct_cli');
	    }
	}

if (stripos($config['cf_image_extension'], "webp") !== false) {
    if (!function_exists("imagewebp")) {
        echo '<script>' . PHP_EOL;
        echo 'alert("이 서버는 webp 이미지를 지원하고 있지 않습니다.\n이미지 업로드 확장자에서 webp 확장자를 제거해 주십시오.\n제거하지 않으면 이미지와 관련된 오류가 발생할 수 있습니다.");' . PHP_EOL;
        echo 'document.getElementById("cf_image_extension").focus();' . PHP_EOL;
        echo '</script>' . PHP_EOL;
    }
}
?>
