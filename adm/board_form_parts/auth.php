<section id="anc_bo_auth">
    <h2>게시판 권한 설정</h2>
    <?php echo $pg_anchor ?>

    <div>
        <table>
        <caption>게시판 권한 설정</caption>
        <colgroup>
            <col>
            <col>
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_admin">게시판 관리자</label></th>
            <td>
                <input type="text" name="bo_admin" value="<?php echo $board['bo_admin'] ?>" id="bo_admin" maxlength="20">
            </td>
            <td>
                <input type="checkbox" name="chk_grp_admin" value="1" id="chk_grp_admin">
                <label for="chk_grp_admin">그룹적용</label>
                <input type="checkbox" name="chk_all_admin" value="1" id="chk_all_admin">
                <label for="chk_all_admin">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_list_level">목록보기 권한</label></th>
            <td>
                <?php echo help('권한 1은 비회원, 2 이상 회원입니다. 권한은 10 이 가장 높습니다.') ?>
                <?php echo get_member_level_select('bo_list_level', 1, 10, $board['bo_list_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_list_level" value="1" id="chk_grp_list_level">
                <label for="chk_grp_list_level">그룹적용</label>
                <input type="checkbox" name="chk_all_list_level" value="1" id="chk_all_list_level">
                <label for="chk_all_list_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_read_level">글읽기 권한</label></th>
            <td>
                <?php echo get_member_level_select('bo_read_level', 1, 10, $board['bo_read_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_read_level" value="1" id="chk_grp_read_level">
                <label for="chk_grp_read_level">그룹적용</label>
                <input type="checkbox" name="chk_all_read_level" value="1" id="chk_all_read_level">
                <label for="chk_all_read_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_write_level">글쓰기 권한</label></th>
            <td>
                <?php echo get_member_level_select('bo_write_level', 1, 10, $board['bo_write_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_write_level" value="1" id="chk_grp_write_level">
                <label for="chk_grp_write_level">그룹적용</label>
                <input type="checkbox" name="chk_all_write_level" value="1" id="chk_all_write_level">
                <label for="chk_all_write_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_reply_level">글답변 권한</label></th>
            <td>
                <?php echo get_member_level_select('bo_reply_level', 1, 10, $board['bo_reply_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_reply_level" value="1" id="chk_grp_reply_level">
                <label for="chk_grp_reply_level">그룹적용</label>
                <input type="checkbox" name="chk_all_reply_level" value="1" id="chk_all_reply_level">
                <label for="chk_all_reply_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_comment_level">댓글쓰기 권한</label></th>
            <td>
                <?php echo get_member_level_select('bo_comment_level', 1, 10, $board['bo_comment_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_comment_level" value="1" id="chk_grp_comment_level">
                <label for="chk_grp_comment_level">그룹적용</label>
                <input type="checkbox" name="chk_all_comment_level" value="1" id="chk_all_comment_level">
                <label for="chk_all_comment_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_link_level">링크 권한</label></th>
            <td>
                <?php echo get_member_level_select('bo_link_level', 1, 10, $board['bo_link_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_link_level" value="1" id="chk_grp_link_level">
                <label for="chk_grp_link_level">그룹적용</label>
                <input type="checkbox" name="chk_all_link_level" value="1" id="chk_all_link_level">
                <label for="chk_all_link_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_upload_level">업로드 권한</label></th>
            <td>
                <?php echo get_member_level_select('bo_upload_level', 1, 10, $board['bo_upload_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_upload_level" value="1" id="chk_grp_upload_level">
                <label for="chk_grp_upload_level">그룹적용</label>
                <input type="checkbox" name="chk_all_upload_level" value="1" id="chk_all_upload_level">
                <label for="chk_all_upload_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_download_level">다운로드 권한</label></th>
            <td>
                <?php echo get_member_level_select('bo_download_level', 1, 10, $board['bo_download_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_download_level" value="1" id="chk_grp_download_level">
                <label for="chk_grp_download_level">그룹적용</label>
                <input type="checkbox" name="chk_all_download_level" value="1" id="chk_all_download_level">
                <label for="chk_all_download_level">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_html_level">HTML 쓰기 권한</label></th>
            <td>
                <?php echo get_member_level_select('bo_html_level', 1, 10, $board['bo_html_level']) ?>
            </td>
            <td>
                <input type="checkbox" name="chk_grp_html_level" value="1" id="chk_grp_html_level">
                <label for="chk_grp_html_level">그룹적용</label>
                <input type="checkbox" name="chk_all_html_level" value="1" id="chk_all_html_level">
                <label for="chk_all_html_level">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>
