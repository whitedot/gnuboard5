<script>
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

        candidates = $.grep(candidates, function(url, idx) {
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

            $.ajax({
                type: "POST",
                url: captchaUrl + "/kcaptcha_session.php",
                cache: false,
                async: false,
                complete: function(xhr) {
                    if (!resolved && xhr && xhr.status >= 200 && xhr.status < 400) {
                        resolved = this.url.replace(/\/kcaptcha_session\.php(?:\?.*)?$/, "");
                    }
                }
            });

            if (resolved) {
                break;
            }
        }

        if (!resolved) {
            return;
        }

        $("#captcha_img")
            .css({
                display: "inline-block",
                verticalAlign: "middle",
                marginRight: "8px"
            })
            .attr("src", resolved + "/kcaptcha_image.php?t=" + (new Date()).getTime());
    }

    function fmember_submit(f) {
        if( jQuery("#mb_password").val() ){
            <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>
        }

        return true;
    }

    jQuery(function($){
        var $passwordField = $("#mb_password"),
            passwordFieldUnlocked = false;

        function hideMemberPasswordCaptcha() {
            var $warp = $("#mb_password_captcha_wrap"),
                is_invisible_recaptcha = $("#captcha").hasClass("invisible_recaptcha");

            $warp.hide();
            if (!is_invisible_recaptcha) {
                $("#mp_captcha_tooltip").remove();
            }
        }

        function unlockMemberPasswordField() {
            if (passwordFieldUnlocked || !$passwordField.length) {
                return;
            }

            passwordFieldUnlocked = true;
            $passwordField.prop("readonly", false).removeAttr("readonly");
        }

        function toggleMemberPasswordCaptcha() {
            var $warp = $("#mb_password_captcha_wrap"),
                tooptipid = "mp_captcha_tooltip",
                $span_text = $("<span>", {id:tooptipid, style:"font-size:0.95em;letter-spacing:-0.1em"}).html("비밀번호를 수정할 경우 캡챠를 입력해야 합니다."),
                $parent = $passwordField.parent(),
                is_invisible_recaptcha = $("#captcha").hasClass("invisible_recaptcha"),
                was_hidden = !$warp.is(":visible");

            if ($passwordField.val()) {
                $warp.show();
                if (was_hidden || ($("#captcha_img").attr("src") || "").indexOf("dot.gif") !== -1) {
                    refresh_member_password_captcha();
                }
                if (!is_invisible_recaptcha) {
                    $warp.css("margin-top", "1em");
                    if (!$("#" + tooptipid).length) {
                        $parent.append($span_text);
                    }
                }
            } else {
                hideMemberPasswordCaptcha();
            }
        }

        $("#captcha_key").prop('required', false).removeAttr("required").removeClass("required");

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

        $passwordField.on("focus pointerdown keydown", function() {
            unlockMemberPasswordField();
        });

        $passwordField.on("input keyup change", function() {
            if (!passwordFieldUnlocked && $(this).val()) {
                $(this).val("");
                hideMemberPasswordCaptcha();
                return;
            }

            toggleMemberPasswordCaptcha();
        });

        window.setTimeout(function() {
            if (!passwordFieldUnlocked && $passwordField.val()) {
                $passwordField.val("");
                hideMemberPasswordCaptcha();
            }
        }, 150);
    });
</script>
