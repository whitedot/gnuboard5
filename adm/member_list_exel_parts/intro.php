<section class="card mb-5">
    <div class="card-header">
        <h2 class="card-title">회원 엑셀 생성</h2>
    </div>
    <div class="card-body space-y-3 text-sm leading-6 text-default-700">
        <?php foreach ($member_export_view['intro_items'] as $item) { ?>
            <p><?php echo $item['html']; ?></p>
        <?php } ?>
    </div>
</section>

<div class="mb-5 inline-flex items-center gap-2 rounded-lg border border-default-300 bg-card px-4 py-2 text-sm font-medium text-default-700 shadow-sm">
    <span>총건수</span>
    <?php if ($member_export_view['total_error'] != "") { ?>
    <span class="text-danger"><?php echo $member_export_view['total_error'] ?></span>
    <?php } else { ?>
    <span><?php echo number_format($member_export_view['total_count']) ?>건</span>
    <?php } ?>
</div>

<?php if (!$member_export_view['environment_ready']) { ?>
<div class="mb-5 rounded-lg border border-danger/30 bg-danger/5 px-4 py-3 text-sm leading-6 text-danger">
    <strong>내보내기 실행 환경 확인 필요</strong>
    <p class="mt-1"><?php echo $member_export_view['environment_error']; ?></p>
</div>
<?php } ?>
