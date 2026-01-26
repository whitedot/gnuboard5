<script>
    function fmember_submit(f) {
        if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
            alert('아이콘은 이미지 파일만 가능합니다.');
            return false;
        }

        if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
            alert('회원이미지는 이미지 파일만 가능합니다.');
            return false;
        }

        if( jQuery("#mb_password").val() ){
            <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>
        }

        return true;
    }

    jQuery(function($){
        $("#captcha_key").prop('required', false).removeAttr("required").removeClass("required");

        $("#mb_password").on("keyup", function(e) {
            var $warp = $("#mb_password_captcha_wrap"),
                tooptipid = "mp_captcha_tooltip",
                $span_text = $("<span>", {id:tooptipid, style:"font-size:0.95em;letter-spacing:-0.1em"}).html("비밀번호를 수정할 경우 캡챠를 입력해야 합니다."),
                $parent = $(this).parent(),
                is_invisible_recaptcha = $("#captcha").hasClass("invisible_recaptcha");

            if($(this).val()){
                $warp.show();
                if(! is_invisible_recaptcha) {
                    $warp.css("margin-top","1em");
                    if(! $("#"+tooptipid).length){ $parent.append($span_text) }
                }
            } else {
                $warp.hide();
                if($("#"+tooptipid).length && ! is_invisible_recaptcha){ $parent.find("#"+tooptipid).remove(); }
            }
        });
    });
</script>
