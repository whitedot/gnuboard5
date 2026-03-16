<section id="anc_mb_profile" class="card">
    <div class="card-header">
        <h2 class="card-title">관리 메모</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="mb_memo" class="form-label">메모</label>
                </div>
                <div class="af-field">
                    <textarea name="mb_memo" id="mb_memo" class="form-textarea"><?php echo html_purifier($mb['mb_memo']); ?></textarea>
                </div>
            </div>
        </div>
    </div>
</section>
