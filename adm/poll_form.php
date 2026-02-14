<?php
$sub_menu = "200900";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

$po_id = isset($po_id) ? (int) $po_id : 0;
$po = array(
    'po_subject' => '',
    'po_etc' => '',
    'po_level' => '',
    'po_point' => '',
);

$html_title = '?ы몴';
if ($w == '') {
    $html_title .= ' ?앹꽦';
} elseif ($w == 'u') {
    $html_title .= ' ?섏젙';
    $sql = " select * from {$g5['poll_table']} where po_id = '{$po_id}' ";
    $po = sql_fetch($sql);
} else {
    alert('w 媛믪씠 ?쒕?濡??섏뼱?ㅼ? ?딆븯?듬땲??');
}

$g5['title'] = $html_title;
require_once './admin.head.php';
?>

<form name="fpoll" id="fpoll" action="./poll_form_update.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="po_id" value="<?php echo $po_id ?>">
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
            <tbody>
                <tr>
                    <th scope="row"><label for="po_subject">?ы몴 ?쒕ぉ<strong class="sr-only">?꾩닔</strong></label></th>
                    <td><input type="text" name="po_subject" value="<?php echo get_sanitize_input($po['po_subject']); ?>" id="po_subject" required class="required frm_input" size="80" maxlength="125"></td>
                </tr>

                <?php
                for ($i = 1; $i <= 9; $i++) {
                    $required = '';
                    $sr_only = '';
                    if ($i == 1 || $i == 2) {
                        $required = 'required';
                        $sr_only = '<strong class="sr-only">?꾩닔</strong>';
                    }

                    $po_poll = isset($po['po_poll' . $i]) ? get_text($po['po_poll' . $i]) : '';
                    $po_cnt = isset($po['po_cnt' . $i]) ? get_text($po['po_cnt' . $i]) : 0;
                    ?>

                    <tr>
                        <th scope="row"><label for="po_poll<?php echo $i ?>">??ぉ <?php echo $i ?><?php echo $sr_only ?></label></th>
                        <td>
                            <input type="text" name="po_poll<?php echo $i ?>" value="<?php echo $po_poll ?>" id="po_poll<?php echo $i ?>" <?php echo $required ?> class="frm_input <?php echo $required ?>" maxlength="125">
                            <label for="po_cnt<?php echo $i ?>">??ぉ <?php echo $i ?> ?ы몴??/label>
                            <input type="text" name="po_cnt<?php echo $i ?>" value="<?php echo $po_cnt; ?>" id="po_cnt<?php echo $i ?>" class="frm_input" size="3">
                        </td>
                    </tr>

                <?php } ?>

                <tr>
                    <th scope="row"><label for="po_etc">湲고??섍껄</label></th>
                    <td>
                        <?php echo help('湲고? ?섍껄???④만 ???덈룄濡??섎젮硫? 媛꾨떒??吏덈Ц???낅젰?섏꽭??') ?>
                        <input type="text" name="po_etc" value="<?php echo get_text($po['po_etc']) ?>" id="po_etc" class="frm_input" size="80" maxlength="125">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="po_level">?ы몴媛???뚯썝?덈꺼</label></th>
                    <td>
                        <?php echo help("?덈꺼??1濡??ㅼ젙?섎㈃ ?먮떂???ы몴?????덉뒿?덈떎.") ?>
                        <?php echo get_member_level_select('po_level', 1, 10, $po['po_level']) ?> ?댁긽 ?ы몴?????덉쓬
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="po_point">?ъ씤??/label></th>
                    <td>
                        <?php echo help('?ы몴??李몄뿬???뚯썝?먭쾶 ?ъ씤?몃? 遺?ы빀?덈떎.') ?>
                        <input type="text" name="po_point" value="<?php echo $po['po_point'] ?>" id="po_point" class="frm_input"> ??
                    </td>
                </tr>

                <?php if ($w == 'u') { ?>
                    <tr>
                        <th scope="row">?ы몴?ъ슜</th>
                        <td><input type="checkbox" name="po_use" id="po_use" value="1" <?php if ($po['po_use']) { echo 'checked="checked"'; } ?>> <label for="po_use">?ъ슜</label></td>
                    </tr>
                    <tr>
                        <th scope="row">?ы몴?깅줉??/th>
                        <td><?php echo $po['po_date']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="po_ips">?ы몴李멸? IP</label></th>
                        <td><textarea name="po_ips" id="po_ips" readonly rows="10"><?php echo html_purifier(preg_replace("/\n/", " / ", $po['po_ips'])); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mb_ids">?ы몴李멸? ?뚯썝</label></th>
                        <td><textarea name="mb_ids" id="mb_ids" readonly rows="10"><?php echo html_purifier(preg_replace("/\n/", " / ", $po['mb_ids'])); ?></textarea></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

    <div class="btn_fixed_top ">
        <a href="./poll_list.php?<?php echo $qstr ?>" class="btn_02 btn">紐⑸줉</a>
        <input type="submit" value="?뺤씤" class="btn_submit btn" accesskey="s">
    </div>

</form>

<?php
require_once './admin.tail.php';
