<script>
    $(function() {
        <?php
        if (!$config['cf_cert_use']) {
            echo '$(".cf_cert_service").addClass("cf_cert_hide");';
        }
        ?>
        $("#cf_cert_use").change(function() {
            switch ($(this).val()) {
                case "0":
                    $(".cf_cert_service").addClass("cf_cert_hide");
                    break;
                default:
                    $(".cf_cert_service").removeClass("cf_cert_hide");
                    break;
            }
        });

        $("#cf_captcha").on("change", function() {
            if ($(this).val() == 'recaptcha' || $(this).val() == 'recaptcha_inv') {
                $("[class^='kcaptcha_']").hide();
            } else {
                $("[class^='kcaptcha_']").show();
            }
        }).trigger("change");

        if (window.CommonUI && typeof window.CommonUI.initStickyAnchorTabs === "function") {
            window.CommonUI.initStickyAnchorTabs({
                tabBarSelector: "#config_tabs_bar",
                tabNavSelector: "#config_tabs_nav",
                tabLinkSelector: "a.js-config-tab-link[href^='#']",
                topbarSelector: "#hd_top",
                heightVarName: "--config-tabs-height",
                scrollGap: 8,
                scrollDuration: 180,
                namespace: "configTabs"
            });
        }
    });

    // 각 요소의 초기값 저장
    var initialValues = {
        cf_admin: $('#cf_admin').val()
    };

    function check_config_captcha_open() {
        var isChanged = false;

        // 현재 값이 있는 경우에만 변경 여부 체크
        if ($('#cf_admin').val()) {
            isChanged = isChanged || $('#cf_admin').val() !== initialValues.cf_admin;
        }
        var $wrap = $("#config_captcha_wrap"),
            tooptipid = "mp_captcha_tooltip",
            $p_text = $("<p>", {id:tooptipid, style:"font-size:0.95em;letter-spacing:-0.1em"}).html("중요정보를 수정할 경우 캡챠를 입력해야 합니다."),
            $children = $wrap.children(':first'),
            is_invisible_recaptcha = $("#captcha").hasClass("invisible_recaptcha");

        if(isChanged){
            $wrap.show();
            if(! is_invisible_recaptcha) {
                $wrap.css("margin-top","1em");
                if(! $("#"+tooptipid).length){ $children.after($p_text) }
            }
        } else {
            $wrap.hide();
            if($("#"+tooptipid).length && ! is_invisible_recaptcha){ $children.next("#"+tooptipid).remove(); }
        }
        
        return isChanged;
    }
        
    function fconfigform_submit(f) {
        var current_user_ip = "<?php echo $_SERVER['REMOTE_ADDR']; ?>";
        var cf_intercept_ip_val = f.cf_intercept_ip.value;
        
        if (check_config_captcha_open()){
            jQuery("html, body").scrollTop(jQuery("#config_captcha_wrap").offset().top);
            
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
    
    jQuery(function($){
        $("#captcha_key").prop('required', false).removeAttr("required").removeClass("required");
        
        // 최고관리자 변경시
        $(document).on('change', '#cf_admin', check_config_captcha_open);
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

    // LG의 경우 log 디렉토리 체크
    if ($config['cf_cert_hp'] == 'lg') {
        $log_path = G5_LGXPAY_PATH . '/lgdacom/log';

        if (!is_dir($log_path)) {
            if (is_writable(G5_LGXPAY_PATH . '/lgdacom/')) {
                // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
                @mkdir($log_path, G5_DIR_PERMISSION);
                @chmod($log_path, G5_DIR_PERMISSION);
            }

            if (!is_dir($log_path)) {
                echo '<script>' . PHP_EOL;
                echo 'alert("' . str_replace(G5_PATH . '/', '', G5_LGXPAY_PATH) . '/lgdacom 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");' . PHP_EOL;
                echo '</script>' . PHP_EOL;
            }
        }

        if (is_dir($log_path) && is_writable($log_path)) {
            if (function_exists('check_log_folder')) {
                check_log_folder($log_path);
            }
        } elseif (is_dir($log_path)) {
            echo '<script>' . PHP_EOL;
            echo 'alert("' . str_replace(G5_PATH . '/', '', $log_path) . ' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");' . PHP_EOL;
            echo '</script>' . PHP_EOL;
        }
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
