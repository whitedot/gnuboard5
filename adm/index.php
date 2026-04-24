<?php
$sub_menu = '100000';
require_once './_common.php';

$dashboard_request = admin_read_dashboard_request($_GET);
$dashboard_view = admin_build_dashboard_page_view($dashboard_request, $member, $is_admin, $auth);

$g5['title'] = $dashboard_view['title'];
$admin_container_class = $dashboard_view['admin_container_class'];
$admin_page_subtitle = $dashboard_view['admin_page_subtitle'];
require_once './admin.head.php';

$addtional_content_before = run_replace('adm_index_addtional_content_before', '', $is_admin, $auth, $member);
if ($addtional_content_before) {
    echo $addtional_content_before;
}

if ($dashboard_view['can_read_member_menu']) {
    ?>

    <section class="card admin-dashboard-card">
        <div class="card-header">
            <div class="admin-dashboard-intro">
                <h2 class="card-title">신규가입회원 <?php echo $dashboard_view['new_member_rows'] ?>건 목록</h2>
                <p class="admin-dashboard-meta">최근 가입한 회원을 빠르게 확인하고 필요한 관리 작업으로 이어질 수 있도록 구성했습니다.</p>
            </div>
            <a href="./member_list.php" class="btn btn-sm btn-surface-default-soft">회원 전체보기</a>
        </div>

        <div class="admin-dashboard-summary">
            <div class="admin-dashboard-stats">
                <span class="admin-dashboard-stat">총회원수 <strong><?php echo number_format($dashboard_view['total_count']) ?>명</strong></span>
                <span class="admin-dashboard-stat">차단 <strong><?php echo number_format($dashboard_view['intercept_count']) ?>명</strong></span>
                <span class="admin-dashboard-stat">탈퇴 <strong><?php echo number_format($dashboard_view['leave_count']) ?>명</strong></span>
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
                    <?php foreach ($dashboard_view['items'] as $item) { ?>
                        <tr>
                            <td class="admin-dashboard-member-id"><?php echo $item['display_mb_id'] ?></td>
                            <td><?php echo $item['mb_name']; ?></td>
                            <td><?php echo $item['sideview_html']; ?></td>
                            <td><?php echo $item['mb_level'] ?></td>
                            <td><?php echo $item['mb_mailling']; ?></td>
                            <td><?php echo $item['mb_open']; ?></td>
                            <td><?php echo $item['mb_email_certify']; ?></td>
                            <td><?php echo $item['mb_intercept_date']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($dashboard_view['items'])) { ?>
                        <tr><td colspan="<?php echo $dashboard_view['colspan']; ?>" class="admin-dashboard-empty">자료가 없습니다.</td></tr>
                    <?php } ?>
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
