<section id="anc_scf_index">
    <h2>쇼핑몰 초기화면</h2>
    <?php echo $pg_anchor; ?>
    <div>
        <p>
            상품관리에서 선택한 상품의 타입대로 쇼핑몰 초기화면에 출력합니다. (상품 타입 히트/추천/최신/인기/할인)<br>
            각 타입별로 선택된 상품이 없으면 쇼핑몰 초기화면에 출력하지 않습니다.
        </p>
    </div>

    <div>
        <table>
        <caption>쇼핑몰 초기화면 설정</caption>
        <colgroup>
            <col>
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">히트상품출력</th>
            <td>
                <label for="de_type1_list_use">출력</label>
                <input type="checkbox" name="de_type1_list_use" value="1" id="de_type1_list_use" <?php echo $default['de_type1_list_use']?"checked":""; ?>>
                <label for="de_type1_list_skin">스킨</label>
                <select name="de_type1_list_skin" id="de_type1_list_skin">
                    <?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type1_list_skin']); ?>
                </select>
                <label for="de_type1_list_mod">1줄당 이미지 수</label>
                <input type="text" name="de_type1_list_mod" value="<?php echo get_sanitize_input($default['de_type1_list_mod']); ?>" id="de_type1_list_mod" size="3">
                <label for="de_type1_list_row">출력할 줄 수</label>
                <input type="text" name="de_type1_list_row" value="<?php echo get_sanitize_input($default['de_type1_list_row']); ?>" id="de_type1_list_row" size="3">
                <label for="de_type1_img_width">이미지 폭</label>
                <input type="text" name="de_type1_img_width" value="<?php echo get_sanitize_input($default['de_type1_img_width']); ?>" id="de_type1_img_width" size="3">
                <label for="de_type1_img_height">이미지 높이</label>
                <input type="text" name="de_type1_img_height" value="<?php echo get_sanitize_input($default['de_type1_img_height']); ?>" id="de_type1_img_height" size="3">
            </td>
        </tr>
        <tr>
            <th scope="row">추천상품출력</th>
            <td>
                <label for="de_type2_list_use">출력</label>
                <input type="checkbox" name="de_type2_list_use" value="1" id="de_type2_list_use" <?php echo $default['de_type2_list_use']?"checked":""; ?>>
                <label for="de_type2_list_skin">스킨</label>
                <select name="de_type2_list_skin" id="de_type2_list_skin">
                    <?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type2_list_skin']); ?>
                </select>
                <label for="de_type2_list_mod">1줄당 이미지 수</label>
                <input type="text" name="de_type2_list_mod" value="<?php echo get_sanitize_input($default['de_type2_list_mod']); ?>" id="de_type2_list_mod" size="3">
                <label for="de_type2_list_row">출력할 줄 수</label>
                <input type="text" name="de_type2_list_row" value="<?php echo get_sanitize_input($default['de_type2_list_row']); ?>" id="de_type2_list_row" size="3">
                <label for="de_type2_img_width">이미지 폭</label>
                <input type="text" name="de_type2_img_width" value="<?php echo get_sanitize_input($default['de_type2_img_width']); ?>" id="de_type2_img_width" size="3">
                <label for="de_type2_img_height">이미지 높이</label>
                <input type="text" name="de_type2_img_height" value="<?php echo get_sanitize_input($default['de_type2_img_height']); ?>" id="de_type2_img_height" size="3">
            </td>
        </tr>
        <tr>
            <th scope="row">최신상품출력</th>
            <td>
                <label for="de_type3_list_use">출력</label>
                <input type="checkbox" name="de_type3_list_use" value="1" id="de_type3_list_use" <?php echo $default['de_type3_list_use']?"checked":""; ?>>
                <label for="de_type3_list_skin">스킨</label>
                <select name="de_type3_list_skin" id="de_type3_list_skin">
                    <?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type3_list_skin']); ?>
                </select>
                <label for="de_type3_list_mod">1줄당 이미지 수</label>
                <input type="text" name="de_type3_list_mod" value="<?php echo get_sanitize_input($default['de_type3_list_mod']); ?>" id="de_type3_list_mod" size="3">
                <label for="de_type3_list_row">출력할 줄 수</label>
                <input type="text" name="de_type3_list_row" value="<?php echo get_sanitize_input($default['de_type3_list_row']); ?>" id="de_type3_list_row" size="3">
                <label for="de_type3_img_width">이미지 폭</label>
                <input type="text" name="de_type3_img_width" value="<?php echo get_sanitize_input($default['de_type3_img_width']); ?>" id="de_type3_img_width" size="3">
                <label for="de_type3_img_height">이미지 높이</label>
                <input type="text" name="de_type3_img_height" value="<?php echo get_sanitize_input($default['de_type3_img_height']); ?>" id="de_type3_img_height" size="3">
            </td>
        </tr>
        <tr>
            <th scope="row">인기상품출력</th>
            <td>
                <label for="de_type4_list_use">출력</label>
                <input type="checkbox" name="de_type4_list_use" value="1" id="de_type4_list_use" <?php echo $default['de_type4_list_use']?"checked":""; ?>>
                <label for="de_type4_list_skin">스킨</label>
                <select name="de_type4_list_skin" id="de_type4_list_skin">
                    <?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type4_list_skin']); ?>
                </select>
                <label for="de_type4_list_mod">1줄당 이미지 수</label>
                <input type="text" name="de_type4_list_mod" value="<?php echo get_sanitize_input($default['de_type4_list_mod']); ?>" id="de_type4_list_mod" size="3">
                <label for="de_type4_list_row">출력할 줄 수</label>
                <input type="text" name="de_type4_list_row" value="<?php echo get_sanitize_input($default['de_type4_list_row']); ?>" id="de_type4_list_row" size="3">
                <label for="de_type4_img_width">이미지 폭</label>
                <input type="text" name="de_type4_img_width" value="<?php echo get_sanitize_input($default['de_type4_img_width']); ?>" id="de_type4_img_width" size="3">
                <label for="de_type4_img_height">이미지 높이</label>
                <input type="text" name="de_type4_img_height" value="<?php echo get_sanitize_input($default['de_type4_img_height']); ?>" id="de_type4_img_height" size="3">
            </td>
        </tr>
        <tr>
            <th scope="row">할인상품출력</th>
            <td>
                <label for="de_type5_list_use">출력</label>
                <input type="checkbox" name="de_type5_list_use" value="1" id="de_type5_list_use" <?php echo $default['de_type5_list_use']?"checked":""; ?>>
                <label for="de_type5_list_skin">스킨</label>
                <select name="de_type5_list_skin" id="de_type5_list_skin">
                    <?php echo get_list_skin_options("^main.[0-9]+\.skin\.php", G5_SHOP_SKIN_PATH, $default['de_type5_list_skin']); ?>
                </select>
                <label for="de_type5_list_mod">1줄당 이미지 수</label>
                <input type="text" name="de_type5_list_mod" value="<?php echo get_sanitize_input($default['de_type5_list_mod']); ?>" id="de_type5_list_mod" size="3">
                <label for="de_type5_list_row">출력할 줄 수</label>
                <input type="text" name="de_type5_list_row" value="<?php echo get_sanitize_input($default['de_type5_list_row']); ?>" id="de_type5_list_row" size="3">
                <label for="de_type5_img_width">이미지 폭</label>
                <input type="text" name="de_type5_img_width" value="<?php echo get_sanitize_input($default['de_type5_img_width']); ?>" id="de_type5_img_width" size="3">
                <label for="de_type5_img_height">이미지 높이</label>
                <input type="text" name="de_type5_img_height" value="<?php echo get_sanitize_input($default['de_type5_img_height']); ?>" id="de_type5_img_height" size="3">
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<button type="button" class="shop_pc_index">테마설정 가져오기</button>
