<section id="anc_scf_etc">
    <h2 class="h2_frm">기타 설정</h2>
    <?php echo $pg_anchor; ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>기타 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">관련상품출력</th>
            <td>
                <?php echo help("관련상품의 경우 등록된 상품은 모두 출력하므로 '출력할 줄 수'는 설정하지 않습니다. 이미지높이를 0으로 설정하면 상품이미지를 이미지폭에 비례하여 생성합니다."); ?>
                <label for="de_rel_list_skin">스킨</label>
                <select name="de_rel_list_skin" id="de_rel_list_skin">
                    <?php echo get_list_skin_options("^relation.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_rel_list_skin']); ?>
                </select>
                <label for="de_rel_img_width">이미지폭</label>
                <input type="text" name="de_rel_img_width" value="<?php echo get_sanitize_input($default['de_rel_img_width']); ?>" id="de_rel_img_width" class="frm_input" size="3">
                <label for="de_rel_img_height">이미지높이</label>
                <input type="text" name="de_rel_img_height" value="<?php echo get_sanitize_input($default['de_rel_img_height']); ?>" id="de_rel_img_height" class="frm_input" size="3">
                <label for="de_rel_list_mod">1줄당 이미지 수</label>
                <input type="text" name="de_rel_list_mod" value="<?php echo get_sanitize_input($default['de_rel_list_mod']); ?>" id="de_rel_list_mod" class="frm_input" size="3">
                <label for="de_rel_list_use">출력</label>
                <input type="checkbox" name="de_rel_list_use" value="1" id="de_rel_list_use" <?php echo $default['de_rel_list_use']?"checked":""; ?>>
            </td>
        </tr>
        <tr>
            <th scope="row">검색상품출력</th>
            <td>
                <label for="de_search_list_skin">스킨</label>
                <select name="de_search_list_skin" id="de_search_list_skin">
                    <?php echo get_list_skin_options("^list.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_search_list_skin']); ?>
                </select>
                <label for="de_search_img_width">이미지폭</label>
                <input type="text" name="de_search_img_width" value="<?php echo get_sanitize_input($default['de_search_img_width']); ?>" id="de_search_img_width" class="frm_input" size="3">
                <label for="de_search_img_height">이미지높이</label>
                <input type="text" name="de_search_img_height" value="<?php echo get_sanitize_input($default['de_search_img_height']); ?>" id="de_search_img_height" class="frm_input" size="3">
                <label for="de_search_list_mod">1줄당 이미지 수</label>
                <input type="text" name="de_search_list_mod" value="<?php echo get_sanitize_input($default['de_search_list_mod']); ?>" id="de_search_list_mod" class="frm_input" size="3">
                <label for="de_search_list_row">출력할 줄 수</label>
                <input type="text" name="de_search_list_row" value="<?php echo get_sanitize_input($default['de_search_list_row']); ?>" id="de_search_list_row" class="frm_input" size="3">
            </td>
        </tr>
        <tr>
            <th scope="row">유형별 상품리스트</th>
            <td>
                <label for="de_listtype_list_skin">스킨</label>
                <select name="de_listtype_list_skin" id="de_listtype_list_skin">
                    <?php echo get_list_skin_options("^list.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_listtype_list_skin']); ?>
                </select>
                <label for="de_listtype_img_width">이미지폭</label>
                <input type="text" name="de_listtype_img_width" value="<?php echo get_sanitize_input($default['de_listtype_img_width']); ?>" id="de_listtype_img_width" class="frm_input" size="3">
                <label for="de_listtype_img_height">이미지높이</label>
                <input type="text" name="de_listtype_img_height" value="<?php echo get_sanitize_input($default['de_listtype_img_height']); ?>" id="de_listtype_img_height" class="frm_input" size="3">
                <label for="de_listtype_list_mod">1줄당 이미지 수</label>
                <input type="text" name="de_listtype_list_mod" value="<?php echo get_sanitize_input($default['de_listtype_list_mod']); ?>" id="de_listtype_list_mod" class="frm_input" size="3">
                <label for="de_listtype_list_row">출력할 줄 수</label>
                <input type="text" name="de_listtype_list_row" value="<?php echo get_sanitize_input($default['de_listtype_list_row']); ?>" id="de_listtype_list_row" class="frm_input" size="3">
            </td>
        </tr>
        <tr>
            <th scope="row">이미지(소)</th>
            <td>
                <?php echo help("분류리스트에서 보여지는 사이즈를 설정하시면 됩니다. 분류관리의 출력 이미지폭, 높이의 기본값으로 사용됩니다. 높이를 0 으로 설정하시면 폭에 비례하여 높이를 썸네일로 생성합니다."); ?>
                <label for="de_simg_width"><span class="sr-only">이미지(소) </span>폭</label>
                <input type="text" name="de_simg_width" value="<?php echo get_sanitize_input($default['de_simg_width']); ?>" id="de_simg_width" class="frm_input" size="5"> 픽셀
                /
                <label for="de_simg_height"><span class="sr-only">이미지(소) </span>높이</label>
                <input type="text" name="de_simg_height" value="<?php echo get_sanitize_input($default['de_simg_height']); ?>" id="de_simg_height" class="frm_input" size="5"> 픽셀
            </td>
        </tr>
        <tr>
            <th scope="row">이미지(중)</th>
            <td>
                <?php echo help("상품상세보기에서 보여지는 상품이미지의 사이즈를 픽셀로 설정합니다. 높이를 0 으로 설정하시면 폭에 비례하여 높이를 썸네일로 생성합니다."); ?>
                <label for="de_mimg_width"><span class="sr-only">이미지(중) </span>폭</label>
                <input type="text" name="de_mimg_width" value="<?php echo get_sanitize_input($default['de_mimg_width']); ?>" id="de_mimg_width" class="frm_input" size="5"> 픽셀
                /
                <label for="de_mimg_height"><span class="sr-only">이미지(중) </span>높이</label>
                <input type="text" name="de_mimg_height" value="<?php echo get_sanitize_input($default['de_mimg_height']); ?>" id="de_mimg_height" class="frm_input" size="5"> 픽셀
            </td>
        </tr>
        <tr>
            <th scope="row">상단로고이미지</th>
            <td>
                <?php echo help("쇼핑몰 상단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
                <input type="file" name="logo_img" id="logo_img">
                <?php
                $logo_img = G5_DATA_PATH."/common/logo_img";
                if (file_exists($logo_img))
                {
                    $size = getimagesize($logo_img);
                ?>
                <input type="checkbox" name="logo_img_del" value="1" id="logo_img_del">
                <label for="logo_img_del"><span class="sr-only">상단로고이미지</span> 삭제</label>
                <span class="scf_img_logoimg"></span>
                <div id="logoimg" class="banner_or_img">
                    <img src="<?php echo G5_DATA_URL; ?>/common/logo_img" alt="">
                    <button type="button" class="sit_wimg_close">닫기</button>
                </div>
                <script>
                $('<button type="button" id="cf_logoimg_view" class="btn_frmline scf_img_view">상단로고이미지 확인</button>').appendTo('.scf_img_logoimg');
                </script>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">하단로고이미지</th>
            <td>
                <?php echo help("쇼핑몰 하단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
                <input type="file" name="logo_img2" id="logo_img2">
                <?php
                $logo_img2 = G5_DATA_PATH."/common/logo_img2";
                if (file_exists($logo_img2))
                {
                    $size = getimagesize($logo_img2);
                ?>
                <input type="checkbox" name="logo_img_del2" value="1" id="logo_img_del2">
                <label for="logo_img_del2"><span class="sr-only">하단로고이미지</span> 삭제</label>
                <span class="scf_img_logoimg2"></span>
                <div id="logoimg2" class="banner_or_img">
                    <img src="<?php echo G5_DATA_URL; ?>/common/logo_img2" alt="">
                    <button type="button" class="sit_wimg_close">닫기</button>
                </div>
                <script>
                $('<button type="button" id="cf_logoimg2_view" class="btn_frmline scf_img_view">하단로고이미지 확인</button>').appendTo('.scf_img_logoimg2');
                </script>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">모바일 상단로고이미지</th>
            <td>
                <?php echo help("모바일 쇼핑몰 상단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
                <input type="file" name="mobile_logo_img" id="mobile_logo_img">
                <?php
                $mobile_logo_img = G5_DATA_PATH."/common/mobile_logo_img";
                if (file_exists($mobile_logo_img))
                {
                    $size = getimagesize($mobile_logo_img);
                ?>
                <input type="checkbox" name="mobile_logo_img_del" value="1" id="mobile_logo_img_del">
                <label for="mobile_logo_img_del"><span class="sr-only">모바일 상단로고이미지</span> 삭제</label>
                <span class="scf_img_mobilelogoimg"></span>
                <div id="mobilelogoimg" class="banner_or_img">
                    <img src="<?php echo G5_DATA_URL; ?>/common/mobile_logo_img" alt="">
                    <button type="button" class="sit_wimg_close">닫기</button>
                </div>
                <script>
                $('<button type="button" id="cf_mobilelogoimg_view" class="btn_frmline scf_img_view">모바일 상단로고이미지 확인</button>').appendTo('.scf_img_mobilelogoimg');
                </script>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">모바일 하단로고이미지</th>
            <td>
                <?php echo help("모바일 쇼핑몰 하단로고를 직접 올릴 수 있습니다. 이미지 파일만 가능합니다."); ?>
                <input type="file" name="mobile_logo_img2" id="mobile_logo_img2">
                <?php
                $mobile_logo_img2 = G5_DATA_PATH."/common/mobile_logo_img2";
                if (file_exists($mobile_logo_img2))
                {
                    $size = getimagesize($mobile_logo_img2);
                ?>
                <input type="checkbox" name="mobile_logo_img_del2" value="1" id="mobile_logo_img_del2">
                <label for="mobile_logo_img_del2"><span class="sr-only">모바일 하단로고이미지</span> 삭제</label>
                <span class="scf_img_mobilelogoimg2"></span>
                <div id="mobilelogoimg2" class="banner_or_img">
                    <img src="<?php echo G5_DATA_URL; ?>/common/mobile_logo_img2" alt="">
                    <button type="button" class="sit_wimg_close">닫기</button>
                </div>
                <script>
                $('<button type="button" id="cf_mobilelogoimg2_view" class="btn_frmline scf_img_view">모바일 하단로고이미지 확인</button>').appendTo('.scf_img_mobilelogoimg2');
                </script>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_item_use_write">사용후기 작성</label></th>
            <td>
                 <?php echo help("주문상태에 따른 사용후기 작성여부를 설정합니다.", 50); ?>
                <select name="de_item_use_write" id="de_item_use_write">
                    <option value="0" <?php echo get_selected($default['de_item_use_write'], 0); ?>>주문상태와 무관하게 작성가능</option>
                    <option value="1" <?php echo get_selected($default['de_item_use_write'], 1); ?>>주문상태가 완료인 경우에만 작성가능</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_item_use_use">사용후기</label></th>
            <td>
                 <?php echo help("사용후기가 올라오면, 즉시 출력 혹은 관리자 승인 후 출력 여부를 설정합니다.", 50); ?>
                <select name="de_item_use_use" id="de_item_use_use">
                    <option value="0" <?php echo get_selected($default['de_item_use_use'], 0); ?>>즉시 출력</option>
                    <option value="1" <?php echo get_selected($default['de_item_use_use'], 1); ?>>관리자 승인 후 출력</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_level_sell">상품구입 권한</label></th>
            <td>
                <?php echo help("권한을 1로 설정하면 누구나 구입할 수 있습니다. 특정회원만 구입할 수 있도록 하려면 해당 권한으로 설정하십시오."); ?>
                <?php echo get_member_level_select('de_level_sell', 1, 10, $default['de_level_sell']); ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_cart_keep_term">장바구니 보관기간</label></th>
            <td>
                 <?php echo help("장바구니 상품의 보관 기간을 설정하십시오."); ?>
                <input type="text" name="de_cart_keep_term" value="<?php echo get_sanitize_input($default['de_cart_keep_term']); ?>" id="de_cart_keep_term" class="frm_input" size="5"> 일
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_guest_cart_use">비회원 장바구니</label></th>
            <td>
                 <?php echo help("비회원 장바구니 기능을 사용하려면 체크하십시오."); ?>
                <input type="checkbox" name="de_guest_cart_use" value="1" id="de_guest_cart_use"<?php echo $default['de_guest_cart_use']?' checked':''; ?>> 사용
            </td>
        </tr>
        <tr>
            <th scope="row">신규회원 쿠폰발행</th>
            <td>
                 <?php echo help("신규회원에게 주문금액 할인 쿠폰을 발행하시려면 아래를 설정하십시오."); ?>
                <label for="de_member_reg_coupon_use">쿠폰발행</label>
                <input type="checkbox" name="de_member_reg_coupon_use" value="1" id="de_member_reg_coupon_use"<?php echo $default['de_member_reg_coupon_use']?' checked':''; ?>>
                <label for="de_member_reg_coupon_price">쿠폰할인금액</label>
                <input type="text" name="de_member_reg_coupon_price" value="<?php echo get_sanitize_input($default['de_member_reg_coupon_price']); ?>" id="de_member_reg_coupon_price" class="frm_input" size="10"> 원
                <label for="de_member_reg_coupon_minimum">주문최소금액</label>
                <input type="text" name="de_member_reg_coupon_minimum" value="<?php echo get_sanitize_input($default['de_member_reg_coupon_minimum']); ?>" id="de_member_reg_coupon_minimum" class="frm_input" size="10"> 원이상
                <label for="de_member_reg_coupon_term">쿠폰유효기간</label>
                <input type="text" name="de_member_reg_coupon_term" value="<?php echo get_sanitize_input($default['de_member_reg_coupon_term']); ?>" id="de_member_reg_coupon_term" class="frm_input" size="5"> 일
            </td>
        </tr>
        <tr>
            <th scope="row">비회원에 대한<br/>개인정보수집 내용</th>
            <td><?php echo editor_html('de_guest_privacy', get_text(html_purifier($default['de_guest_privacy']), 0)); ?></td>
        </tr>
        <tr>
            <th scope="row">MYSQL USER</th>
            <td><?php echo G5_MYSQL_USER; ?></td>
        </tr>
        <tr>
            <th scope="row">MYSQL DB</th>
            <td><?php echo G5_MYSQL_DB; ?></td>
        </tr>
        <tr>
            <th scope="row">서버 IP</th>
            <td><?php echo ($_SERVER['SERVER_ADDR']?$_SERVER['SERVER_ADDR']:$_SERVER['LOCAL_ADDR']); ?></td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<button type="button" class="shop_etc">테마설정 가져오기</button>

<?php if (file_exists($logo_img) || file_exists($logo_img2) || file_exists($mobile_logo_img) || file_exists($mobile_logo_img2)) { ?>
<script>
$(".banner_or_img").addClass("scf_img");
$(function() {
    $(".scf_img_view").bind("click", function() {
        var sit_wimg_id = $(this).attr("id").split("_");
        var $img_display = $("#"+sit_wimg_id[1]);

        $img_display.toggle();

        if($img_display.is(":visible")) {
            $(this).text($(this).text().replace("확인", "닫기"));
        } else {
            $(this).text($(this).text().replace("닫기", "확인"));
        }

        if(sit_wimg_id[1].search("mainimg") > -1) {
            var $img = $("#"+sit_wimg_id[1]).children("img");
            var width = $img.width();
            var height = $img.height();
            if(width > 700) {
                var img_width = 700;
                var img_height = Math.round((img_width * height) / width);

                $img.width(img_width).height(img_height);
            }
        }
    });
    $(".sit_wimg_close").bind("click", function() {
        var $img_display = $(this).parents(".banner_or_img");
        var id = $img_display.attr("id");
        $img_display.toggle();
        var $button = $("#cf_"+id+"_view");
        $button.text($button.text().replace("닫기", "확인"));
    });
});
</script>
<?php } ?>
