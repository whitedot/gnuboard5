<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$member_list_request = admin_read_member_list_request(array(
    'sfl' => isset($sfl) ? $sfl : '',
    'stx' => isset($stx) ? $stx : '',
    'sst' => isset($sst) ? $sst : '',
    'sod' => isset($sod) ? $sod : '',
    'page' => isset($page) ? $page : 1,
), $config);
$member_list_view = admin_build_member_list_page_view($member_list_request, $member, $is_admin, $config, $qstr);
extract($member_list_view, EXTR_SKIP);
extract($member_list_request, EXTR_SKIP);

$g5['title'] = $title;
require_once './admin.head.php';
?>

<div class="member-summary">
    <div class="member-summary-links">
        <?php echo $listall; ?>
    </div>

    <div class="member-summary-stats">
        <span class="member-summary-meta">총회원 <strong><?php echo number_format($total_count); ?>명</strong></span>
        <a href="<?php echo $blocked_url; ?>" class="member-summary-meta"<?php echo $quick_view === 'blocked' ? ' aria-current="page"' : ''; ?>>차단 <?php echo number_format($intercept_count); ?>명</a>
        <a href="<?php echo $left_url; ?>" class="member-summary-meta"<?php echo $quick_view === 'left' ? ' aria-current="page"' : ''; ?>>탈퇴 <?php echo number_format($leave_count); ?>명</a>
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
                    <?php foreach ($items as $index => $item) { ?>
                        <tr>
                            <td headers="mb_list_chk" class="member-cell-fixed">
                                <input type="hidden" name="mb_id[<?php echo $index; ?>]" value="<?php echo $item['mb_id']; ?>" id="mb_id_<?php echo $index; ?>">
                                <label for="chk_<?php echo $index; ?>" class="sr-only"><?php echo $item['mb_name']; ?> <?php echo strip_tags($item['mb_nick']); ?>님</label>
                                <input type="checkbox" name="chk[]" value="<?php echo $index; ?>" id="chk_<?php echo $index; ?>">
                            </td>
                            <td headers="mb_list_id" class="member-cell-fixed font-medium"><?php echo $item['mb_id']; ?></td>
                            <td headers="mb_list_name" class="member-cell-fixed"><?php echo $item['mb_name']; ?></td>
                            <td headers="mb_list_nick" class="member-cell-fixed"><?php echo $item['mb_nick']; ?></td>
                            <td headers="mb_list_email" class="member-cell-email"><?php echo $item['mb_email']; ?></td>
                            <td headers="mb_list_level" class="member-cell-fixed">
                                <span class="member-level">Lv.<?php echo $item['mb_level']; ?></span>
                            </td>
                            <td headers="mb_list_status" class="member-cell-fixed">
                                <span class="member-status <?php echo $item['status_class']; ?>"><?php echo $item['status_label']; ?></span>
                            </td>
                            <td headers="mb_list_mng" class="member-cell-manage text-end">
                                <div class="member-manage">
                                    <?php echo $item['manage_links'] ? implode('', $item['manage_links']) : '<span>-</span>'; ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($items)) { ?>
                        <tr><td colspan="<?php echo $colspan; ?>">자료가 없습니다.</td></tr>
                    <?php } ?>
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

<?php echo get_paging(G5_ADMIN_PAGING_PAGES, $page, $total_page, $paging_url); ?>

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
