<section id="anc_mb_media">
    <h2 class="section-title">아이콘 및 이미지</h2>
    <div class="form-card table-shell">
        <table>
            <caption>아이콘 및 이미지</caption>
            <colgroup>
                <col class="col-4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="mb_icon">회원아이콘</label></th>
                    <td>
                        <?php echo help('이미지 크기는 <strong>넓이 ' . $config['cf_member_icon_width'] . '픽셀 높이 ' . $config['cf_member_icon_height'] . '픽셀</strong>로 해주세요.') ?>
                        <input type="file" name="mb_icon" id="mb_icon">
                        <?php
                        $mb_dir = substr($mb['mb_id'], 0, 2);
                        $icon_file = G5_DATA_PATH . '/member/' . $mb_dir . '/' . get_mb_icon_name($mb['mb_id']) . '.gif';
                        if (file_exists($icon_file)) {
                            $icon_url = str_replace(G5_DATA_PATH, G5_DATA_URL, $icon_file);
                            $icon_filemtile = (defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) ? '?' . filemtime($icon_file) : '';
                            echo '<img src="' . $icon_url . $icon_filemtile . '" alt="">';
                            echo '<input type="checkbox" id="del_mb_icon" name="del_mb_icon" value="1">삭제';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_img">회원이미지</label></th>
                    <td>
                        <?php echo help('이미지 크기는 <strong>넓이 ' . $config['cf_member_img_width'] . '픽셀 높이 ' . $config['cf_member_img_height'] . '픽셀</strong>로 해주세요.') ?>
                        <input type="file" name="mb_img" id="mb_img">
                        <?php
                        $mb_dir = substr($mb['mb_id'], 0, 2);
                        $icon_file = G5_DATA_PATH . '/member_image/' . $mb_dir . '/' . get_mb_icon_name($mb['mb_id']) . '.gif';
                        if (file_exists($icon_file)) {
                            echo get_member_profile_img($mb['mb_id']);
                            echo '<input type="checkbox" id="del_mb_img" name="del_mb_img" value="1">삭제';
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
