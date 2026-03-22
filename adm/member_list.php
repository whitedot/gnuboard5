<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_level':
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_hp':
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default:
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($is_admin != 'super') {
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
}

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
}
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="btn btn-surface-default-soft">전체 보기</a>';
$quick_view = 'all';
if ($sst === 'mb_intercept_date' && $sod === 'desc') {
    $quick_view = 'blocked';
} elseif ($sst === 'mb_leave_date' && $sod === 'desc') {
    $quick_view = 'left';
}

$g5['title'] = '회원관리';
$admin_container_class = 'admin-page-member-list';
$admin_page_subtitle = '회원 상태를 한눈에 확인하고, 조건 검색과 빠른 관리 동선을 자연스럽게 이어가세요.';
require_once './admin.head.php';

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 8;
$admin_token = get_admin_token();
?>

<style>
    .member-summary,
    .member-search-card,
    .member-notice {
        margin-bottom: 1rem;
    }

    .member-summary {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem 1rem;
    }

    .member-summary-links,
    .member-summary-stats {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    .member-summary-meta {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8125rem;
        color: var(--color-default-700);
    }

    .member-summary-meta strong {
        color: var(--color-default-900);
    }

    .member-search-card form {
        display: grid;
        gap: 0.875rem;
    }

    .member-search-fields {
        display: grid;
        gap: 0.875rem;
        grid-template-columns: minmax(0, 11rem) minmax(0, 1fr) auto;
        align-items: end;
    }

    .member-field {
        display: grid;
        gap: 0.45rem;
        min-width: 0;
    }

    .member-field label {
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--color-default-700);
    }

    .member-field select,
    .member-field input[type="text"] {
        width: 100%;
        min-height: 2.75rem;
        border: 1px solid var(--color-default-300);
        border-radius: 0;
        background: var(--color-card);
        padding: 0.65rem 0.8rem;
        color: var(--color-default-900);
    }

    .member-field select:focus,
    .member-field input[type="text"]:focus {
        border-color: color-mix(in oklab, var(--color-primary) 38%, var(--color-default-300));
        outline: none;
        box-shadow: 0 0 0 4px color-mix(in oklab, var(--color-primary) 12%, transparent);
    }

    .member-search-submit {
        min-height: 2.75rem;
        align-self: end;
        white-space: nowrap;
    }

    .member-notice {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr);
        align-items: flex-start;
        gap: 0.875rem;
        padding: 0.875rem 1rem;
        background: var(--color-default-50);
        border: 1px solid var(--color-default-300);
    }

    .member-notice-copy {
        display: grid;
        gap: 0.3rem;
    }

    .member-notice-copy strong,
    .member-notice-copy p {
        margin: 0;
    }

    .member-notice-copy p {
        color: var(--color-default-600);
    }

    .member-notice-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0;
        background: var(--color-card);
        border: 1px solid var(--color-default-300);
        color: var(--color-info-hover);
        font-size: 0.875rem;
        font-weight: 700;
    }

    .member-table-card {
        margin-bottom: 1rem;
        border: 1px solid var(--color-default-300);
        background: var(--color-card);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .member-table-card .table-wrapper {
        width: 100%;
        overflow: auto;
    }

    #fmemberlist .table {
        width: 100%;
        border-radius: 0 !important;
        border-collapse: collapse;
    }

    #fmemberlist .table thead th {
        border-bottom: 1px solid var(--color-default-300);
        background: var(--color-default-100);
        padding: 0.75rem 0.625rem;
        text-align: left;
        vertical-align: middle;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--color-default-700);
    }

    #fmemberlist .table tbody td {
        border-bottom: 1px solid var(--color-default-300);
        padding: 0.75rem 0.625rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    #fmemberlist .table tbody tr:last-child td {
        border-bottom: 0;
    }

    #fmemberlist .table :is(th, td):first-child {
        padding-left: 1rem;
    }

    #fmemberlist .table :is(th, td):last-child {
        padding-right: 1rem;
    }

    #fmemberlist .table thead th.text-end,
    #fmemberlist .table tbody td.text-end {
        text-align: right;
    }

    #fmemberlist .member-cell-fixed,
    #fmemberlist .member-cell-manage {
        white-space: nowrap;
    }

    #fmemberlist .member-cell-manage {
        min-width: 9.75rem;
    }

    #fmemberlist .member-cell-email {
        white-space: normal;
        word-break: break-all;
    }

    #fmemberlist .sv_wrap {
        position: static;
        display: inline-flex;
        align-items: center;
    }

    #fmemberlist .sv_member {
        display: inline-flex;
        align-items: center;
        padding: 0;
        border: 0;
        background: transparent;
        box-shadow: none;
        color: inherit;
        font: inherit;
        line-height: inherit;
        text-align: left;
        vertical-align: baseline;
        cursor: pointer;
    }

    #fmemberlist .sv_member:hover,
    #fmemberlist .sv_member:focus {
        color: var(--color-primary);
        text-decoration: underline;
        outline: none;
    }

    #fmemberlist .sv {
        z-index: 140;
        min-width: 8.5rem;
        width: max-content;
        max-width: 18rem;
        white-space: nowrap;
    }

    #fmemberlist .hs-dropdown.hs-dropdown-open .hs-dropdown-menu,
    #fmemberlist .hs-dropdown.open .hs-dropdown-menu {
        display: block;
        opacity: 1;
    }

    #fmemberlist .member-level {
        font-weight: 600;
        color: var(--color-default-700);
    }

    #fmemberlist .member-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        white-space: nowrap;
    }

    #fmemberlist .member-status.is-normal {
        background: color-mix(in oklab, var(--color-success) 14%, white);
        color: var(--color-success-hover);
    }

    #fmemberlist .member-status.is-blocked {
        background: color-mix(in oklab, var(--color-warning) 14%, white);
        color: var(--color-warning-hover);
    }

    #fmemberlist .member-status.is-left {
        background: color-mix(in oklab, var(--color-danger) 12%, white);
        color: var(--color-danger-hover);
    }

    #fmemberlist .member-manage {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.375rem;
        justify-content: flex-end;
        align-items: center;
        white-space: nowrap;
    }

    #fmemberlist .member-manage .btn {
        flex: 0 0 auto;
    }

    #fmemberlist .member-manage form {
        margin: 0;
    }

    .member-list-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 1rem;
        border-top: 1px solid var(--color-default-300);
        background: color-mix(in oklab, var(--color-default-50) 70%, white);
    }

    @media (max-width: 1023px) {
        .member-search-fields {
            grid-template-columns: 1fr;
        }

        .member-search-submit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 767px) {
        .member-summary {
            align-items: flex-start;
        }

        .member-notice {
            grid-template-columns: 1fr;
        }

        .member-list-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .member-list-actions > * {
            width: 100%;
        }

        .member-list-actions .btn {
            justify-content: center;
        }
    }
</style>

<div class="member-summary">
    <div class="member-summary-links">
        <?php echo $listall; ?>
    </div>

    <div class="member-summary-stats">
        <span class="member-summary-meta">총회원 <strong><?php echo number_format($total_count); ?>명</strong></span>
        <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="member-summary-meta"<?php echo $quick_view === 'blocked' ? ' aria-current="page"' : ''; ?>>차단 <?php echo number_format($intercept_count); ?>명</a>
        <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="member-summary-meta"<?php echo $quick_view === 'left' ? ' aria-current="page"' : ''; ?>>탈퇴 <?php echo number_format($leave_count); ?>명</a>
    </div>
</div>

<div class="member-search-card">
    <form id="fsearch" name="fsearch" method="get">
        <div class="member-search-fields">
            <div class="member-field">
                <label for="sfl">검색대상</label>
                <select name="sfl" id="sfl">
                    <option value="mb_id" <?php echo get_selected($sfl, "mb_id"); ?>>회원아이디</option>
                    <option value="mb_nick" <?php echo get_selected($sfl, "mb_nick"); ?>>닉네임</option>
                    <option value="mb_name" <?php echo get_selected($sfl, "mb_name"); ?>>이름</option>
                    <option value="mb_level" <?php echo get_selected($sfl, "mb_level"); ?>>권한</option>
                    <option value="mb_email" <?php echo get_selected($sfl, "mb_email"); ?>>E-MAIL</option>
                    <option value="mb_hp" <?php echo get_selected($sfl, "mb_hp"); ?>>휴대폰번호</option>
                    <option value="mb_datetime" <?php echo get_selected($sfl, "mb_datetime"); ?>>가입일시</option>
                    <option value="mb_ip" <?php echo get_selected($sfl, "mb_ip"); ?>>IP</option>
                </select>
            </div>
            <div class="member-field">
                <label for="stx">검색어</label>
                <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required" placeholder="검색어를 입력하세요">
            </div>
            <button type="submit" class="btn btn-solid-primary member-search-submit">검색</button>
        </div>
    </form>
</div>

<div class="member-notice">
    <span class="member-notice-icon" aria-hidden="true">i</span>
    <div class="member-notice-copy">
        <strong>회원 삭제 안내</strong>
        <p>회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.</p>
    </div>
</div>

<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo $admin_token ?>">

    <div class="member-table-card">
        <div class="table-wrapper">
            <table class="table">
                <caption><?php echo $g5['title']; ?> 목록</caption>
                <colgroup>
                    <col style="width: 3.5rem;">
                    <col style="width: 9rem;">
                    <col style="width: 8rem;">
                    <col style="width: 9rem;">
                    <col>
                    <col style="width: 6rem;">
                    <col style="width: 6rem;">
                    <col style="width: 10rem;">
                </colgroup>
                <thead class="border-default-300 bg-default-100 border-b font-semibold text-xs">
                    <tr>
                        <th scope="col" id="mb_list_chk">
                            <label for="chkall" class="sr-only">회원 전체</label>
                            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                        </th>
                        <th scope="col" id="mb_list_id"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
                        <th scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?>이름</a></th>
                        <th scope="col" id="mb_list_nick"><?php echo subject_sort_link('mb_nick') ?>닉네임</a></th>
                        <th scope="col" id="mb_list_email"><?php echo subject_sort_link('mb_email') ?>이메일 주소</a></th>
                        <th scope="col" id="mb_list_level"><?php echo subject_sort_link('mb_level', '', 'desc') ?>권한</a></th>
                        <th scope="col" id="mb_list_status">상태</th>
                        <th scope="col" id="mb_list_mng" class="text-end">관리</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], '');

                        $status_label = '정상';
                        $status_class = 'is-normal';
                        if ($row['mb_leave_date']) {
                            $status_label = '탈퇴';
                            $status_class = 'is-left';
                        } elseif ($row['mb_intercept_date']) {
                            $status_label = '차단';
                            $status_class = 'is-blocked';
                        }

                        $manage_links = [];
                        if ($is_admin != 'group') {
                            $manage_links[] = '<a href="./member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $row['mb_id'] . '" class="btn btn-sm btn-surface-default-soft">수정</a>';
                        }

                        if (
                            $member['mb_id'] != $row['mb_id']
                            && is_admin($row['mb_id']) != 'super'
                            && ($is_admin == 'super' || $row['mb_level'] < $member['mb_level'])
                        ) {
                            $manage_links[] = '<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMember(\'' . $row['mb_id'] . '\')">삭제</button>';
                        }

                    ?>
                        <tr>
                            <td headers="mb_list_chk" class="member-cell-fixed">
                                <input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>" id="mb_id_<?php echo $i; ?>">
                                <label for="chk_<?php echo $i; ?>" class="sr-only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
                                <input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
                            </td>
                            <td headers="mb_list_id" class="member-cell-fixed font-medium"><?php echo $row['mb_id']; ?></td>
                            <td headers="mb_list_name" class="member-cell-fixed"><?php echo get_text($row['mb_name']); ?></td>
                            <td headers="mb_list_nick" class="member-cell-fixed"><?php echo $mb_nick; ?></td>
                            <td headers="mb_list_email" class="member-cell-email"><?php echo get_text($row['mb_email']); ?></td>
                            <td headers="mb_list_level" class="member-cell-fixed">
                                <span class="member-level">Lv.<?php echo (int) $row['mb_level']; ?></span>
                            </td>
                            <td headers="mb_list_status" class="member-cell-fixed">
                                <span class="member-status <?php echo $status_class; ?>"><?php echo $status_label; ?></span>
                            </td>
                            <td headers="mb_list_mng" class="member-cell-manage text-end">
                                <div class="member-manage">
                                    <?php echo $manage_links ? implode('', $manage_links) : '<span>-</span>'; ?>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    if ($i == 0) {
                        echo "<tr><td colspan=\"" . $colspan . "\">자료가 없습니다.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="member-list-actions">
            <div class="flex items-center">
                <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-outline-danger">
            </div>
            <?php if ($is_admin == 'super') { ?>
                <a href="./member_form.php" id="member_add" class="btn btn-surface-default-soft">회원추가</a>
            <?php } ?>
        </div>
    </div>
</form>

<form id="member_delete_form" method="post" action="./member_delete.php" class="hidden">
    <input type="hidden" name="token" value="<?php echo $admin_token; ?>">
    <input type="hidden" name="mb_id" value="">
</form>

<?php echo get_paging(G5_ADMIN_PAGING_PAGES, $page, $total_page, '?' . $qstr . '&amp;page='); ?>

<script>
    function fmemberlist_submit(f) {
        if (!is_checked("chk[]")) {
            alert("선택삭제 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        return confirm("선택한 자료를 정말 삭제하시겠습니까?");
    }

    function deleteMember(mbId) {
        if (!confirm("이 회원을 삭제하시겠습니까?")) {
            return;
        }

        var form = document.getElementById('member_delete_form');
        if (!form) {
            return;
        }

        form.elements.mb_id.value = mbId;
        form.submit();
    }

    (function () {
        var container = document.getElementById('fmemberlist');
        if (!container) {
            return;
        }

        function placeSideview(dropdown) {
            if (!dropdown || !dropdown.classList.contains('hs-dropdown-open')) {
                return;
            }

            var toggle = dropdown.querySelector('.hs-dropdown-toggle');
            var menu = dropdown.querySelector('.hs-dropdown-menu');
            if (!toggle || !menu) {
                return;
            }

            menu.style.display = 'block';
            menu.style.position = 'fixed';
            menu.style.minWidth = '8.5rem';
            menu.style.maxWidth = '18rem';

            var toggleRect = toggle.getBoundingClientRect();
            var menuRect = menu.getBoundingClientRect();
            var viewportGap = 8;
            var top = Math.round(toggleRect.bottom + 10);
            var left = Math.round(toggleRect.left);

            if (left + menuRect.width > window.innerWidth - viewportGap) {
                left = Math.max(viewportGap, Math.round(toggleRect.right - menuRect.width));
            }

            if (top + menuRect.height > window.innerHeight - viewportGap) {
                var topAbove = Math.round(toggleRect.top - menuRect.height - 10);
                top = topAbove >= viewportGap
                    ? topAbove
                    : Math.max(viewportGap, window.innerHeight - menuRect.height - viewportGap);
            }

            menu.style.left = left + 'px';
            menu.style.top = top + 'px';
        }

        container.addEventListener('ui.dropdown.open', function (event) {
            placeSideview(event.target);
        });

        window.addEventListener('resize', function () {
            Array.prototype.forEach.call(
                container.querySelectorAll('.hs-dropdown.hs-dropdown-open'),
                placeSideview
            );
        });

        window.addEventListener('scroll', function () {
            Array.prototype.forEach.call(
                container.querySelectorAll('.hs-dropdown.hs-dropdown-open'),
                placeSideview
            );
        }, true);
    }());
</script>

<?php
require_once './admin.tail.php';
