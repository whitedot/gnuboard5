<section id="anc_cf_mail" class="card">
    <div class="card-header">
        <h2 class="card-title">기본 메일 환경 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_use" class="form-label">메일발송 사용</label>
                </div>
                <div class="af-field">
                    <?php echo help('체크하지 않으면 메일발송을 아예 사용하지 않습니다. 메일 테스트도 불가합니다.') ?>
                    <label for="cf_email_use" class="af-check form-label">
                        <input type="checkbox" name="cf_email_use" value="1" id="cf_email_use" <?php echo $config['cf_email_use'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_use_email_certify" class="form-label">메일인증 사용</label>
                </div>
                <div class="af-field">
                    <?php $tmp = !(defined('G5_SOCIAL_CERTIFY_MAIL') && G5_SOCIAL_CERTIFY_MAIL) ? '<br>( SNS를 이용한 소셜로그인 한 회원은 회원메일인증을 하지 않습니다. 일반회원에게만 해당됩니다. )' : ''; ?>
                    <?php echo help('메일에 배달된 인증 주소를 클릭하여야 회원으로 인정합니다.' . $tmp); ?>
                    <label for="cf_use_email_certify" class="af-check form-label">
                        <input type="checkbox" name="cf_use_email_certify" value="1" id="cf_use_email_certify" <?php echo $config['cf_use_email_certify'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_formmail_is_member" class="form-label">폼메일 사용 여부</label>
                </div>
                <div class="af-field">
                    <?php echo help('체크하지 않으면 비회원도 사용 할 수 있습니다.') ?>
                    <label for="cf_formmail_is_member" class="af-check form-label">
                        <input type="checkbox" name="cf_formmail_is_member" value="1" id="cf_formmail_is_member" <?php echo $config['cf_formmail_is_member'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">회원만 사용</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="anc_cf_article_mail" class="card">
    <div class="card-header">
        <h2 class="card-title">게시판 글 작성 시 메일 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_wr_super_admin" class="form-label">최고관리자</label>
                </div>
                <div class="af-field">
                    <?php echo help('최고관리자에게 메일을 발송합니다.') ?>
                    <label for="cf_email_wr_super_admin" class="af-check form-label">
                        <input type="checkbox" name="cf_email_wr_super_admin" value="1" id="cf_email_wr_super_admin" <?php echo $config['cf_email_wr_super_admin'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_wr_group_admin" class="form-label">그룹관리자</label>
                </div>
                <div class="af-field">
                    <?php echo help('그룹관리자에게 메일을 발송합니다.') ?>
                    <label for="cf_email_wr_group_admin" class="af-check form-label">
                        <input type="checkbox" name="cf_email_wr_group_admin" value="1" id="cf_email_wr_group_admin" <?php echo $config['cf_email_wr_group_admin'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_wr_board_admin" class="form-label">게시판관리자</label>
                </div>
                <div class="af-field">
                    <?php echo help('게시판관리자에게 메일을 발송합니다.') ?>
                    <label for="cf_email_wr_board_admin" class="af-check form-label">
                        <input type="checkbox" name="cf_email_wr_board_admin" value="1" id="cf_email_wr_board_admin" <?php echo $config['cf_email_wr_board_admin'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_wr_write" class="form-label">원글작성자</label>
                </div>
                <div class="af-field">
                    <?php echo help('게시자님께 메일을 발송합니다.') ?>
                    <label for="cf_email_wr_write" class="af-check form-label">
                        <input type="checkbox" name="cf_email_wr_write" value="1" id="cf_email_wr_write" <?php echo $config['cf_email_wr_write'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_wr_comment_all" class="form-label">댓글작성자</label>
                </div>
                <div class="af-field">
                    <?php echo help('원글에 댓글이 올라오는 경우 댓글 쓴 모든 분들께 메일을 발송합니다.') ?>
                    <label for="cf_email_wr_comment_all" class="af-check form-label">
                        <input type="checkbox" name="cf_email_wr_comment_all" value="1" id="cf_email_wr_comment_all" <?php echo $config['cf_email_wr_comment_all'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="anc_cf_join_mail" class="card">
    <div class="card-header">
        <h2 class="card-title">회원가입 시 메일 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_mb_super_admin" class="form-label">최고관리자 메일발송</label>
                </div>
                <div class="af-field">
                    <?php echo help('최고관리자에게 메일을 발송합니다.') ?>
                    <label for="cf_email_mb_super_admin" class="af-check form-label">
                        <input type="checkbox" name="cf_email_mb_super_admin" value="1" id="cf_email_mb_super_admin" <?php echo $config['cf_email_mb_super_admin'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_mb_member" class="form-label">회원님께 메일발송</label>
                </div>
                <div class="af-field">
                    <?php echo help('회원가입한 회원님께 메일을 발송합니다.') ?>
                    <label for="cf_email_mb_member" class="af-check form-label">
                        <input type="checkbox" name="cf_email_mb_member" value="1" id="cf_email_mb_member" <?php echo $config['cf_email_mb_member'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="anc_cf_vote_mail" class="card">
    <div class="card-header">
        <h2 class="card-title">투표 기타의견 작성 시 메일 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_email_po_super_admin" class="form-label">최고관리자 메일발송</label>
                </div>
                <div class="af-field">
                    <?php echo help('최고관리자에게 메일을 발송합니다.') ?>
                    <label for="cf_email_po_super_admin" class="af-check form-label">
                        <input type="checkbox" name="cf_email_po_super_admin" value="1" id="cf_email_po_super_admin" <?php echo $config['cf_email_po_super_admin'] ? 'checked' : ''; ?> class="form-checkbox">
                        <span class="form-label">사용</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>
