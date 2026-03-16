<?php
$sub_menu = '100000';
require_once './_common.php';

@require_once './safe_check.php';

if (function_exists('social_log_file_delete')) {
    //소셜로그인 디버그 파일 24시간 지난것은 삭제
    social_log_file_delete(86400);
}

$g5['title'] = '관리자메인';
require_once './admin.head.php';

$new_member_rows = 5;
$new_point_rows = 5;
$addtional_content_before = run_replace('adm_index_addtional_content_before', '', $is_admin, $auth, $member);
if ($addtional_content_before) {
    echo $addtional_content_before;
}

if (!auth_check_menu($auth, '200100', 'r', true)) {
    $sql_common = " from {$g5['member_table']} ";

    $sql_search = " where (1) ";

    if ($is_admin != 'super') {
        $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
    }

    if (!$sst) {
        $sst = "mb_datetime";
        $sod = "desc";
    }

    $sql_order = " order by {$sst} {$sod} ";

    $sql = " SELECT count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    // 탈퇴회원수
    $sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
    $row = sql_fetch($sql);
    $leave_count = $row['cnt'];

    // 차단회원수
    $sql = " SELECT count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
    $row = sql_fetch($sql);
    $intercept_count = $row['cnt'];

    $sql = " SELECT * {$sql_common} {$sql_search} {$sql_order} limit {$new_member_rows} ";
    $result = sql_query($sql);

    $colspan = 12;
    ?>

    <section>
        <h2>신규가입회원 <?php echo $new_member_rows ?>건 목록</h2>
        <div>
            총회원수 <?php echo number_format($total_count) ?>명 중 차단 <?php echo number_format($intercept_count) ?>명, 탈퇴 : <?php echo number_format($leave_count) ?>명
        </div>

        
            <table>
                <caption>신규가입회원</caption>
                <thead>
                    <tr>
                        <th scope="col">회원아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col">닉네임</th>
                        <th scope="col">권한</th>
                        <th scope="col">포인트</th>
                        <th scope="col">수신</th>
                        <th scope="col">공개</th>
                        <th scope="col">인증</th>
                        <th scope="col">차단</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        if ($is_admin == 'group') {
                            $s_mod = '';
                            $s_del = '';
                        } else {
                            $s_mod = '<a href="./member_form.php?$qstr&amp;w=u&amp;mb_id=' . $row['mb_id'] . '">수정</a>';
                            $s_del = '<a href="./member_delete.php?' . $qstr . '&amp;w=d&amp;mb_id=' . $row['mb_id'] . '&amp;url=' . $_SERVER['SCRIPT_NAME'] . '" onclick="return delete_confirm(this);">삭제</a>';
                        }

                        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date("Ymd", G5_SERVER_TIME);
                        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date("Ymd", G5_SERVER_TIME);

                        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

                        $mb_id = $row['mb_id'];
                        ?>
                        <tr>
                            <td><?php echo $mb_id ?></td>
                            <td><?php echo get_text($row['mb_name']); ?></td>
                            <td>
                                <?php echo $mb_nick ?>
                            </td>
                            <td><?php echo $row['mb_level'] ?></td>
                            <td><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td>
                            <td><?php echo $row['mb_mailling'] ? '예' : '아니오'; ?></td>
                            <td><?php echo $row['mb_open'] ? '예' : '아니오'; ?></td>
                            <td><?php echo preg_match('/[1-9]/', $row['mb_email_certify']) ? '예' : '아니오'; ?></td>
                            <td><?php echo $row['mb_intercept_date'] ? '예' : '아니오'; ?></td>
                        </tr>
                        <?php
                    }
                    if ($i == 0) {
                        echo '<tr><td colspan="' . $colspan . '">자료가 없습니다.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        

        
            <a href="./member_list.php">회원 전체보기</a>
        
    </section>

    <?php
} //endif 최신 회원

if (!auth_check_menu($auth, '200200', 'r', true)) {

    $sql_common = " from {$g5['point_table']} ";
    $sql_search = " where (1) ";
    $sql_order = " order by po_id desc ";

    $sql = " SELECT count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $sql = " SELECT * {$sql_common} {$sql_search} {$sql_order} limit {$new_point_rows} ";
    $result = sql_query($sql);

    $colspan = 7;
    ?>

    <section>
        <h2>최근 포인트 발생내역</h2>
        <div>
            전체 <?php echo number_format($total_count) ?> 건 중 <?php echo $new_point_rows ?>건 목록
        </div>

        
            <table>
                <caption>최근 포인트 발생내역</caption>
                <thead>
                    <tr>
                        <th scope="col">회원아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col">닉네임</th>
                        <th scope="col">일시</th>
                        <th scope="col">포인트 내용</th>
                        <th scope="col">포인트</th>
                        <th scope="col">포인트합</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $row2['mb_id'] = '';
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        if ($row2['mb_id'] != $row['mb_id']) {
                            $sql2 = " SELECT mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_point from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
                            $row2 = sql_fetch($sql2);
                        }

                        $mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

                        $link1 = $link2 = "";
                        if ((!defined('G5_MEMBER_ONLY') || !G5_MEMBER_ONLY) && !preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table']) {
                            $link1 = '<a href="' . get_pretty_url($row['po_rel_table'], $row['po_rel_id']) . '" target="_blank">';
                            $link2 = '</a>';
                        }
                        ?>

                        <tr>
                            <td><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
                            <td><?php echo get_text($row2['mb_name']); ?></td>
                            <td>
                                <?php echo $mb_nick ?>
                            </td>
                            <td><?php echo $row['po_datetime'] ?></td>
                            <td><?php echo $link1 . $row['po_content'] . $link2 ?></td>
                            <td><?php echo number_format($row['po_point']) ?></td>
                            <td><?php echo number_format($row['po_mb_point']) ?></td>
                        </tr>

                        <?php
                    }

                    if ($i == 0) {
                        echo '<tr><td colspan="' . $colspan . '">자료가 없습니다.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        

        
            <a href="./point_list.php">포인트내역 전체보기</a>
        
    </section>

    <?php
} //endif

$addtional_content_after = run_replace('adm_index_addtional_content_after', '', $is_admin, $auth, $member);
if ($addtional_content_after) {
    echo $addtional_content_after;
}
require_once './admin.tail.php';
