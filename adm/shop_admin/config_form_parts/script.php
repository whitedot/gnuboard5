<script>
function fconfig_check(f)
{
    <?php echo get_editor_js('de_baesong_content'); ?>
    <?php echo get_editor_js('de_change_content'); ?>
    <?php echo get_editor_js('de_guest_privacy'); ?>
    
    var msg = "",
        pg_msg = "";

    if( f.de_pg_service.value == "kcp" ){
        if( f.de_kcp_mid.value && f.de_kcp_site_key.value && parseInt(f.de_card_test.value) > 0 ){
            pg_msg = "NHN KCP";
        }
    } else if ( f.de_pg_service.value == "lg" ) {
        if( f.cf_lg_mid.value && f.cf_lg_mert_key.value && parseInt(f.de_card_test.value) > 0 ){
            pg_msg = "토스페이먼츠(구버전)";
        }
    } else if ( f.de_pg_service.value == "toss" ) {
        if( f.cf_lg_mid.value && f.cf_toss_client_key.value && f.cf_toss_secret_key.value && parseInt(f.de_card_test.value) > 0 ){
            msg += "(주의!) 토스페이먼츠 결제의 결제 설정이 현재 테스트결제로 되어 있습니다.\n상점 API키를 [테스트]키로 설정한 후 테스트결제를 진행해주세요.\n쇼핑몰 운영중이면 반드시 실결제 전환 및 [라이브]키로 설정하여 운영하셔야 합니다.\n실결제로 변경하려면 결제설정 탭 -> 결제 테스트에서 실결제를 선택해 주세요.\n정말로 테스트결제로 설정하시겠습니까?";
        }
    } else if ( f.de_pg_service.value == "inicis" ) {
        if( f.de_inicis_mid.value && f.de_inicis_sign_key.value && parseInt(f.de_card_test.value) > 0 ){
            pg_msg = "KG이니시스";
        }
    } else if ( f.de_pg_service.value == "nicepay" ) {
        if( f.de_nicepay_mid.value && f.de_nicepay_key.value && parseInt(f.de_card_test.value) > 0 ){
            pg_msg = "NICEPAY";
        }
    }

    if( pg_msg ){
        msg += "(주의!) "+pg_msg+" 결제의 결제 설정이 현재 테스트결제 로 되어 있습니다.\n쇼핑몰 운영중이면 반드시 실결제로 설정하여 운영하셔야 합니다.\n실결제로 변경하려면 결제설정 탭 -> 결제 테스트에서 실결제를 선택해 주세요.\n정말로 테스트결제로 설정하시겠습니까?";
    }

    if( msg ){
        if (confirm(msg)){
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

$(function() {

    $(document).ready(function () {
        
        $("#de_global_nhnkcp_naverpay").on("click", function(e){
            if ( $(this).prop('checked') ) {
                $("#de_easy_nhnkcp_naverpay").prop('checked', true);
            }
        });

        function hash_goto_scroll(hash){
            var $elem = hash ? $("#"+hash) : $('#' + window.location.hash.replace('#', ''));
            if($elem.length) {

                var admin_head_height = $("#hd_top").height() + $("#container_title").height() + 30;

                $('html, body').animate({
                    scrollTop: ($elem.offset().top - admin_head_height) + 'px'
                }, 500, 'swing');
            }
        }

        hash_goto_scroll();
        
        $(document).on("click", ".pg_test_conf_link", function(e){
            e.preventDefault();

            var str_hash = this.href.split("#")[1];

            if( str_hash ){
                hash_goto_scroll(str_hash);
            }
        });
    });

    //$(".pg_info_fld").hide();
    $(".pg_vbank_url").hide();
    <?php if($default['de_pg_service']) { ?>
    //$(".<?php echo $default['de_pg_service']; ?>_info_fld").show();
    $("#<?php echo $default['de_pg_service']; ?>_vbank_url").show();
    <?php } else { ?>
    $(".kcp_info_fld").show();
    $("#kcp_vbank_url").show();
    <?php } ?>
    $(document).on("click", ".de_pg_tab a", function(e){

        var pg = $(this).attr("data-value"),
            class_name = "tab-current";

        $("#de_pg_service").val(pg);
        $(this).parent("li").addClass(class_name).siblings().removeClass(class_name);

        //$(".pg_info_fld:visible").hide();
        $(".pg_vbank_url:visible").hide();
        //$("."+pg+"_info_fld").show();
        $("#"+pg+"_vbank_url").show();
        $(".scf_cardtest").addClass("scf_cardtest_hide");
        $("."+pg+"_cardtest").removeClass("scf_cardtest_hide");
        $(".scf_cardtest_tip_adm").addClass("scf_cardtest_tip_adm_hide");
        $("#"+pg+"_cardtest_tip").removeClass("scf_cardtest_tip_adm_hide");
    });

    $("#de_pg_service").on("change", function() {
        var pg = $(this).val();
        $(".pg_info_fld:visible").hide();
        $(".pg_vbank_url:visible").hide();
        $("."+pg+"_info_fld").show();
        $("#"+pg+"_vbank_url").show();
        $(".scf_cardtest").addClass("scf_cardtest_hide");
        $("."+pg+"_cardtest").removeClass("scf_cardtest_hide");
        $(".scf_cardtest_tip_adm").addClass("scf_cardtest_tip_adm_hide");
        $("#"+pg+"_cardtest_tip").removeClass("scf_cardtest_tip_adm_hide");
    });

    $(".scf_cardtest_btn").bind("click", function() {
        var $cf_cardtest_tip = $("#scf_cardtest_tip");
        var $cf_cardtest_btn = $(".scf_cardtest_btn");

        $cf_cardtest_tip.toggle();

        if($cf_cardtest_tip.is(":visible")) {
            $cf_cardtest_btn.text("테스트결제 팁 닫기");
        } else {
            $cf_cardtest_btn.text("테스트결제 팁 더보기");
        }
    });

    $(".get_shop_skin").on("click", function() {
        if(!confirm("현재 테마의 쇼핑몰 스킨 설정을 적용하시겠습니까?"))
            return false;

        $.ajax({
            type: "POST",
            url: "../theme_config_load.php",
            cache: false,
            async: false,
            data: { type: "shop_skin" },
            dataType: "json",
            success: function(data) {
                if(data.error) {
                    alert(data.error);
                    return false;
                }

                var field = Array('de_shop_skin', 'de_shop_mobile_skin');
                var count = field.length;
                var key;

                for(i=0; i<count; i++) {
                    key = field[i];

                    if(data[key] != undefined && data[key] != "")
                        $("select[name="+key+"]").val(data[key]);
                }
            }
        });
    });

    $(".shop_pc_index, .shop_mobile_index, .shop_etc").on("click", function() {
        if(!confirm("현재 테마의 스킨, 이미지 사이즈 등의 설정을 적용하시겠습니까?"))
            return false;

        var type = $(this).attr("class");
        var $el;

        $.ajax({
            type: "POST",
            url: "../theme_config_load.php",
            cache: false,
            async: false,
            data: { type: type },
            dataType: "json",
            success: function(data) {
                if(data.error) {
                    alert(data.error);
                    return false;
                }

                $.each(data, function(key, val) {
                    if(key == "error")
                        return true;

                    $el = $("#"+key);

                    if($el[0].type == "checkbox") {
                        $el.attr("checked", parseInt(val) ? true : false);
                        return true;
                    }
                    $el.val(val);
                });
            }
        });
    });

    $(document).on("change", "#de_taxsave_use", function(e){
        var $val = $(this).val();
        
        if( parseInt($val) > 0 ){
            $("#de_taxsave_types").show();
        } else {
            $("#de_taxsave_types").hide();
        }
    });
    
    // 현금영수증 발급수단 중 무통장입금은 무조건 체크처리
    document.getElementById("de_taxsave_types_account").checked = true;
    document.getElementById("de_taxsave_types_account").disabled = true;
});
</script>

<?php
// 결제모듈 실행권한 체크
if($default['de_iche_use'] || $default['de_vbank_use'] || $default['de_hp_use'] || $default['de_card_use']) {
    // kcp의 경우 pp_cli 체크
    if($default['de_pg_service'] == 'kcp') {
        if(!extension_loaded('openssl')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("PHP openssl 확장모듈이 설치되어 있지 않습니다.\n모바일 쇼핑몰 결제 때 사용되오니 openssl 확장 모듈을 설치하여 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        if(!extension_loaded('soap') || !class_exists('SOAPClient')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("PHP SOAP 확장모듈이 설치되어 있지 않습니다.\n모바일 쇼핑몰 결제 때 사용되오니 SOAP 확장 모듈을 설치하여 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        $is_linux = true;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            $is_linux = false;

        $exe = '/kcp/bin/';
        if($is_linux) {
            if(PHP_INT_MAX == 2147483647) // 32-bit
                $exe .= 'pp_cli';
            else
                $exe .= 'pp_cli_x64';
        } else {
            $exe .= 'pp_cli_exe.exe';
        }

        echo module_exec_check(G5_SHOP_PATH.$exe, 'pp_cli');

        // shop/kcp/log 디렉토리 체크 후 있으면 경고
        if(is_dir(G5_SHOP_PATH.'/kcp/log') && is_writable(G5_SHOP_PATH.'/kcp/log')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("웹접근 가능 경로에 log 디렉토리가 있습니다.\nlog 디렉토리를 웹에서 접근 불가능한 경로로 변경해 주십시오.\n\nlog 디렉토리 경로 변경은 SIR FAQ를 참고해 주세요.")'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }
    }

    // LG의 경우 log 디렉토리 체크
    if($default['de_pg_service'] == 'lg') {
        $log_path = G5_LGXPAY_PATH.'/lgdacom/log';

        try {
            if( ! is_dir($log_path) && is_writable(G5_LGXPAY_PATH.'/lgdacom/') ){
                @mkdir($log_path, G5_DIR_PERMISSION);
                @chmod($log_path, G5_DIR_PERMISSION);
            }
        } catch(Exception $e) {
        }

        if(!is_dir($log_path)) {

            if( is_writable(G5_LGXPAY_PATH.'/lgdacom/') ){
                // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
                @mkdir($log_path, G5_DIR_PERMISSION);
                @chmod($log_path, G5_DIR_PERMISSION);
            }

            if(!is_dir($log_path)){
                echo '<script>'.PHP_EOL;
                echo 'alert("'.str_replace(G5_PATH.'/', '', G5_LGXPAY_PATH).'/lgdacom 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");'.PHP_EOL;
                echo '</script>'.PHP_EOL;
            }
        }

        if(is_writable($log_path)) {
            if( function_exists('check_log_folder') ){
                check_log_folder($log_path);
            }
        } else {
            echo '<script>'.PHP_EOL;
            echo 'alert("'.str_replace(G5_PATH.'/', '',$log_path).' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }
    }

    // 이니시스의 경우 log 디렉토리 체크
    if($default['de_pg_service'] == 'inicis') {
        if (!function_exists('xml_set_element_handler')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("XML 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        if (!function_exists('openssl_get_publickey')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("OPENSSL 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        if (!function_exists('socket_create')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("SOCKET 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        $log_path = G5_SHOP_PATH.'/inicis/log';
        
        try {
            if( ! is_dir($log_path) && is_writable(G5_SHOP_PATH.'/inicis/') ){
                @mkdir($log_path, G5_DIR_PERMISSION);
                @chmod($log_path, G5_DIR_PERMISSION);
            }
        } catch(Exception $e) {
        }

        if( function_exists('check_log_folder') && is_writable($log_path) ){
            check_log_folder($log_path);
        }
    }

    // 카카오페이의 경우 log 디렉토리 체크
    if($default['de_kakaopay_mid'] && $default['de_kakaopay_key'] && $default['de_kakaopay_enckey'] && $default['de_kakaopay_hashkey'] && $default['de_kakaopay_cancelpwd']) {
        $log_path = G5_SHOP_PATH.'/kakaopay/log';

        if(!is_dir($log_path)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("'.str_replace(G5_PATH.'/', '', G5_SHOP_PATH).'/kakaopay 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        } else {
            if(!is_writable($log_path)) {
                echo '<script>'.PHP_EOL;
                echo 'alert("'.str_replace(G5_PATH.'/', '',$log_path).' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");'.PHP_EOL;
                echo '</script>'.PHP_EOL;
            } else {
                if( function_exists('check_log_folder') && is_writable($log_path) ){
                    check_log_folder($log_path);
                }
            }
        }
    }
}
?>
