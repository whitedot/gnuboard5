<section id="anc_bo_design">
    <h2 class="section-title">게시판 디자인/양식</h2>
    <?php echo $pg_anchor ?>

    <div class="form-card table-shell">
        <table>
        <caption>게시판 디자인/양식</caption>
        <colgroup>
            <col class="col-4">
            <col>
            <col class="col-3">
        </colgroup>
        <tbody>
            <tr>
            <th scope="row"><label for="bo_skin">스킨 디렉토리<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo get_skin_select('board', 'bo_skin', 'bo_skin', $board['bo_skin'], 'required'); ?>
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_skin" value="1" id="chk_grp_skin">
                <label for="chk_grp_skin">그룹적용</label>
                <input type="checkbox" name="chk_all_skin" value="1" id="chk_all_skin">
                <label for="chk_all_skin">전체적용</label>
            </td>
        </tr>
        <?php if ($is_admin === 'super') {   // 슈퍼관리자인 경우에만 수정 가능 ?>
        <tr>
            <th scope="row"><label for="bo_include_head">상단 파일 경로</label></th>
            <td>
                <input type="text" name="bo_include_head" value="<?php echo get_sanitize_input($board['bo_include_head']); ?>" id="bo_include_head" class="form-input" size="50">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_include_head" value="1" id="chk_grp_include_head">
                <label for="chk_grp_include_head">그룹적용</label>
                <input type="checkbox" name="chk_all_include_head" value="1" id="chk_all_include_head">
                <label for="chk_all_include_head">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_include_tail">하단 파일 경로</label></th>
            <td>
                <input type="text" name="bo_include_tail" value="<?php echo get_sanitize_input($board['bo_include_tail']); ?>" id="bo_include_tail" class="form-input" size="50">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_include_tail" value="1" id="chk_grp_include_tail">
                <label for="chk_grp_include_tail">그룹적용</label>
                <input type="checkbox" name="chk_all_include_tail" value="1" id="chk_all_include_tail">
                <label for="chk_all_include_tail">전체적용</label>
            </td>
        </tr>
        <tr id="admin_captcha_box" style="display:none;">
            <th scope="row">자동등록방지</th>
            <td>
                <?php
                echo help("파일 경로를 입력 또는 수정시 캡챠를 반드시 입력해야 합니다.");

                include_once G5_CAPTCHA_PATH.'/captcha.lib.php';
                $captcha_html = captcha_html();
                $captcha_js   = chk_captcha_js();
                echo $captcha_html;
                ?>
                <script>
                jQuery("#captcha_key").removeAttr("required").removeClass("required");
                </script>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_content_head">상단 내용</label></th>
            <td>
                <?php echo editor_html("bo_content_head", get_text(html_purifier($board['bo_content_head']), 0)); ?>
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_content_head" value="1" id="chk_grp_content_head">
                <label for="chk_grp_content_head">그룹적용</label>
                <input type="checkbox" name="chk_all_content_head" value="1" id="chk_all_content_head">
                <label for="chk_all_content_head">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_content_tail">하단 내용</label></th>
            <td>
                <?php echo editor_html("bo_content_tail", get_text(html_purifier($board['bo_content_tail']), 0)); ?>
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_content_tail" value="1" id="chk_grp_content_tail">
                <label for="chk_grp_content_tail">그룹적용</label>
                <input type="checkbox" name="chk_all_content_tail" value="1" id="chk_all_content_tail">
                <label for="chk_all_content_tail">전체적용</label>
            </td>
        </tr>
        <?php }     //end if $is_admin === 'super' ?>
         <tr>
            <th scope="row"><label for="bo_insert_content">글쓰기 기본 내용</label></th>
            <td>
                <textarea id="bo_insert_content" name="bo_insert_content" rows="5"><?php echo html_purifier($board['bo_insert_content']); ?></textarea>
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_insert_content" value="1" id="chk_grp_insert_content">
                <label for="chk_grp_insert_content">그룹적용</label>
                <input type="checkbox" name="chk_all_insert_content" value="1" id="chk_all_insert_content">
                <label for="chk_all_insert_content">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_subject_len">제목 길이<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('목록에서의 제목 글자수. 잘리는 글은 … 로 표시') ?>
                <input type="text" name="bo_subject_len" value="<?php echo $board['bo_subject_len'] ?>" id="bo_subject_len" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_subject_len" value="1" id="chk_grp_subject_len">
                <label for="chk_grp_subject_len">그룹적용</label>
                <input type="checkbox" name="chk_all_subject_len" value="1" id="chk_all_subject_len">
                <label for="chk_all_subject_len">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_mobile_subject_len">모바일 제목 길이<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('목록에서의 제목 글자수. 잘리는 글은 … 로 표시') ?>
                <input type="text" name="bo_mobile_subject_len" value="<?php echo $board['bo_mobile_subject_len'] ?>" id="bo_mobile_subject_len" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_mobile_subject_len" value="1" id="chk_grp_mobile_subject_len">
                <label for="chk_grp_mobile_subject_len">그룹적용</label>
                <input type="checkbox" name="chk_all_mobile_subject_len" value="1" id="chk_all_mobile_subject_len">
                <label for="chk_all_mobile_subject_len">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_page_rows">페이지당 목록 수<strong class="sr-only">필수</strong></label></th>
            <td>
                <input type="text" name="bo_page_rows" value="<?php echo $board['bo_page_rows'] ?>" id="bo_page_rows" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_page_rows" value="1" id="chk_grp_page_rows">
                <label for="chk_grp_page_rows">그룹적용</label>
                <input type="checkbox" name="chk_all_page_rows" value="1" id="chk_all_page_rows">
                <label for="chk_all_page_rows">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_mobile_page_rows">모바일 페이지당 목록 수<strong class="sr-only">필수</strong></label></th>
            <td>
                <input type="text" name="bo_mobile_page_rows" value="<?php echo $board['bo_mobile_page_rows'] ?>" id="bo_mobile_page_rows" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_mobile_page_rows" value="1" id="chk_grp_mobile_page_rows">
                <label for="chk_grp_mobile_page_rows">그룹적용</label>
                <input type="checkbox" name="chk_all_mobile_page_rows" value="1" id="chk_all_mobile_page_rows">
                <label for="chk_all_mobile_page_rows">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_gallery_cols">갤러리 이미지 수<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('갤러리 형식의 게시판 목록에서 이미지를 한줄에 몇장씩 보여 줄 것인지를 설정하는 값') ?>
                <input type="text" name="bo_gallery_cols" value="<?php echo $board['bo_gallery_cols'] ?>" id="bo_gallery_cols" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_gallery_cols" value="1" id="chk_grp_gallery_cols">
                <label for="chk_grp_gallery_cols">그룹적용</label>
                <input type="checkbox" name="chk_all_gallery_cols" value="1" id="chk_all_gallery_cols">
                <label for="chk_all_gallery_cols">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_gallery_width">갤러리 이미지 폭<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('갤러리 형식의 게시판 목록에서 썸네일 이미지의 폭을 설정하는 값') ?>
                <input type="text" name="bo_gallery_width" value="<?php echo $board['bo_gallery_width'] ?>" id="bo_gallery_width" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_gallery_width" value="1" id="chk_grp_gallery_width">
                <label for="chk_grp_gallery_width">그룹적용</label>
                <input type="checkbox" name="chk_all_gallery_width" value="1" id="chk_all_gallery_width">
                <label for="chk_all_gallery_width">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_gallery_height">갤러리 이미지 높이<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('갤러리 형식의 게시판 목록에서 썸네일 이미지의 높이를 설정하는 값') ?>
                <input type="text" name="bo_gallery_height" value="<?php echo $board['bo_gallery_height'] ?>" id="bo_gallery_height" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_gallery_height" value="1" id="chk_grp_gallery_height">
                <label for="chk_grp_gallery_height">그룹적용</label>
                <input type="checkbox" name="chk_all_gallery_height" value="1" id="chk_all_gallery_height">
                <label for="chk_all_gallery_height">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_mobile_gallery_width">모바일<br>갤러리 이미지 폭<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('모바일로 접속시 갤러리 형식의 게시판 목록에서 썸네일 이미지의 폭을 설정하는 값') ?>
                <input type="text" name="bo_mobile_gallery_width" value="<?php echo $board['bo_mobile_gallery_width'] ?>" id="bo_mobile_gallery_width" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_mobile_gallery_width" value="1" id="chk_grp_mobile_gallery_width">
                <label for="chk_grp_mobile_gallery_width">그룹적용</label>
                <input type="checkbox" name="chk_all_mobile_gallery_width" value="1" id="chk_all_mobile_gallery_width">
                <label for="chk_all_mobile_gallery_width">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_mobile_gallery_height">모바일<br>갤러리 이미지 높이<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('모바일로 접속시 갤러리 형식의 게시판 목록에서 썸네일 이미지의 높이를 설정하는 값') ?>
                <input type="text" name="bo_mobile_gallery_height" value="<?php echo $board['bo_mobile_gallery_height'] ?>" id="bo_mobile_gallery_height" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_mobile_gallery_height" value="1" id="chk_grp_mobile_gallery_height">
                <label for="chk_grp_mobile_gallery_height">그룹적용</label>
                <input type="checkbox" name="chk_all_mobile_gallery_height" value="1" id="chk_all_mobile_gallery_height">
                <label for="chk_all_mobile_gallery_height">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_table_width">게시판 폭<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('100 이하는 %') ?>
                <input type="text" name="bo_table_width" value="<?php echo $board['bo_table_width'] ?>" id="bo_table_width" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_table_width" value="1" id="chk_grp_table_width">
                <label for="chk_grp_table_width">그룹적용</label>
                <input type="checkbox" name="chk_all_table_width" value="1" id="chk_all_table_width">
                <label for="chk_all_table_width">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_image_width">이미지 폭 크기<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('게시판에서 출력되는 이미지의 폭 크기') ?>
                <input type="text" name="bo_image_width" value="<?php echo $board['bo_image_width'] ?>" id="bo_image_width" required class="required numeric form-input" size="4"> 픽셀
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_image_width" value="1" id="chk_grp_image_width">
                <label for="chk_grp_image_width">그룹적용</label>
                <input type="checkbox" name="chk_all_image_width" value="1" id="chk_all_image_width">
                <label for="chk_all_image_width">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_new">새글 아이콘<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('글 입력후 new 이미지를 출력하는 시간. 0을 입력하시면 아이콘을 출력하지 않습니다.') ?>
                <input type="text" name="bo_new" value="<?php echo $board['bo_new'] ?>" id="bo_new" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_new" value="1" id="chk_grp_new">
                <label for="chk_grp_new">그룹적용</label>
                <input type="checkbox" name="chk_all_new" value="1" id="chk_all_new">
                <label for="chk_all_new">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_hot">인기글 아이콘<strong class="sr-only">필수</strong></label></th>
            <td>
                <?php echo help('조회수가 설정값 이상이면 hot 이미지 출력. 0을 입력하시면 아이콘을 출력하지 않습니다.') ?>
                <input type="text" name="bo_hot" value="<?php echo $board['bo_hot'] ?>" id="bo_hot" required class="required numeric form-input" size="4">
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_hot" value="1" id="chk_grp_hot">
                <label for="chk_grp_hot">그룹적용</label>
                <input type="checkbox" name="chk_all_hot" value="1" id="chk_all_hot">
                <label for="chk_all_hot">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_reply_order">답변 달기</label></th>
            <td>
                <select id="bo_reply_order" name="bo_reply_order">
                    <option value="1"<?php echo get_selected($board['bo_reply_order'], 1, true); ?>>나중에 쓴 답변 아래로 달기 (기본)
                    <option value="0"<?php echo get_selected($board['bo_reply_order'], 0); ?>>나중에 쓴 답변 위로 달기
                </select>
            </td>
            <td class="cell-grpset">
                <input type="checkbox" id="chk_grp_reply_order" name="chk_grp_reply_order" value="1">
                <label for="chk_grp_reply_order">그룹적용</label>
                <input type="checkbox" id="chk_all_reply_order" name="chk_all_reply_order" value="1">
                <label for="chk_all_reply_order">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_sort_field">리스트 정렬 필드</label></th>
            <td>
                <?php echo help('리스트에서 기본으로 정렬에 사용할 필드를 선택합니다. "기본"으로 사용하지 않으시는 경우 속도가 느려질 수 있습니다.') ?>
                <select id="bo_sort_field" name="bo_sort_field">
                    <?php foreach (get_board_sort_fields($board) as $v) {
                        $option_value = $order_by_str = $v[0];
                        if ($v[0] === 'wr_num, wr_reply') {
                            $selected = (! $board['bo_sort_field']) ? 'selected="selected"' : '';
                            $option_value = '';
                        } else {
                            $selected = ($board['bo_sort_field'] === $v[0]) ? 'selected="selected"' : '';
                        }
                        
                        if ($order_by_str !== 'wr_num, wr_reply') {
                            $tmp = explode(',', $v[0]);
                            $order_by_str = $tmp[0];
                        }

                        echo '<option value="'.$option_value.'" '.$selected.' >'.$order_by_str.' : '.$v[1].'</option>';
                    } //end foreach ?>
                </select>
            </td>
            <td class="cell-grpset">
                <input type="checkbox" name="chk_grp_sort_field" value="1" id="chk_grp_sort_field">
                <label for="chk_grp_sort_field">그룹적용</label>
                <input type="checkbox" name="chk_all_sort_field" value="1" id="chk_all_sort_field">
                <label for="chk_all_sort_field">전체적용</label>
            </td>
        </tbody>
        </table>
    </div>
    <button type="button" class="get_theme_galc btn btn-secondary" >테마 이미지설정 가져오기</button>
</section>
