<div class="member-summary">
    <div class="member-summary-links">
        <a href="<?php echo $member_list_view['list_all_url']; ?>" class="btn btn-surface-default-soft">전체 보기</a>
    </div>

    <div class="member-summary-stats">
        <span class="member-summary-meta">총회원 <strong><?php echo number_format($member_list_view['total_count']); ?>명</strong></span>
        <a href="<?php echo $member_list_view['blocked_url']; ?>" class="member-summary-meta"<?php echo $member_list_view['quick_view'] === 'blocked' ? ' aria-current="page"' : ''; ?>>차단 <?php echo number_format($member_list_view['intercept_count']); ?>명</a>
        <a href="<?php echo $member_list_view['left_url']; ?>" class="member-summary-meta"<?php echo $member_list_view['quick_view'] === 'left' ? ' aria-current="page"' : ''; ?>>탈퇴 <?php echo number_format($member_list_view['leave_count']); ?>명</a>
    </div>
</div>

<div class="member-notice">
    <span class="member-notice-icon" aria-hidden="true">i</span>
    <div class="member-notice-copy">
        <strong><?php echo $member_list_view['notice_title']; ?></strong>
        <p><?php echo $member_list_view['notice_body']; ?></p>
    </div>
</div>
