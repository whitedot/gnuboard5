<section id="anc_sitfrm_extra">
    <h2>여분필드 설정</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
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
                <label for="it_<?php echo $i ?>_subj">여분필드 <?php echo $i ?> 제목</label>
                <input type="text" name="it_<?php echo $i ?>_subj" id="it_<?php echo $i ?>_subj" value="<?php echo get_text($it['it_'.$i.'_subj']) ?>" class="frm_input">
                <label for="it_<?php echo $i ?>">여분필드 <?php echo $i ?> 값</label>
                <input type="text" name="it_<?php echo $i ?>" value="<?php echo get_text($it['it_'.$i]) ?>" id="it_<?php echo $i ?>" class="frm_input">
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_<?php echo $i ?>" value="1" id="chk_ca_<?php echo $i ?>">
                <label for="chk_ca_<?php echo $i ?>">분류적용</label>
                <input type="checkbox" name="chk_all_<?php echo $i ?>" value="1" id="chk_all_<?php echo $i ?>">
                <label for="chk_all_<?php echo $i ?>">전체적용</label>
            </td>
        </tr>
        <?php } ?>
        <?php if ($w == "u") { ?>
        <tr>
            <th scope="row">입력일시</th>
            <td colspan="2">
                <?php echo help("상품을 처음 입력(등록)한 시간입니다."); ?>
                <?php echo $it['it_time']; ?>
            </td>
        </tr>
        <tr>
            <th scope="row">수정일시</th>
            <td colspan="2">
                <?php echo help("상품을 최종 수정한 시간입니다."); ?>
                <?php echo $it['it_update_time']; ?>
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>
