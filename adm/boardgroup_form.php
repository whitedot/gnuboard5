<?php
$sub_menu = "300200";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

if ($is_admin != 'super' && $w == '') {
    alert('理쒓퀬愿由ъ옄留??묎렐 媛?ν빀?덈떎.');
}

$html_title = 'Board Group';
$gr_id_attr = '';
$sr_only = '';

if (!isset($group['gr_id'])) {
    $group['gr_id'] = '';
    $group['gr_subject'] = '';
    $group['gr_device'] = '';
}

$gr = array('gr_use_access' => 0, 'gr_admin' => '');
if ($w == '') {
    $gr_id_attr = 'required';
    $sr_only = '<strong class="sr-only"> ?꾩닔</strong>';
    $html_title .= ' ?앹꽦';
} elseif ($w == 'u') {
    $gr_id_attr = 'readonly';
    $gr = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id' ");
    $html_title .= ' ?섏젙';
} else {
    alert('?쒕?濡???媛믪씠 ?섏뼱?ㅼ? ?딆븯?듬땲??');
}

// ?묎렐?뚯썝??
$sql1 = " select count(*) as cnt from {$g5['group_member_table']} where gr_id = '{$gr_id}' ";
$row1 = sql_fetch($sql1);
$group_member_count = $row1['cnt'];

$g5['title'] = $html_title;
require_once './admin.head.php';
?>

<form name="fboardgroup" id="fboardgroup" action="./boardgroup_form_update.php" onsubmit="return fboardgroup_check(this);" method="post" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?></caption>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="gr_id">洹몃９ ID<?php echo $sr_only ?></label></th>
                    <td><input type="text" name="gr_id" value="<?php echo $group['gr_id'] ?>" id="gr_id" <?php echo $gr_id_attr; ?> class="<?php echo $gr_id_attr; ?> alnum_ frm_input" maxlength="10">
                        <?php
                        if ($w == '') {
                            echo '?곷Ц?? ?レ옄, _ 留?媛??(怨듬갚?놁씠)';
                        } else {
                            echo '<a href="' . G5_BBS_URL . '/group.php?gr_id=' . $group['gr_id'] . '" class="btn_frmline">寃뚯떆?먭렇猷?諛붾줈媛湲?/a>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="gr_subject">洹몃９ ?쒕ぉ<strong class="sr-only"> ?꾩닔</strong></label></th>
                    <td>
                        <input type="text" name="gr_subject" value="<?php echo get_text($group['gr_subject']) ?>" id="gr_subject" required class="required frm_input" size="80">
                        <?php
                        if ($w == 'u') {
                            echo '<a href="./board_form.php?gr_id=' . $gr_id . '" class="btn_frmline">寃뚯떆?먯깮??/a>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="gr_device">?묒냽湲곌린</label></th>
                    <td>
                        <?php echo help("PC ? 紐⑤컮???ъ슜??援щ텇?⑸땲??") ?>
                        <select id="gr_device" name="gr_device">
                            <option value="both" <?php echo get_selected($group['gr_device'], 'both', true); ?>>PC? 紐⑤컮?쇱뿉??紐⑤몢 ?ъ슜</option>
                            <option value="pc" <?php echo get_selected($group['gr_device'], 'pc'); ?>>PC ?꾩슜</option>
                            <option value="mobile" <?php echo get_selected($group['gr_device'], 'mobile'); ?>>紐⑤컮???꾩슜</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php
                        if ($is_admin == 'super') {
                            echo '<label for="gr_admin">洹몃９ 愿由ъ옄</label>';
                        } else {
                            echo '洹몃９ 愿由ъ옄';
                        }
                        ?>
                    </th>
                    <td>
                        <?php
                        if ($is_admin == 'super') {
                            echo '<input type="text" id="gr_admin" name="gr_admin" class="frm_input" value="' . $gr['gr_admin'] . '" maxlength="20">';
                        } else {
                            echo '<input type="hidden" id="gr_admin" name="gr_admin" value="' . $gr['gr_admin'] . '">' . $gr['gr_admin'];
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="gr_use_access">?묎렐?뚯썝?ъ슜</label></th>
                    <td>
                        <?php echo help("?ъ슜??泥댄겕?섏떆硫???洹몃９???랁븳 寃뚯떆?먯? ?묎렐媛?ν븳 ?뚯썝留??묎렐??媛?ν빀?덈떎.") ?>
                        <input type="checkbox" name="gr_use_access" value="1" id="gr_use_access" <?php echo $gr['gr_use_access'] ? 'checked' : ''; ?>>
                        ?ъ슜
                    </td>
                </tr>
                <tr>
                    <th scope="row">?묎렐?뚯썝??/th>
                    <td>
                        <?php
                        echo '<a href="./boardgroupmember_list.php?gr_id=' . $gr_id . '">' . $group_member_count . '</a>';
                        ?>
                    </td>
                </tr>
                <?php for ($i = 1; $i <= 10; $i++) { ?>
                    <tr>
                        <th scope="row">?щ텇?꾨뱶<?php echo $i ?></th>
                        <td class="td_extra">
                            <label for="gr_<?php echo $i ?>_subj">?щ텇?꾨뱶 <?php echo $i ?> ?쒕ぉ</label>
                            <input type="text" name="gr_<?php echo $i ?>_subj" value="<?php echo isset($group['gr_' . $i . '_subj']) ? get_text($group['gr_' . $i . '_subj']) : ''; ?>" id="gr_<?php echo $i ?>_subj" class="frm_input">
                            <label for="gr_<?php echo $i ?>">?щ텇?꾨뱶 <?php echo $i ?> ?댁슜</label>
                            <input type="text" name="gr_<?php echo $i ?>" value="<?php echo isset($gr['gr_' . $i]) ? get_sanitize_input($gr['gr_' . $i]) : ''; ?>" id="gr_<?php echo $i ?>" class="frm_input">
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <a href="./boardgroup_list.php?<?php echo $qstr ?>" class="btn btn_02">紐⑸줉</a>
        <input type="submit" class="btn_submit btn" accesskey="s" value="?뺤씤">
    </div>

</form>

<div class="local_desc01 local_desc">
    <p>
        寃뚯떆?먯쓣 ?앹꽦?섏떆?ㅻ㈃ 1媛??댁긽??寃뚯떆?먭렇猷뱀씠 ?꾩슂?⑸땲??<br>
        寃뚯떆?먭렇猷뱀쓣 ?댁슜?섏떆硫????④낵?곸쑝濡?寃뚯떆?먯쓣 愿由ы븷 ???덉뒿?덈떎.
    </p>
</div>

<script>
    function fboardgroup_check(f) {
        f.action = './boardgroup_form_update.php';
        return true;
    }
</script>

<?php
require_once './admin.tail.php';
