<section id="anc_sitfrm_ini">
    <h2 class="h2_frm">기본정보</h2>
    <?php echo $pg_anchor; ?>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>기본정보 입력</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">상품코드</th>
            <td colspan="2">
                <?php if ($w == '') { // 추가 ?>
                    <?php echo help("상품의 코드는 10자리 숫자로 자동생성합니다. <b>직접 상품코드를 입력할 수도 있습니다.</b>\n상품코드는 영문자, 숫자, - 만 입력 가능합니다."); ?>
                    <input type="text" name="it_id" value="<?php echo time(); ?>" id="it_id" required class="frm_input required" size="20" maxlength="20">
                <?php } else { ?>
                    <input type="hidden" name="it_id" value="<?php echo $it['it_id']; ?>">
                    <span class="frm_ca_id"><?php echo $it['it_id']; ?></span>
                    <a href="<?php echo shop_item_url($it_id); ?>" class="btn_frmline">상품확인</a>
                    <a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemuselist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn_frmline">사용후기</a>
                    <a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemqalist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn_frmline">상품문의</a>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_name">상품명</label></th>
            <td colspan="2">
                <?php echo help("HTML 입력이 불가합니다."); ?>
                <input type="text" name="it_name" value="<?php echo get_text(cut_str($it['it_name'], 250, "")); ?>" id="it_name" required class="frm_input required" size="95">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_basic">기본설명</label></th>
            <td>
                <?php echo help("상품명 하단에 상품에 대한 추가적인 설명이 필요한 경우에 입력합니다. HTML 입력도 가능합니다."); ?>
                <input type="text" name="it_basic" value="<?php echo get_text(html_purifier($it['it_basic'])); ?>" id="it_basic" class="frm_input" size="95">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_basic" value="1" id="chk_ca_it_basic">
                <label for="chk_ca_it_basic">분류적용</label>
                <input type="checkbox" name="chk_all_it_basic" value="1" id="chk_all_it_basic">
                <label for="chk_all_it_basic">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_order">출력순서</label></th>
            <td>
                <?php echo help("숫자가 작을 수록 상위에 출력됩니다. 음수 입력도 가능하며 입력 가능 범위는 -2147483648 부터 2147483647 까지입니다.\n<b>입력하지 않으면 자동으로 출력됩니다.</b>"); ?>
                <input type="text" name="it_order" value="<?php echo $it['it_order']; ?>" id="it_order" class="frm_input" size="12">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_order" value="1" id="chk_ca_it_order">
                <label for="chk_ca_it_order">분류적용</label>
                <input type="checkbox" name="chk_all_it_order" value="1" id="chk_all_it_order">
                <label for="chk_all_it_order">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row">상품유형</th>
            <td>
                <?php echo help("메인화면에 유형별로 출력할때 사용합니다.\n이곳에 체크하게되면 상품리스트에서 유형별로 정렬할때 체크된 상품이 가장 먼저 출력됩니다."); ?>
                <input type="checkbox" name="it_type1" value="1" <?php echo ($it['it_type1'] ? "checked" : ""); ?> id="it_type1">
                <label for="it_type1">히트 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_hit.gif" alt=""></label>
                <input type="checkbox" name="it_type2" value="1" <?php echo ($it['it_type2'] ? "checked" : ""); ?> id="it_type2">
                <label for="it_type2">추천 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_rec.gif" alt=""></label>
                <input type="checkbox" name="it_type3" value="1" <?php echo ($it['it_type3'] ? "checked" : ""); ?> id="it_type3">
                <label for="it_type3">신상품 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_new.gif" alt=""></label>
                <input type="checkbox" name="it_type4" value="1" <?php echo ($it['it_type4'] ? "checked" : ""); ?> id="it_type4">
                <label for="it_type4">인기 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_best.gif" alt=""></label>
                <input type="checkbox" name="it_type5" value="1" <?php echo ($it['it_type5'] ? "checked" : ""); ?> id="it_type5">
                <label for="it_type5">할인 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_discount.gif" alt=""></label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_type" value="1" id="chk_ca_it_type">
                <label for="chk_ca_it_type">분류적용</label>
                <input type="checkbox" name="chk_all_it_type" value="1" id="chk_all_it_type">
                <label for="chk_all_it_type">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_maker">제조사</label></th>
            <td>
                <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                <input type="text" name="it_maker" value="<?php echo get_text($it['it_maker']); ?>" id="it_maker" class="frm_input" size="40">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_maker" value="1" id="chk_ca_it_maker">
                <label for="chk_ca_it_maker">분류적용</label>
                <input type="checkbox" name="chk_all_it_maker" value="1" id="chk_all_it_maker">
                <label for="chk_all_it_maker">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_origin">원산지</label></th>
            <td>
                <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                <input type="text" name="it_origin" value="<?php echo get_text($it['it_origin']); ?>" id="it_origin" class="frm_input" size="40">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_origin" value="1" id="chk_ca_it_origin">
                <label for="chk_ca_it_origin">분류적용</label>
                <input type="checkbox" name="chk_all_it_origin" value="1" id="chk_all_it_origin">
                <label for="chk_all_it_origin">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_brand">브랜드</label></th>
            <td>
                <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                <input type="text" name="it_brand" value="<?php echo get_text($it['it_brand']); ?>" id="it_brand" class="frm_input" size="40">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_brand" value="1" id="chk_ca_it_brand">
                <label for="chk_ca_it_brand">분류적용</label>
                <input type="checkbox" name="chk_all_it_brand" value="1" id="chk_all_it_brand">
                <label for="chk_all_it_brand">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_model">모델</label></th>
            <td>
                <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                <input type="text" name="it_model" value="<?php echo get_text($it['it_model']); ?>" id="it_model" class="frm_input" size="40">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_model" value="1" id="chk_ca_it_model">
                <label for="chk_ca_it_model">분류적용</label>
                <input type="checkbox" name="chk_all_it_model" value="1" id="chk_all_it_model">
                <label for="chk_all_it_model">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_tel_inq">전화문의</label></th>
            <td>
                <?php echo help("상품 금액 대신 전화문의로 표시됩니다."); ?>
                <input type="checkbox" name="it_tel_inq" value="1" id="it_tel_inq" <?php echo ($it['it_tel_inq']) ? "checked" : ""; ?>> 예
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_tel_inq" value="1" id="chk_ca_it_tel_inq">
                <label for="chk_ca_it_tel_inq">분류적용</label>
                <input type="checkbox" name="chk_all_it_tel_inq" value="1" id="chk_all_it_tel_inq">
                <label for="chk_all_it_tel_inq">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_use">판매가능</label></th>
            <td>
                <?php echo help("잠시 판매를 중단하거나 재고가 없을 경우에 체크를 해제해 놓으면 출력되지 않으며, 주문도 받지 않습니다."); ?>
                <input type="checkbox" name="it_use" value="1" id="it_use" <?php echo ($it['it_use']) ? "checked" : ""; ?>> 예
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_use" value="1" id="chk_ca_it_use">
                <label for="chk_ca_it_use">분류적용</label>
                <input type="checkbox" name="chk_all_it_use" value="1" id="chk_all_it_use">
                <label for="chk_all_it_use">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_nocoupon">쿠폰적용안함</label></th>
            <td>
                <?php echo help("설정에 체크하시면 쿠폰 생성 때 상품 검색 결과에 노출되지 않습니다."); ?>
                <input type="checkbox" name="it_nocoupon" value="1" id="it_nocoupon" <?php echo ($it['it_nocoupon']) ? "checked" : ""; ?>> 예
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_nocoupon" value="1" id="chk_ca_it_nocoupon">
                <label for="chk_ca_it_nocoupon">분류적용</label>
                <input type="checkbox" name="chk_all_it_nocoupon" value="1" id="chk_all_it_nocoupon">
                <label for="chk_all_it_nocoupon">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="ec_mall_pid">네이버쇼핑 상품ID</label></th>
            <td colspan="2">
                <?php echo help("네이버쇼핑에 입점한 경우 네이버쇼핑 상품ID를 입력하시면 네이버페이와 연동됩니다.<br>일부 쇼핑몰의 경우 네이버쇼핑 상품ID 대신 쇼핑몰 상품ID를 입력해야 하는 경우가 있습니다.<br>네이버페이 연동과정에서 이 부분에 대한 안내가 이뤄지니 안내받은 대로 값을 입력하시면 됩니다."); ?>
                <input type="text" name="ec_mall_pid" value="<?php echo get_text($it['ec_mall_pid']); ?>" id="ec_mall_pid" class="frm_input" size="20">
            </td>
        </tr>
        <tr>
            <th scope="row">상품설명</th>
            <td colspan="2"> <?php echo editor_html('it_explan', get_text(html_purifier($it['it_explan']), 0)); ?></td>
        </tr>
        <tr>
            <th scope="row">모바일 상품설명</th>
            <td colspan="2"> <?php echo editor_html('it_mobile_explan', get_text(html_purifier($it['it_mobile_explan']), 0)); ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="it_sell_email">판매자 e-mail</label></th>
            <td>
                <?php echo help("운영자와 실제 판매자가 다른 경우 실제 판매자의 e-mail을 입력하면, 상품 주문 시점을 기준으로 실제 판매자에게도 주문서를 발송합니다."); ?>
                <input type="text" name="it_sell_email" value="<?php echo get_sanitize_input($it['it_sell_email']); ?>" id="it_sell_email" class="frm_input" size="40">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_sell_email" value="1" id="chk_ca_it_sell_email">
                <label for="chk_ca_it_sell_email">분류적용</label>
                <input type="checkbox" name="chk_all_it_sell_email" value="1" id="chk_all_it_sell_email">
                <label for="chk_all_it_sell_email">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_shop_memo">상점메모</label></th>
            <td><textarea name="it_shop_memo" id="it_shop_memo"><?php echo html_purifier($it['it_shop_memo']); ?></textarea></td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_shop_memo" value="1" id="chk_ca_it_shop_memo">
                <label for="chk_ca_it_shop_memo">분류적용</label>
                <input type="checkbox" name="chk_all_it_shop_memo" value="1" id="chk_all_it_shop_memo">
                <label for="chk_all_it_shop_memo">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>
