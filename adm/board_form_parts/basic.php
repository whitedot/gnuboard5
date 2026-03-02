<section id="anc_bo_basic" class="card">
    <div class="card-header">
        <h2 class="card-title">게시판 기본 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
            <div class="af-label"><label for="bo_table" class="form-label">TABLE<?php echo $sound_only ?></label></div>
            <div class="af-field"><input type="text" name="bo_table" value="<?php echo $board['bo_table'] ?>" id="bo_table" <?php echo $required ?> <?php echo $readonly ?> class="form-input <?php echo $readonly ?>" maxlength="20">
                <?php if ($w == '') { ?>
                    영문자, 숫자, _ 만 가능 (공백없이 20자 이내)
                <?php } else { ?>
                    <a href="<?php echo get_pretty_url($board['bo_table']) ?>">게시판 바로가기</a>
                    <a href="./board_list.php?<?php echo $qstr;?>">목록으로</a>
                <?php } ?></div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="gr_id" class="form-label">그룹<strong>필수</strong></label></div>
            <div class="af-field"><?php echo get_group_select('gr_id', $board['gr_id'], 'class="form-select required"'); ?>
                <?php if ($w=='u') { ?>
                    <a href="javascript:document.location.href='./board_list.php?sfl=a.gr_id&stx='+document.fboardform.gr_id.value;">동일그룹 게시판목록</a>
                <?php } ?></div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_subject" class="form-label">게시판 제목<strong>필수</strong></label></div>
            <div class="af-field"><input type="text" name="bo_subject" value="<?php echo get_text($board['bo_subject']) ?>" id="bo_subject" required class="form-input required" size="80" maxlength="120"></div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_device" class="form-label">접속기기</label></div>
            <div class="af-field">
            <div><?php echo help("PC 와 모바일 사용을 구분합니다.") ?>
                <select class="form-select" id="bo_device" name="bo_device">
                    <option value="both"<?php echo get_selected($board['bo_device'], 'both'); ?>>PC와 모바일에서 모두 사용</option>
                    <option value="pc"<?php echo get_selected($board['bo_device'], 'pc'); ?>>PC 전용</option>
                    <option value="mobile"<?php echo get_selected($board['bo_device'], 'mobile'); ?>>모바일 전용</option>
                </select></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_device" value="1" id="chk_grp_device">
                <label for="chk_grp_device" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_device" value="1" id="chk_all_device">
                <label for="chk_all_device" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_category_list" class="form-label">분류</label></div>
            <div class="af-field">
            <div><?php echo help('분류와 분류 사이는 | 로 구분하세요. (예: 질문|답변) 첫자로 #은 입력하지 마세요. (예: #질문|#답변 [X])'."\n".'분류명에 일부 특수문자 ()/ 는 사용할수 없습니다.'); ?>
                <input type="text" class="form-input" name="bo_category_list" value="<?php echo get_text($board['bo_category_list']) ?>" id="bo_category_list" size="70">
                <input type="checkbox" class="form-checkbox" name="bo_use_category" value="1" id="bo_use_category" <?php echo $board['bo_use_category']?'checked':''; ?>>
                <label for="bo_use_category" class="form-label">사용</label></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_category_list" value="1" id="chk_grp_category_list">
                <label for="chk_grp_category_list" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_category_list" value="1" id="chk_all_category_list">
                <label for="chk_all_category_list" class="form-label">전체적용</label></div>
        </div>
        </div>
        <?php if ($w == 'u') { ?>
                <div class="af-row">
            <div class="af-label"><label for="proc_count" class="form-label">카운트 조정</label></div>
            <div class="af-field"><?php echo help('현재 원글수 : '.number_format($board['bo_count_write']).', 현재 댓글수 : '.number_format($board['bo_count_comment'])."\n".'게시판 목록에서 글의 번호가 맞지 않을 경우에 체크하십시오.') ?>
                <input type="checkbox" class="form-checkbox" name="proc_count" value="1" id="proc_count"></div>
        </div>
        <?php } ?>
        </div>
    </div>
</section>




