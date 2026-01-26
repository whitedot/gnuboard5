    <section id="anc_cf_extra">
        <h2 class="h2_frm">여분필드 기본 설정</h2>
        <?php echo $pg_anchor ?>
        <div class="local_desc02 local_desc">
            <p>각 게시판 관리에서 개별적으로 설정 가능합니다.</p>
        </div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>여분필드 기본 설정</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                        <tr>
                            <th scope="row">여분필드<?php echo $i ?></th>
                            <td class="td_extra">
                                <label for="cf_<?php echo $i ?>_subj">여분필드<?php echo $i ?> 제목</label>
                                <input type="text" name="cf_<?php echo $i ?>_subj" value="<?php echo get_text($config['cf_' . $i . '_subj']) ?>" id="cf_<?php echo $i ?>_subj" class="frm_input" size="30">
                                <label for="cf_<?php echo $i ?>">여분필드<?php echo $i ?> 값</label>
                                <input type="text" name="cf_<?php echo $i ?>" value="<?php echo get_sanitize_input($config['cf_' . $i]); ?>" id="cf_<?php echo $i ?>" class="frm_input extra-value-input" size="30">
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
