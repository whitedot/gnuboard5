<section id="anc_mb_profile" class="card">
    <div class="card-header">
        <h2 class="card-title">프로필 및 메모</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="mb_signature" class="form-label">서명</label>
                </div>
                <div class="af-field">
                    <textarea name="mb_signature" id="mb_signature" class="form-textarea"><?php echo html_purifier($mb['mb_signature']); ?></textarea>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="mb_profile" class="form-label">자기 소개</label>
                </div>
                <div class="af-field">
                    <textarea name="mb_profile" id="mb_profile" class="form-textarea"><?php echo html_purifier($mb['mb_profile']); ?></textarea>
                </div>
            </div>

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
