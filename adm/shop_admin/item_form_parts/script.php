<script>
var f = document.fitemform;

<?php if ($w == 'u') { ?>
$(".banner_or_img").addClass("sit_wimg");
$(function() {
    $(".sit_wimg_view").bind("click", function() {
        var sit_wimg_id = $(this).attr("id").split("_");
        var $img_display = $("#"+sit_wimg_id[1]);

        $img_display.toggle();

        if($img_display.is(":visible")) {
            $(this).text($(this).text().replace("확인", "닫기"));
        } else {
            $(this).text($(this).text().replace("닫기", "확인"));
        }

        var $img = $("#"+sit_wimg_id[1]).children("img");
        var width = $img.width();
        var height = $img.height();
        if(width > 700) {
            var img_width = 700;
            var img_height = Math.round((img_width * height) / width);

            $img.width(img_width).height(img_height);
        }
    });
    $(".sit_wimg_close").bind("click", function() {
        var $img_display = $(this).parents(".banner_or_img");
        var id = $img_display.attr("id");
        $img_display.toggle();
        var $button = $("#it_"+id+"_view");
        $button.text($button.text().replace("닫기", "확인"));
    });
});
<?php } ?>

function fitemformcheck(f)
{
    if (!f.ca_id.value) {
        alert("기본분류를 선택하십시오.");
        f.ca_id.focus();
        return false;
    }

    if (f.w.value == "") {
        var error = "";
        $.ajax({
            url: "./ajax.it_id.php",
            type: "POST",
            data: {
                "it_id": f.it_id.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                error = data.error;
            }
        });

        if (error) {
            alert(error);
            return false;
        }
    }

    if(f.it_point_type.value == "1" || f.it_point_type.value == "2") {
        var point = parseInt(f.it_point.value);
        if(point < 0 || point > 99) {
            alert("포인트 비율을 0과 99 사이의 값으로 입력해 주십시오.");
            f.it_point.focus();
            f.it_point.select();
            return false;
        }
    }

    if(parseInt(f.it_sc_type.value) > 1) {
        if(!f.it_sc_price.value || f.it_sc_price.value == "0") {
            alert("기본배송비를 입력해 주십시오.");
            return false;
        }

        if(f.it_sc_type.value == "2" && (!f.it_sc_minimum.value || f.it_sc_minimum.value == "0")) {
            alert("배송비 상세조건의 주문금액을 입력해 주십시오.");
            return false;
        }

        if(f.it_sc_type.value == "4" && (!f.it_sc_qty.value || f.it_sc_qty.value == "0")) {
            alert("배송비 상세조건의 주문수량을 입력해 주십시오.");
            return false;
        }
    }

    // 관련상품처리
    var item = new Array();
    var re_item = it_id = "";

    $("#reg_relation input[name='re_it_id[]']").each(function() {
        it_id = $(this).val();
        if(it_id == "")
            return true;

        item.push(it_id);
    });

    if(item.length > 0)
        re_item = item.join();

    $("input[name=it_list]").val(re_item);

    // 이벤트처리
    var evnt = new Array();
    var ev = ev_id = "";

    $("#reg_event_list input[name='ev_id[]']").each(function() {
        ev_id = $(this).val();
        if(ev_id == "")
            return true;

        evnt.push(ev_id);
    });

    if(evnt.length > 0)
        ev = evnt.join();

    $("input[name=ev_list]").val(ev);

    <?php echo get_editor_js('it_explan'); ?>
    <?php echo get_editor_js('it_mobile_explan'); ?>
    <?php echo get_editor_js('it_head_html'); ?>
    <?php echo get_editor_js('it_tail_html'); ?>
    <?php echo get_editor_js('it_mobile_head_html'); ?>
    <?php echo get_editor_js('it_mobile_tail_html'); ?>

    return true;
}

function categorychange(f)
{
    var idx = f.ca_id.value;

    if (f.w.value == "" && idx)
    {
        f.it_use.checked = ca_use[idx] ? true : false;
        f.it_stock_qty.value = ca_stock_qty[idx];
        f.it_sell_email.value = ca_sell_email[idx];
    }
}

categorychange(document.fitemform);
</script>
