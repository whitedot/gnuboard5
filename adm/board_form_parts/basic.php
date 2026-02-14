<section id="anc_bo_basic">
    <h2 class="h2_frm">寃뚯떆??湲곕낯 ?ㅼ젙</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>寃뚯떆??湲곕낯 ?ㅼ젙</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_table">TABLE<?php echo $sr_only ?></label></th>
            <td colspan="2">
                <input type="text" name="bo_table" value="<?php echo $board['bo_table'] ?>" id="bo_table" <?php echo $required ?> <?php echo $readonly ?> class="frm_input <?php echo $readonly ?> <?php echo $required ?> <?php echo $required_valid ?>" maxlength="20">
                <?php if ($w == '') { ?>
                    ?곷Ц?? ?レ옄, _ 留?媛??(怨듬갚?놁씠 20???대궡)
                <?php } else { ?>
                    <a href="<?php echo get_pretty_url($board['bo_table']) ?>" class="btn_frmline">寃뚯떆??諛붾줈媛湲?/a>
                    <a href="./board_list.php?<?php echo $qstr;?>" class="btn_frmline">紐⑸줉?쇰줈</a>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="gr_id">洹몃９<strong class="sr-only">?꾩닔</strong></label></th>
            <td colspan="2">
                <?php echo get_group_select('gr_id', $board['gr_id'], 'required'); ?>
                <?php if ($w=='u') { ?>
                    <a href="javascript:document.location.href='./board_list.php?sfl=a.gr_id&stx='+document.fboardform.gr_id.value;" class="btn_frmline">?숈씪洹몃９ 寃뚯떆?먮ぉ濡?/a>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_subject">寃뚯떆???쒕ぉ<strong class="sr-only">?꾩닔</strong></label></th>
            <td colspan="2">
                <input type="text" name="bo_subject" value="<?php echo get_text($board['bo_subject']) ?>" id="bo_subject" required class="required frm_input" size="80" maxlength="120">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_mobile_subject">紐⑤컮??寃뚯떆???쒕ぉ</label></th>
            <td colspan="2">
                <?php echo help("紐⑤컮?쇱뿉??蹂댁뿬吏??寃뚯떆???쒕ぉ???ㅻⅨ 寃쎌슦???낅젰?⑸땲?? ?낅젰???놁쑝硫?湲곕낯 寃뚯떆???쒕ぉ??異쒕젰?⑸땲??") ?>
                <input type="text" name="bo_mobile_subject" value="<?php echo get_text($board['bo_mobile_subject']) ?>" id="bo_mobile_subject" class="frm_input" size="80" maxlength="120">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_device">?묒냽湲곌린</label></th>
            <td>
                <?php echo help("PC ? 紐⑤컮???ъ슜??援щ텇?⑸땲??") ?>
                <select id="bo_device" name="bo_device">
                    <option value="both"<?php echo get_selected($board['bo_device'], 'both'); ?>>PC? 紐⑤컮?쇱뿉??紐⑤몢 ?ъ슜</option>
                    <option value="pc"<?php echo get_selected($board['bo_device'], 'pc'); ?>>PC ?꾩슜</option>
                    <option value="mobile"<?php echo get_selected($board['bo_device'], 'mobile'); ?>>紐⑤컮???꾩슜</option>
                </select>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_device" value="1" id="chk_grp_device">
                <label for="chk_grp_device">洹몃９?곸슜</label>
                <input type="checkbox" name="chk_all_device" value="1" id="chk_all_device">
                <label for="chk_all_device">?꾩껜?곸슜</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_category_list">遺꾨쪟</label></th>
            <td>
                <?php echo help('遺꾨쪟? 遺꾨쪟 ?ъ씠??| 濡?援щ텇?섏꽭?? (?? 吏덈Ц|?듬?) 泥レ옄濡?#? ?낅젰?섏? 留덉꽭?? (?? #吏덈Ц|#?듬? [X])'."\n".'遺꾨쪟紐낆뿉 ?쇰? ?뱀닔臾몄옄 ()/ ???ъ슜?좎닔 ?놁뒿?덈떎.'); ?>
                <input type="text" name="bo_category_list" value="<?php echo get_text($board['bo_category_list']) ?>" id="bo_category_list" class="frm_input" size="70">
                <input type="checkbox" name="bo_use_category" value="1" id="bo_use_category" <?php echo $board['bo_use_category']?'checked':''; ?>>
                <label for="bo_use_category">?ъ슜</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_category_list" value="1" id="chk_grp_category_list">
                <label for="chk_grp_category_list">洹몃９?곸슜</label>
                <input type="checkbox" name="chk_all_category_list" value="1" id="chk_all_category_list">
                <label for="chk_all_category_list">?꾩껜?곸슜</label>
            </td>
        </tr>
        <?php if ($w == 'u') { ?>
        <tr>
            <th scope="row"><label for="proc_count">移댁슫??議곗젙</label></th>
            <td colspan="2">
                <?php echo help('?꾩옱 ?먭???: '.number_format($board['bo_count_write']).', ?꾩옱 ?볤???: '.number_format($board['bo_count_comment'])."\n".'寃뚯떆??紐⑸줉?먯꽌 湲??踰덊샇媛 留욎? ?딆쓣 寃쎌슦??泥댄겕?섏떗?쒖삤.') ?>
                <input type="checkbox" name="proc_count" value="1" id="proc_count">
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>
