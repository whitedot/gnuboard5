<section id="anc_bo_extra">
    <h2 class="h2_frm">게시판 여분필드 설정</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시판 여분필드 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <?php for ($i=1; $i<=10; $i++) { ?>
        <tr>
            <th scope="row">여분필드<?php echo $i ?></th>
            <td class="td_extra">
                <label for="bo_<?php echo $i ?>_subj">여분필드 <?php echo $i ?> 제목</label>
                <input type="text" name="bo_<?php echo $i ?>_subj" id="bo_<?php echo $i ?>_subj" value="<?php echo get_text($board['bo_'.$i.'_subj']) ?>" class="frm_input">
                <label for="bo_<?php echo $i ?>">여분필드 <?php echo $i ?> 값</label>
                <input type="text" name="bo_<?php echo $i ?>" value="<?php echo get_text($board['bo_'.$i]) ?>" id="bo_<?php echo $i ?>" class="frm_input extra-value-input">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_<?php echo $i ?>" value="1" id="chk_grp_<?php echo $i ?>">
                <label for="chk_grp_<?php echo $i ?>">그룹적용</label>
                <input type="checkbox" name="chk_all_<?php echo $i ?>" value="1" id="chk_all_<?php echo $i ?>">
                <label for="chk_all_<?php echo $i ?>">전체적용</label>
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>
