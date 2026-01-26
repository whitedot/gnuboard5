<script>
$(function(){
    $("#board_copy").click(function(){
        window.open(this.href, "win_board_copy", "left=10,top=10,width=500,height=400");
        return false;
    });

    $(".get_theme_galc").on("click", function() {
        if(!confirm("현재 테마의 게시판 이미지 설정을 적용하시겠습니까?"))
            return false;

        $.ajax({
            type: "POST",
            url: "./theme_config_load.php",
            cache: false,
            async: false,
            data: { type: "board" },
            dataType: "json",
            success: function(data) {
                if(data.error) {
                    alert(data.error);
                    return false;
                }

                var field = Array('bo_gallery_cols', 'bo_gallery_width', 'bo_gallery_height', 'bo_mobile_gallery_width', 'bo_mobile_gallery_height', 'bo_image_width');
                var count = field.length;
                var key;

                for(i=0; i<count; i++) {
                    key = field[i];

                    if(data[key] != undefined && data[key] != "")
                        $("input[name="+key+"]").val(data[key]);
                }
            }
        });
    });
});

function board_copy(bo_table) {
    window.open("./board_copy.php?bo_table="+bo_table, "BoardCopy", "left=10,top=10,width=500,height=200");
}

function set_point(f) {
    if (f.chk_grp_point.checked) {
        f.bo_read_point.value = "<?php echo $config['cf_read_point'] ?>";
        f.bo_write_point.value = "<?php echo $config['cf_write_point'] ?>";
        f.bo_comment_point.value = "<?php echo $config['cf_comment_point'] ?>";
        f.bo_download_point.value = "<?php echo $config['cf_download_point'] ?>";
    } else {
        f.bo_read_point.value     = f.bo_read_point.defaultValue;
        f.bo_write_point.value    = f.bo_write_point.defaultValue;
        f.bo_comment_point.value  = f.bo_comment_point.defaultValue;
        f.bo_download_point.value = f.bo_download_point.defaultValue;
    }
}

var captcha_chk = false;

function use_captcha_check(){
    $.ajax({
        type: "POST",
        url: g5_admin_url+"/ajax.use_captcha.php",
        data: { admin_use_captcha: "1" },
        cache: false,
        async: false,
        dataType: "json",
        success: function(data) {
        }
    });
}

var bo_include_head = jQuery.trim(jQuery("#bo_include_head").val()),
    bo_include_tail = jQuery.trim(jQuery("#bo_include_tail").val());

function frm_check_file(){
    var head = jQuery.trim(jQuery("#bo_include_head").val());
    var tail = jQuery.trim(jQuery("#bo_include_tail").val());

    if(bo_include_head !== head || bo_include_tail !== tail){
        // 캡챠를 사용합니다.
        jQuery("#admin_captcha_box").show();
        captcha_chk = true;

        use_captcha_check();

        return false;
    } else {
        // jQuery("#admin_captcha_box").hide();
    }

    return true;
}

jQuery(function($){
    if( window.self !== window.top ){   // frame 또는 iframe을 사용할 경우 체크
        use_captcha_check();
    }
    
    $("#bo_include_head, #bo_include_tail").on("change paste keyup", function(e) {
        frm_check_file();
    });
});

function fboardform_submit(f)
{
    <?php
    if (!$w) {
        $js_array = get_bo_table_banned_word();
        echo "var banned_array = ". json_encode($js_array) . ";\n";
    }
    ?>

    // 게시판명이 금지된 단어로 되어 있으면
    if( (typeof banned_array != 'undefined') && jQuery.inArray(f.bo_table.value, banned_array) !== -1 ){
        alert("입력한 게시판 TABLE명을 사용할수 없습니다. 다른 이름으로 입력해 주세요.");
        return false;
    }

    <?php echo get_editor_js("bo_content_head"); ?>
    <?php echo get_editor_js("bo_content_tail"); ?>
    <?php echo get_editor_js("bo_mobile_content_head"); ?>
    <?php echo get_editor_js("bo_mobile_content_tail"); ?>

    if (parseInt(f.bo_count_modify.value) < 0) {
        alert("원글 수정 불가 댓글수는 0 이상 입력하셔야 합니다.");
        f.bo_count_modify.focus();
        return false;
    }

    if (parseInt(f.bo_count_delete.value) < 1) {
        alert("원글 삭제 불가 댓글수는 1 이상 입력하셔야 합니다.");
        f.bo_count_delete.focus();
        return false;
    }

    if (frm_check_file() == false) {
        jQuery(window).scrollTop($('#bo_include_tail').offset().top - 30);
    }
    
    if( captcha_chk ) {
        <?php echo isset($captcha_js) ? $captcha_js : ''; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>
    }
    
    return true;
}
</script>
