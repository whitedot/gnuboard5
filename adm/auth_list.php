<?php
$sub_menu = "100200";
require_once './_common.php';

if ($is_admin != 'super') {
    alert('理쒓퀬愿由ъ옄留??묎렐 媛?ν빀?덈떎.');
}

$sql_common = " from {$g5['auth_table']} a left join {$g5['member_table']} b on (a.mb_id=b.mb_id) ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default:
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "a.mb_id, au_menu";
    $sod = "";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // ?꾩껜 ?섏씠吏 怨꾩궛
if ($page < 1) {
    $page = 1; // ?섏씠吏媛 ?놁쑝硫?泥??섏씠吏 (1 ?섏씠吏)
}
$from_record = ($page - 1) * $rows; // ?쒖옉 ?댁쓣 援ы븿

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall btn_ov02">?꾩껜紐⑸줉</a>';

$g5['title'] = 'Auth List';
require_once './admin.head.php';

$colspan = 5;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">?ㅼ젙??愿由ш텒??/span><span class="ov_num"><?php echo number_format($total_count) ?>嫄?/span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
    <input type="hidden" name="sfl" value="a.mb_id" id="sfl">

    <label for="stx" class="sr-only">?뚯썝?꾩씠??strong class="sr-only"> ?꾩닔</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
    <input type="submit" value="寃?? id="fsearch_submit" class="btn_submit">

</form>

<form name="fauthlist" id="fauthlist" method="post" action="./auth_list_delete.php" onsubmit="return fauthlist_submit(this);">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 紐⑸줉</caption>
            <thead>
                <tr>
                    <th scope="col">
                        <label for="chkall" class="sr-only">?꾩옱 ?섏씠吏 ?뚯썝 ?꾩껜</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                    <th scope="col"><?php echo subject_sort_link('a.mb_id') ?>?뚯썝?꾩씠??/a></th>
                    <th scope="col"><?php echo subject_sort_link('mb_nick') ?>?됰꽕??/a></th>
                    <th scope="col">硫붾돱</th>
                    <th scope="col">沅뚰븳</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 0;
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    $is_continue = false;
                    // ?뚯썝?꾩씠?붽? ?녿뒗 硫붾돱????젣??
                    if ($row['mb_id'] == '' && $row['mb_nick'] == '') {
                        sql_query(" delete from {$g5['auth_table']} where au_menu = '{$row['au_menu']}' ");
                        $is_continue = true;
                    }

                    // 硫붾돱踰덊샇媛 諛붾뚮뒗 寃쎌슦???꾩옱 ?녿뒗 ??λ맂 硫붾돱????젣??
                    if (!isset($auth_menu[$row['au_menu']])) {
                        sql_query(" delete from {$g5['auth_table']} where au_menu = '{$row['au_menu']}' ");
                        $is_continue = true;
                    }

                    if ($is_continue) {
                        continue;
                    }

                    $mb_nick = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);

                    $bg = 'bg' . ($i % 2);
                ?>
                    <tr class="<?php echo $bg; ?>">
                        <td class="td_chk">
                            <input type="hidden" name="au_menu[<?php echo $i ?>]" value="<?php echo $row['au_menu'] ?>">
                            <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>">
                            <label for="chk_<?php echo $i; ?>" class="sr-only"><?php echo $row['mb_nick'] ?>??沅뚰븳</label>
                            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        </td>
                        <td class="td_mbid"><a href="?sfl=a.mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
                        <td class="td_auth_mbnick"><?php echo $mb_nick ?></td>
                        <td class="td_menu">
                            <?php echo $row['au_menu'] ?>
                            <?php echo $auth_menu[$row['au_menu']] ?>
                        </td>
                        <td class="td_auth"><?php echo $row['au_auth'] ?></td>
                    </tr>
                <?php
                    $count++;
                }

                if ($count == 0) {
                    echo '<tr><td colspan="' . $colspan . '" class="empty_table">?먮즺媛 ?놁뒿?덈떎.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="btn_list01 btn_list">
        <input type="submit" name="act_button" value="?좏깮??젣" onclick="document.pressed=this.value" class="btn btn_02">
    </div>

    <?php
    //if (isset($stx))
    //    echo '<script>document.fsearch.sfl.value = "'.$sfl.'";</script>'."\n";

    if (strstr($sfl, 'mb_id')) {
        $mb_id = $stx;
    } else {
        $mb_id = '';
    }
    ?>
</form>

<?php
$pagelist = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
echo $pagelist;
?>

<form name="fauthlist2" id="fauthlist2" action="./auth_update.php" method="post" autocomplete="off" onsubmit="return fauth_add_submit(this);">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <section id="add_admin">
        <h2 class="h2_frm">愿由ш텒??異붽?</h2>

        <div class="local_desc01 local_desc">
            <p>
                ?ㅼ쓬 ?묒떇?먯꽌 ?뚯썝?먭쾶 愿由ш텒?쒖쓣 遺?ы븯?????덉뒿?덈떎.<br>
                沅뚰븳 <strong>r</strong>? ?쎄린沅뚰븳, <strong>w</strong>???곌린沅뚰븳, <strong>d</strong>????젣沅뚰븳?낅땲??
            </p>
        </div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="row"><label for="mb_id">?뚯썝?꾩씠??strong class="sr-only">?꾩닔</strong></label></th>
                        <td>
                            <strong id="msg_mb_id" class="sr-only"></strong>
                            <input type="text" name="mb_id" value="<?php echo $mb_id ?>" id="mb_id" required class="required frm_input">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="au_menu">?묎렐媛?λ찓??strong class="sr-only">?꾩닔</strong></label></th>
                        <td>
                            <select id="au_menu" name="au_menu" required class="required">
                                <option value=''>?좏깮?섏꽭??/option>
                                <?php
                                foreach ($auth_menu as $key => $value) {
                                    if (!(substr($key, -3) == '000' || $key == '-' || !$key)) {
                                        echo '<option value="' . $key . '">' . $key . ' ' . $value . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">沅뚰븳吏??/th>
                        <td>
                            <input type="checkbox" name="r" value="r" id="r" checked>
                            <label for="r">r (?쎄린)</label>
                            <input type="checkbox" name="w" value="w" id="w">
                            <label for="w">w (?곌린)</label>
                            <input type="checkbox" name="d" value="d" id="d">
                            <label for="d">d (??젣)</label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">?먮룞?깅줉諛⑹?</th>
                        <td>
                            <?php
                            require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
                            $captcha_html = captcha_html();
                            $captcha_js   = chk_captcha_js();
                            echo $captcha_html;
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn_confirm01 btn_confirm">
            <input type="submit" value="異붽?" class="btn_submit btn">
        </div>
    </section>

</form>

<script>
    function fauth_add_submit(f) {

        <?php echo $captcha_js; // 罹≪콬 ?ъ슜???먮컮?ㅽ겕由쏀듃?먯꽌 ?낅젰??罹≪콬瑜?寃?ы븿 ?>

        return true;
    }

    function fauthlist_submit(f) {
        if (!is_checked("chk[]")) {
            alert(document.pressed + " ?섏떎 ??ぉ???섎굹 ?댁긽 ?좏깮?섏꽭??");
            return false;
        }

        if (document.pressed == "?좏깮??젣") {
            if (!confirm("?좏깮???먮즺瑜??뺣쭚 ??젣?섏떆寃좎뒿?덇퉴?")) {
                return false;
            }
        }

        return true;
    }
</script>

<?php
require_once './admin.tail.php';
