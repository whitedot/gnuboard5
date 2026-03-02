<section id="anc_mb_media" class="card">
    <div class="card-header">
        <h2 class="card-title">아이콘 및 이미지</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="mb_icon" class="form-label">회원아이콘</label>
                </div>
                <div class="af-field">
                    <?php echo help('이미지 크기는 <strong>넓이 ' . $config['cf_member_icon_width'] . '픽셀 높이 ' . $config['cf_member_icon_height'] . '픽셀</strong>로 해주세요.') ?>
                    <input type="file" name="mb_icon" id="mb_icon" class="form-input">
                    <?php
                    $mb_dir = substr($mb['mb_id'], 0, 2);
                    $icon_file = G5_DATA_PATH . '/member/' . $mb_dir . '/' . get_mb_icon_name($mb['mb_id']) . '.gif';
                    if (file_exists($icon_file)) {
                        $icon_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $icon_file);
                        $icon_filemtile = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?' . filemtime($icon_file) : '';
                    ?>
                    <div class="af-stack">
                        <img src="<?php echo $icon_url . $icon_filemtile; ?>" alt="">
                        <label for="del_mb_icon" class="af-check form-label">
                            <input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1" class="form-checkbox">
                            <span class="form-label">삭제</span>
                        </label>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_img" class="form-label">회원이미지</label>
                </div>
                <div class="af-field">
                    <?php echo help('이미지 크기는 <strong>넓이 ' . $config['cf_member_img_width'] . '픽셀 높이 ' . $config['cf_member_img_height'] . '픽셀</strong>로 해주세요.') ?>
                    <input type="file" name="mb_img" id="mb_img" class="form-input">
                    <?php
                    $mb_dir = substr($mb['mb_id'], 0, 2);
                    $icon_file = G5_DATA_PATH . '/member_image/' . $mb_dir . '/' . get_mb_icon_name($mb['mb_id']) . '.gif';
                    if (file_exists($icon_file)) {
                    ?>
                    <div class="af-stack">
                        <?php echo get_member_profile_img($mb['mb_id']); ?>
                        <label for="del_mb_img" class="af-check form-label">
                            <input type="checkbox" id="del_mb_img" name="del_mb_img" value="1" class="form-checkbox">
                            <span class="form-label">삭제</span>
                        </label>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
