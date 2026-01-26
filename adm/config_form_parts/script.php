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

        $(".get_theme_confc").on("click", function() {
            var type = $(this).data("type");
            var msg = "기본환경 스킨 설정";
            if (type == "conf_member")
                msg = "기본환경 회원스킨 설정";

            if (!confirm("현재 테마의 " + msg + "을 적용하시겠습니까?"))
                return false;

            $.ajax({
                type: "POST",
                url: "./theme_config_load.php",
                cache: false,
                async: false,
                data: {
                    type: type
                },
                dataType: "json",
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                        return false;
                    }

                    var field = Array('cf_member_skin', 'cf_mobile_member_skin', 'cf_new_skin', 'cf_mobile_new_skin', 'cf_search_skin', 'cf_mobile_search_skin', 'cf_connect_skin', 'cf_mobile_connect_skin', 'cf_faq_skin', 'cf_mobile_faq_skin');
                    var count = field.length;
                    var key;

                    for (i = 0; i < count; i++) {
                        key = field[i];

                        if (data[key] != undefined && data[key] != "")
                            $("select[name=" + key + "]").val(data[key]);
                    }
                }
            });
        });
    });

    // 각 요소의 초기값 저장
    var initialValues = {
        cf_admin: $('#cf_admin').val(),
        cf_analytics: $('#cf_analytics').val(),
        cf_add_meta: $('#cf_add_meta').val(),
        cf_add_script: $('#cf_add_script').val()
    };

    function check_config_captcha_open() {
        var isChanged = false;

        // 현재 값이 있는 경우에만 변경 여부 체크
        if ($('#cf_admin').val()) {
            isChanged = isChanged || $('#cf_admin').val() !== initialValues.cf_admin;
        }
        if ($('#cf_analytics').val()) {
            isChanged = isChanged || $('#cf_analytics').val() !== initialValues.cf_analytics;
        }
        if ($('#cf_add_meta').val()) {
            isChanged = isChanged || $('#cf_add_meta').val() !== initialValues.cf_add_meta;
        }
        if ($('#cf_add_script').val()) {
            isChanged = isChanged || $('#cf_add_script').val() !== initialValues.cf_add_script;
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

        // 방문자분석 스크립트 변경시
        $(document).on('input', '#cf_analytics', check_config_captcha_open);
        
        // 추가 메타태그 변경시
        $(document).on('input', '#cf_add_meta', check_config_captcha_open);
        
        // 추가 script, css 변경시
        $(document).on('input', '#cf_add_script', check_config_captcha_open);
    });
</script>

<?php
// 본인확인 모듈 실행권한 체크
if ($config['cf_cert_use']) {
    // kcb일 때
    if ($config['cf_cert_ipin'] == 'kcb' || $config['cf_cert_hp'] == 'kcb') {
        // 실행모듈
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            if (PHP_INT_MAX == 2147483647) { // 32-bit
                $exe = G5_OKNAME_PATH . '/bin/okname';
            } else {
                $exe = G5_OKNAME_PATH . '/bin/okname_x64';
            }
        } else {
            if (PHP_INT_MAX == 2147483647) { // 32-bit
                $exe = G5_OKNAME_PATH . '/bin/okname.exe';
            } else {
                $exe = G5_OKNAME_PATH . '/bin/oknamex64.exe';
            }
        }

        echo module_exec_check($exe, 'okname');

        if (is_dir(G5_OKNAME_PATH . '/log') && is_writable(G5_OKNAME_PATH . '/log') && function_exists('check_log_folder')) {
            check_log_folder(G5_OKNAME_PATH . '/log');
        }
    }

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
