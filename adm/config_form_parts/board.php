<section id="anc_cf_board" class="card">
    <div class="card-header">
        <h2 class="card-title">게시판 기본 설정</h2>
    </div>
    <div class="card-body">
        <p>각 게시판 관리에서 개별적으로 설정 가능합니다.</p>

        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_delay_sec" class="form-label">글쓰기 간격<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_delay_sec" value="<?php echo (int) $config['cf_delay_sec'] ?>" id="cf_delay_sec" required size="3" class="form-input">
                        <span>초 지난후 가능</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_link_target" class="form-label">새창 링크</label>
                </div>
                <div class="af-field">
                    <?php echo help('글내용중 자동 링크되는 타켓을 지정합니다.') ?>
                    <select name="cf_link_target" id="cf_link_target" class="form-select">
                        <option value="_blank" <?php echo get_selected($config['cf_link_target'], '_blank') ?>>_blank</option>
                        <option value="_self" <?php echo get_selected($config['cf_link_target'], '_self') ?>>_self</option>
                        <option value="_top" <?php echo get_selected($config['cf_link_target'], '_top') ?>>_top</option>
                        <option value="_new" <?php echo get_selected($config['cf_link_target'], '_new') ?>>_new</option>
                    </select>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_read_point" class="form-label">글읽기 포인트<strong>필수</strong></label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_read_point" value="<?php echo (int) $config['cf_read_point'] ?>" id="cf_read_point" required size="3" class="form-input">
                        <span>점</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_write_point" class="form-label">글쓰기 포인트</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_write_point" value="<?php echo (int) $config['cf_write_point'] ?>" id="cf_write_point" required size="3" class="form-input">
                        <span>점</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_comment_point" class="form-label">댓글쓰기 포인트</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_comment_point" value="<?php echo (int) $config['cf_comment_point'] ?>" id="cf_comment_point" required size="3" class="form-input">
                        <span>점</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_download_point" class="form-label">다운로드 포인트</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_download_point" value="<?php echo (int) $config['cf_download_point'] ?>" id="cf_download_point" required size="3" class="form-input">
                        <span>점</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_search_part" class="form-label">검색 단위</label>
                </div>
                <div class="af-field">
                    <div class="af-inline">
                        <input type="text" name="cf_search_part" value="<?php echo (int) $config['cf_search_part'] ?>" id="cf_search_part" size="4" class="form-input">
                        <span>건 단위로 검색</span>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_image_extension" class="form-label">이미지 업로드 확장자</label>
                </div>
                <div class="af-field">
                    <?php echo help('게시판 글작성시 이미지 파일 업로드 가능 확장자. | 로 구분') ?>
                    <input type="text" name="cf_image_extension" value="<?php echo get_sanitize_input($config['cf_image_extension']); ?>" id="cf_image_extension" size="70" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_flash_extension" class="form-label">플래쉬 업로드 확장자</label>
                </div>
                <div class="af-field">
                    <?php echo help('게시판 글작성시 플래쉬 파일 업로드 가능 확장자. | 로 구분') ?>
                    <input type="text" name="cf_flash_extension" value="<?php echo get_sanitize_input($config['cf_flash_extension']); ?>" id="cf_flash_extension" size="70" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_movie_extension" class="form-label">동영상 업로드 확장자</label>
                </div>
                <div class="af-field">
                    <?php echo help('게시판 글작성시 동영상 파일 업로드 가능 확장자. | 로 구분') ?>
                    <input type="text" name="cf_movie_extension" value="<?php echo get_sanitize_input($config['cf_movie_extension']); ?>" id="cf_movie_extension" size="70" class="form-input">
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_filter" class="form-label">단어 필터링</label>
                </div>
                <div class="af-field">
                    <?php echo help('입력된 단어가 포함된 내용은 게시할 수 없습니다. 단어와 단어 사이는 ,로 구분합니다.') ?>
                    <textarea name="cf_filter" id="cf_filter" rows="7" class="form-textarea"><?php echo get_sanitize_input($config['cf_filter']); ?></textarea>
                </div>
            </div>
        </div>
    </div>
</section>
