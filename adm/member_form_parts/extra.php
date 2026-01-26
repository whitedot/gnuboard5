<section id="anc_mb_extra">
    <h2 class="h2_frm">여분 필드</h2>
    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption>여분 필드</caption>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <?php for ($i = 1; $i <= 10; $i++) { ?>
                    <tr>
                        <th scope="row"><label for="mb_<?php echo $i ?>">여분 필드 <?php echo $i ?></label></th>
                        <td><input type="text" name="mb_<?php echo $i ?>" value="<?php echo $mb['mb_' . $i] ?>" id="mb_<?php echo $i ?>" class="frm_input" size="30" maxlength="255"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
