<section id="anc_bo_point">
    <h2>게시판 포인트 설정</h2>
    <?php echo $pg_anchor ?>

    
        
        
        
        
                <div class="ui-form-row">
            <div class="ui-form-label"><label for="chk_grp_point">기본값으로 설정</label></div>
            <div class="ui-form-field"><?php echo help('환경설정에 입력된 포인트로 설정') ?>
                <input type="checkbox" name="chk_grp_point" id="chk_grp_point" onclick="set_point(this.form)"></div>
        </div>
                <div class="ui-form-row">
            <div class="ui-form-label"><label for="bo_read_point">글읽기 포인트<strong>필수</strong></label></div>
            <div class="ui-form-field">
            <div><input type="text" name="bo_read_point" value="<?php echo $board['bo_read_point'] ?>" id="bo_read_point" required class="required" size="5"></div>
            <div><input type="checkbox" name="chk_grp_read_point" value="1" id="chk_grp_read_point">
                <label for="chk_grp_read_point">그룹적용</label>
                <input type="checkbox" name="chk_all_read_point" value="1" id="chk_all_read_point">
                <label for="chk_all_read_point">전체적용</label></div>
        </div>
        </div>
                <div class="ui-form-row">
            <div class="ui-form-label"><label for="bo_write_point">글쓰기 포인트<strong>필수</strong></label></div>
            <div class="ui-form-field">
            <div><input type="text" name="bo_write_point" value="<?php echo $board['bo_write_point'] ?>" id="bo_write_point" required class="required" size="5"></div>
            <div><input type="checkbox" name="chk_grp_write_point" value="1" id="chk_grp_write_point">
                <label for="chk_grp_write_point">그룹적용</label>
                <input type="checkbox" name="chk_all_write_point" value="1" id="chk_all_write_point">
                <label for="chk_all_write_point">전체적용</label></div>
        </div>
        </div>
                <div class="ui-form-row">
            <div class="ui-form-label"><label for="bo_comment_point">댓글쓰기 포인트<strong>필수</strong></label></div>
            <div class="ui-form-field">
            <div><input type="text" name="bo_comment_point" value="<?php echo $board['bo_comment_point'] ?>" id="bo_comment_point" required class="required" size="5"></div>
            <div><input type="checkbox" name="chk_grp_comment_point" value="1" id="chk_grp_comment_point">
                <label for="chk_grp_comment_point">그룹적용</label>
                <input type="checkbox" name="chk_all_comment_point" value="1" id="chk_all_comment_point">
                <label for="chk_all_comment_point">전체적용</label></div>
        </div>
        </div>
                <div class="ui-form-row">
            <div class="ui-form-label"><label for="bo_download_point">다운로드 포인트<strong>필수</strong></label></div>
            <div class="ui-form-field">
            <div><input type="text" name="bo_download_point" value="<?php echo $board['bo_download_point'] ?>" id="bo_download_point" required class="required" size="5"></div>
            <div><input type="checkbox" name="chk_grp_download_point" value="1" id="chk_grp_download_point">
                <label for="chk_grp_download_point">그룹적용</label>
                <input type="checkbox" name="chk_all_download_point" value="1" id="chk_all_download_point">
                <label for="chk_all_download_point">전체적용</label></div>
        </div>
        </div>
        
        
    
</section>
