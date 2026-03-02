<section id="anc_bo_auth" class="card">
    <div class="card-header">
        <h2 class="card-title">게시판 권한 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
            <div class="af-label"><label for="bo_admin" class="form-label">게시판 관리자</label></div>
            <div class="af-field">
            <div><input type="text" class="form-input" name="bo_admin" value="<?php echo $board['bo_admin'] ?>" id="bo_admin" maxlength="20"></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_admin" value="1" id="chk_grp_admin">
                <label for="chk_grp_admin" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_admin" value="1" id="chk_all_admin">
                <label for="chk_all_admin" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_list_level" class="form-label">목록보기 권한</label></div>
            <div class="af-field">
            <div><?php echo help('권한 1은 비회원, 2 이상 회원입니다. 권한은 10 이 가장 높습니다.') ?>
                <?php echo get_member_level_select('bo_list_level', 1, 10, $board['bo_list_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_list_level" value="1" id="chk_grp_list_level">
                <label for="chk_grp_list_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_list_level" value="1" id="chk_all_list_level">
                <label for="chk_all_list_level" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_read_level" class="form-label">글읽기 권한</label></div>
            <div class="af-field">
            <div><?php echo get_member_level_select('bo_read_level', 1, 10, $board['bo_read_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_read_level" value="1" id="chk_grp_read_level">
                <label for="chk_grp_read_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_read_level" value="1" id="chk_all_read_level">
                <label for="chk_all_read_level" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_write_level" class="form-label">글쓰기 권한</label></div>
            <div class="af-field">
            <div><?php echo get_member_level_select('bo_write_level', 1, 10, $board['bo_write_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_write_level" value="1" id="chk_grp_write_level">
                <label for="chk_grp_write_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_write_level" value="1" id="chk_all_write_level">
                <label for="chk_all_write_level" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_reply_level" class="form-label">글답변 권한</label></div>
            <div class="af-field">
            <div><?php echo get_member_level_select('bo_reply_level', 1, 10, $board['bo_reply_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_reply_level" value="1" id="chk_grp_reply_level">
                <label for="chk_grp_reply_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_reply_level" value="1" id="chk_all_reply_level">
                <label for="chk_all_reply_level" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_comment_level" class="form-label">댓글쓰기 권한</label></div>
            <div class="af-field">
            <div><?php echo get_member_level_select('bo_comment_level', 1, 10, $board['bo_comment_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_comment_level" value="1" id="chk_grp_comment_level">
                <label for="chk_grp_comment_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_comment_level" value="1" id="chk_all_comment_level">
                <label for="chk_all_comment_level" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_link_level" class="form-label">링크 권한</label></div>
            <div class="af-field">
            <div><?php echo get_member_level_select('bo_link_level', 1, 10, $board['bo_link_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_link_level" value="1" id="chk_grp_link_level">
                <label for="chk_grp_link_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_link_level" value="1" id="chk_all_link_level">
                <label for="chk_all_link_level" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_upload_level" class="form-label">업로드 권한</label></div>
            <div class="af-field">
            <div><?php echo get_member_level_select('bo_upload_level', 1, 10, $board['bo_upload_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_upload_level" value="1" id="chk_grp_upload_level">
                <label for="chk_grp_upload_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_upload_level" value="1" id="chk_all_upload_level">
                <label for="chk_all_upload_level" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_download_level" class="form-label">다운로드 권한</label></div>
            <div class="af-field">
            <div><?php echo get_member_level_select('bo_download_level', 1, 10, $board['bo_download_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_download_level" value="1" id="chk_grp_download_level">
                <label for="chk_grp_download_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_download_level" value="1" id="chk_all_download_level">
                <label for="chk_all_download_level" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_html_level" class="form-label">HTML 쓰기 권한</label></div>
            <div class="af-field">
            <div><?php echo get_member_level_select('bo_html_level', 1, 10, $board['bo_html_level'], 'class="form-select"') ?></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_html_level" value="1" id="chk_grp_html_level">
                <label for="chk_grp_html_level" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_html_level" value="1" id="chk_all_html_level">
                <label for="chk_all_html_level" class="form-label">전체적용</label></div>
        </div>
        </div>
        </div>
    </div>
</section>




