<?php
$sub_menu = '100000';
require_once './_common.php';

$g5['title'] = '관리자메인';
$admin_container_class = 'admin-page-dashboard';
$admin_page_subtitle = '신규 가입 회원 현황을 빠르게 확인하고 회원 관리 화면으로 자연스럽게 이어가세요.';
require_once './admin.head.php';

$new_member_rows = 5;
$addtional_content_before = run_replace('adm_index_addtional_content_before', '', $is_admin, $auth, $member);
if ($addtional_content_before) {
    echo $addtional_content_before;
}

if (!auth_check_menu($auth, '200100', 'r', true)) {
    $allowed_sort_fields = array(
        'mb_datetime',
        'mb_id',
        'mb_name',
        'mb_nick',
        'mb_level',
        'mb_mailling',
        'mb_open',
        'mb_email_certify',
        'mb_intercept_date',
    );
    $sort_field = (isset($sst) && in_array($sst, $allowed_sort_fields, true)) ? $sst : 'mb_datetime';
    $sort_direction = (isset($sod) && strtolower((string) $sod) === 'asc') ? 'asc' : 'desc';

    $sql_common = " from {$g5['member_table']} ";
    $conditions = array('(1)');
    $query_params = array();

    if ($is_admin != 'super') {
        $conditions[] = 'mb_level <= :admin_mb_level';
        $query_params['admin_mb_level'] = (int) $member['mb_level'];
    }

    $sql_search = ' where ' . implode(' and ', $conditions);
    $sql_order = " order by {$sort_field} {$sort_direction} ";

    $sql = " SELECT count(*) as cnt {$sql_common} {$sql_search} ";
    $total_count = (int) sql_fetch_value_prepared($sql, $query_params);

    // 탈퇴회원수
    $sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' ";
    $leave_count = (int) sql_fetch_value_prepared($sql, $query_params);

    // 차단회원수
    $sql = " SELECT count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' ";
    $intercept_count = (int) sql_fetch_value_prepared($sql, $query_params);

    $sql = " SELECT * {$sql_common} {$sql_search} {$sql_order} limit :new_member_rows ";
    $list_params = $query_params;
    $list_params['new_member_rows'] = (int) $new_member_rows;
    $result = sql_query_prepared($sql, $list_params);

    $colspan = 8;
    ?>

    <section class="card admin-dashboard-card">
        <div class="card-header">
            <div class="admin-dashboard-intro">
                <h2 class="card-title">신규가입회원 <?php echo $new_member_rows ?>건 목록</h2>
                <p class="admin-dashboard-meta">최근 가입한 회원을 빠르게 확인하고 필요한 관리 작업으로 이어질 수 있도록 구성했습니다.</p>
            </div>
            <a href="./member_list.php" class="btn btn-sm btn-surface-default-soft">회원 전체보기</a>
        </div>

        <div class="admin-dashboard-summary">
            <div class="admin-dashboard-stats">
                <span class="admin-dashboard-stat">총회원수 <strong><?php echo number_format($total_count) ?>명</strong></span>
                <span class="admin-dashboard-stat">차단 <strong><?php echo number_format($intercept_count) ?>명</strong></span>
                <span class="admin-dashboard-stat">탈퇴 <strong><?php echo number_format($leave_count) ?>명</strong></span>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <caption>신규가입회원</caption>
                <thead class="border-default-300 bg-default-100 border-b font-semibold text-xs">
                    <tr>
                        <th scope="col">회원아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col">닉네임</th>
                        <th scope="col">권한</th>
                        <th scope="col">수신</th>
                        <th scope="col">공개</th>
                        <th scope="col">인증</th>
                        <th scope="col">차단</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        $s_mod = '<a href="./member_form.php?$qstr&amp;w=u&amp;mb_id=' . $row['mb_id'] . '">수정</a>';
                        $s_del = '<a href="./member_delete.php?' . $qstr . '&amp;w=d&amp;mb_id=' . $row['mb_id'] . '&amp;url=' . $_SERVER['SCRIPT_NAME'] . '" onclick="return delete_confirm(this);">삭제</a>';

                        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date("Ymd", G5_SERVER_TIME);
                        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date("Ymd", G5_SERVER_TIME);

                        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], '');

                        $mb_id = $row['mb_id'];
                        ?>
                        <tr>
                            <td class="admin-dashboard-member-id"><?php echo $mb_id ?></td>
                            <td><?php echo get_text($row['mb_name']); ?></td>
                            <td>
                                <?php echo $mb_nick ?>
                            </td>
                            <td><?php echo $row['mb_level'] ?></td>
                            <td><?php echo $row['mb_mailling'] ? '예' : '아니오'; ?></td>
                            <td><?php echo $row['mb_open'] ? '예' : '아니오'; ?></td>
                            <td><?php echo preg_match('/[1-9]/', $row['mb_email_certify']) ? '예' : '아니오'; ?></td>
                            <td><?php echo $row['mb_intercept_date'] ? '예' : '아니오'; ?></td>
                        </tr>
                        <?php
                    }
                    if ($i == 0) {
                        echo '<tr><td colspan="' . $colspan . '" class="admin-dashboard-empty">자료가 없습니다.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php
} //endif

$addtional_content_after = run_replace('adm_index_addtional_content_after', '', $is_admin, $auth, $member);
if ($addtional_content_after) {
    echo $addtional_content_after;
}
require_once './admin.tail.php';
