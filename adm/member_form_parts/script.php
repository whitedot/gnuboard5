<script>
    function postSync(url) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, false);
        xhr.send(null);

        return {
            ok: xhr.status >= 200 && xhr.status < 400,
            status: xhr.status,
            url: url
        };
    }

    function refresh_member_password_captcha() {
        var candidates = [],
            captchaUrl = window.g5_captcha_url || "",
            adminBase = (window.g5_admin_url || "").replace(/\/+$/, "").replace(/\/adm$/, ""),
            pathBase = window.location.origin + window.location.pathname.replace(/\/adm\/.*$/, ""),
            i = 0,
            resolved = "";

        if (captchaUrl) {
            candidates.push(captchaUrl);
        }
        if (window.g5_url) {
            candidates.push(window.g5_url.replace(/\/+$/, "") + "/plugin/kcaptcha");
        }
        if (adminBase) {
            candidates.push(adminBase + "/plugin/kcaptcha");
        }
        if (pathBase && /\/g5aif$/i.test(pathBase)) {
            candidates.push(pathBase + "/plugin/kcaptcha");
        }

        candidates = candidates.filter(function(url, idx) {
            return candidates.indexOf(url) === idx;
        });

        if (!candidates.length) {
            return;
        }

        for (i = 0; i < candidates.length; i++) {
            captchaUrl = candidates[i];
            if (/^https:/i.test(window.location.protocol) && /^http:\/\//i.test(captchaUrl)) {
                captchaUrl = captchaUrl.replace(/^http:/i, "https:");
            }

            try {
                if (new URL(captchaUrl, window.location.href).origin !== window.location.origin) {
                    continue;
                }
            } catch (e) {
                continue;
            }

            var response = postSync(captchaUrl + "/kcaptcha_session.php");
            if (!resolved && response.ok) {
                resolved = response.url.replace(/\/kcaptcha_session\.php(?:\?.*)?$/, "");
            }

            if (resolved) {
                break;
            }
        }

        if (!resolved) {
            return;
        }

        var captchaImage = document.getElementById("captcha_img");
        if (!captchaImage) {
            return;
        }

        captchaImage.style.display = "inline-block";
        captchaImage.style.verticalAlign = "middle";
        captchaImage.style.marginRight = "8px";
        captchaImage.src = resolved + "/kcaptcha_image.php?t=" + (new Date()).getTime();
    }

    function fmember_submit(f) {
        var passwordField = document.getElementById("mb_password");
        if (passwordField && passwordField.value) {
            <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>
        }

        return true;
    }

    document.addEventListener("DOMContentLoaded", function() {
        var passwordField = document.getElementById("mb_password");
        var passwordFieldUnlocked = false;

        function hideMemberPasswordCaptcha() {
            var wrap = document.getElementById("mb_password_captcha_wrap");
            var captcha = document.getElementById("captcha");
            var is_invisible_recaptcha = captcha ? captcha.classList.contains("invisible_recaptcha") : false;

            if (wrap) {
                wrap.style.display = "none";
            }
            if (!is_invisible_recaptcha) {
                var tooltip = document.getElementById("mp_captcha_tooltip");
                if (tooltip) {
                    tooltip.remove();
                }
            }
        }

        function unlockMemberPasswordField() {
            if (passwordFieldUnlocked || !passwordField) {
                return;
            }

            passwordFieldUnlocked = true;
            passwordField.readOnly = false;
            passwordField.removeAttribute("readonly");
        }

        function toggleMemberPasswordCaptcha() {
            var wrap = document.getElementById("mb_password_captcha_wrap");
            var captcha = document.getElementById("captcha");
            var parent = passwordField ? passwordField.parentElement : null;
            var tooltipId = "mp_captcha_tooltip";
            var captchaImage = document.getElementById("captcha_img");
            var is_invisible_recaptcha = captcha ? captcha.classList.contains("invisible_recaptcha") : false;
            var was_hidden = !wrap || wrap.style.display === "none" || window.getComputedStyle(wrap).display === "none";

            if (!wrap || !passwordField) {
                return;
            }

            if (passwordField.value) {
                wrap.style.display = "";
                if (was_hidden || ((captchaImage && captchaImage.getAttribute("src")) || "").indexOf("dot.gif") !== -1) {
                    refresh_member_password_captcha();
                }
                if (!is_invisible_recaptcha) {
                    wrap.style.marginTop = "1em";
                    if (!document.getElementById(tooltipId) && parent) {
                        var tooltip = document.createElement("span");
                        tooltip.id = tooltipId;
                        tooltip.style.fontSize = "0.95em";
                        tooltip.style.letterSpacing = "-0.1em";
                        tooltip.innerHTML = "비밀번호를 수정할 경우 캡챠를 입력해야 합니다.";
                        parent.appendChild(tooltip);
                    }
                }
            } else {
                hideMemberPasswordCaptcha();
            }
        }

        var captchaKey = document.getElementById("captcha_key");
        if (captchaKey) {
            captchaKey.required = false;
            captchaKey.removeAttribute("required");
            captchaKey.classList.remove("required");
        }

        if (window.CommonUI && typeof window.CommonUI.initStickyAnchorTabs === "function") {
            window.CommonUI.initStickyAnchorTabs({
                tabBarSelector: "#member_tabs_nav",
                tabNavSelector: "#member_tabs_nav",
                tabLinkSelector: "a.js-member-tab-link[href^='#']",
                topbarSelector: "#hd_top",
                heightVarName: "--config-tabs-height",
                scrollGap: 8,
                scrollDuration: 180,
                namespace: "memberTabs"
            });
        }

        hideMemberPasswordCaptcha();

        if (!passwordField) {
            return;
        }

        ["focus", "pointerdown", "keydown"].forEach(function(eventName) {
            passwordField.addEventListener(eventName, function() {
                unlockMemberPasswordField();
            });
        });

        ["input", "keyup", "change"].forEach(function(eventName) {
            passwordField.addEventListener(eventName, function() {
                if (!passwordFieldUnlocked && passwordField.value) {
                    passwordField.value = "";
                    hideMemberPasswordCaptcha();
                    return;
                }

                toggleMemberPasswordCaptcha();
            });
        });

        window.setTimeout(function() {
            if (!passwordFieldUnlocked && passwordField.value) {
                passwordField.value = "";
                hideMemberPasswordCaptcha();
            }
        }, 150);
    });
</script>
