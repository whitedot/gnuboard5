<?php
$sub_menu = "300100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from {$g5['board_table']} a ";
$sql_search = " where (1) ";

if ($is_admin != "super") {
    $sql_common .= " , {$g5['group_table']} b ";
    $sql_search .= " and (a.gr_id = b.gr_id and b.gr_admin = '{$member['mb_id']}') ";
}

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "bo_table":
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.gr_id":
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default:
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "a.gr_id, a.bo_table";
    $sod = "asc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
}
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '">전체목록</a>';

$g5['title'] = '게시판관리';
require_once './admin.head.php';

$colspan = 15;
?>

<div>
    <?php echo $listall ?>
    <span><span>생성된 게시판수</span><span> <?php echo number_format($total_count) ?>개</span></span>
</div>

<form name="fsearch" id="fsearch" method="get">
    <label for="sfl">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="bo_table" <?php echo get_selected($sfl, "bo_table", true); ?>>TABLE</option>
        <option value="bo_subject" <?php echo get_selected($sfl, "bo_subject"); ?>>제목</option>
        <option value="a.gr_id" <?php echo get_selected($sfl, "a.gr_id"); ?>>그룹ID</option>
    </select>
    <label for="stx">검색어<strong> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required">
    <input type="submit" value="검색">
</form>

<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo isset($token) ? $token : ''; ?>">

    <div>
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
                <tr>
                    <th scope="col">
                        <label for="chkall">게시판 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                    <th scope="col"><?php echo subject_sort_link('a.gr_id') ?>그룹</a></th>
                    <th scope="col"><?php echo subject_sort_link('bo_table') ?>TABLE</a></th>
                    <th scope="col"><?php echo subject_sort_link('bo_skin', '', 'desc') ?>스킨</a></th>
                    <th scope="col"><?php echo subject_sort_link('bo_subject') ?>제목</a></th>
                    <th scope="col">읽기P<span>포인트</span></th>
                    <th scope="col">쓰기P<span>포인트</span></th>
                    <th scope="col">댓글P<span>포인트</span></th>
                    <th scope="col">다운P<span>포인트</span></th>
                    <th scope="col"><?php echo subject_sort_link('bo_use_sns') ?>SNS<br>사용</a></th>
                    <th scope="col"><?php echo subject_sort_link('bo_use_search') ?>검색<br>사용</a></th>
                    <th scope="col"><?php echo subject_sort_link('bo_order') ?>출력<br>순서</a></th>
                    <th scope="col">접속기기</th>
                    <th scope="col">관리</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    $one_update = '<a href="./board_form.php?w=u&amp;bo_table=' . $row['bo_table'] . '&amp;' . $qstr . '">수정</a>';
                    $one_copy = '<a href="./board_copy.php?bo_table=' . $row['bo_table'] . '" class="board_copy" target="win_board_copy">복사</a>';

                    $bg = 'bg' . ($i % 2);
                ?>

                    <tr class="<?php echo $bg; ?>">
                        <td>
                            <label for="chk_<?php echo $i; ?>"><?php echo get_text($row['bo_subject']) ?></label>
                            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        </td>
                        <td>
                            <?php if ($is_admin == 'super') { ?>
                                <?php echo get_group_select("gr_id[$i]", $row['gr_id']) ?>
                            <?php } else { ?>
                                <input type="hidden" name="gr_id[<?php echo $i ?>]" value="<?php echo $row['gr_id'] ?>"><?php echo $row['gr_subject'] ?>
                            <?php } ?>
                        </td>
                        <td>
                            <input type="hidden" name="board_table[<?php echo $i ?>]" value="<?php echo $row['bo_table'] ?>">
                            <a href="<?php echo get_pretty_url($row['bo_table']) ?>"><?php echo $row['bo_table'] ?></a>
                        </td>
                        <td>
                            <label for="bo_skin_<?php echo $i; ?>">스킨</label>
                            <?php echo get_skin_select('board', 'bo_skin_' . $i, "bo_skin[$i]", $row['bo_skin']); ?>
                        </td>
                        <td>
                            <label for="bo_subject_<?php echo $i; ?>">게시판 제목<strong> 필수</strong></label>
                            <input type="text" name="bo_subject[<?php echo $i ?>]" value="<?php echo get_text($row['bo_subject']) ?>" id="bo_subject_<?php echo $i ?>" required class="required" size="10">
                        </td>
                        <td>
                            <label for="bo_read_point_<?php echo $i; ?>">읽기 포인트</label>
                            <input type="text" name="bo_read_point[<?php echo $i ?>]" value="<?php echo $row['bo_read_point'] ?>" id="bo_read_point_<?php echo $i; ?>" size="2">
                        </td>
                        <td>
                            <label for="bo_write_point_<?php echo $i; ?>">쓰기 포인트</label>
                            <input type="text" name="bo_write_point[<?php echo $i ?>]" value="<?php echo $row['bo_write_point'] ?>" id="bo_write_point_<?php echo $i; ?>" size="2">
                        </td>
                        <td>
                            <label for="bo_comment_point_<?php echo $i; ?>">댓글 포인트</label>
                            <input type="text" name="bo_comment_point[<?php echo $i ?>]" value="<?php echo $row['bo_comment_point'] ?>" id="bo_comment_point_<?php echo $i; ?>" size="2">
                        </td>
                        <td>
                            <label for="bo_download_point_<?php echo $i; ?>">다운<br>포인트</label>
                            <input type="text" name="bo_download_point[<?php echo $i ?>]" value="<?php echo $row['bo_download_point'] ?>" id="bo_download_point_<?php echo $i; ?>" size="2">
                        </td>
                        <td>
                            <label for="bo_use_sns_<?php echo $i; ?>">SNS<br>사용</label>
                            <input type="checkbox" name="bo_use_sns[<?php echo $i ?>]" value="1" id="bo_use_sns_<?php echo $i ?>" <?php echo $row['bo_use_sns'] ? "checked" : "" ?>>
                        </td>
                        <td>
                            <label for="bo_use_search_<?php echo $i; ?>">검색<br>사용</label>
                            <input type="checkbox" name="bo_use_search[<?php echo $i ?>]" value="1" id="bo_use_search_<?php echo $i ?>" <?php echo $row['bo_use_search'] ? "checked" : "" ?>>
                        </td>
                        <td>
                            <label for="bo_order_<?php echo $i; ?>">출력<br>순서</label>
                            <input type="text" name="bo_order[<?php echo $i ?>]" value="<?php echo $row['bo_order'] ?>" id="bo_order_<?php echo $i ?>" size="2">
                        </td>
                        <td class="cell-mngsmall">
                            <label for="bo_device_<?php echo $i; ?>">접속기기</label>
                            <select name="bo_device[<?php echo $i ?>]" id="bo_device_<?php echo $i ?>">
                                <option value="both" <?php echo get_selected($row['bo_device'], 'both', true); ?>>모두</option>
                                <option value="pc" <?php echo get_selected($row['bo_device'], 'pc'); ?>>PC</option>
                                <option value="mobile" <?php echo get_selected($row['bo_device'], 'mobile'); ?>>모바일</option>
                            </select>
                        </td>
                        <td>
                            <?php echo $one_update ?>
                            <?php echo $one_copy ?>
                        </td>
                    </tr>
                <?php
                }
                if ($i == 0) {
                    echo '<tr><td colspan="' . $colspan . '">자료가 없습니다.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div>
        <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
        <?php if ($is_admin == 'super') { ?>
            <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
            <a href="./board_form.php" id="bo_add">게시판 추가</a>
        <?php } ?>
    </div>

</form>

<?php echo get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='); ?>

<script>
    function fboardlist_submit(f) {
        if (!is_checked("chk[]")) {
            alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if (document.pressed == "선택삭제") {
            if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
        }

        return true;
    }

    $(function() {
        $(".board_copy").click(function() {
            window.open(this.href, "win_board_copy", "left=100,top=100,width=550,height=450");
            return false;
        });
    });
</script>

<?php
require_once './admin.tail.php';


