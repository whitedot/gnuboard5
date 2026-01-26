<section id="anc_bo_basic">
    <h2 class="h2_frm">게시판 기본 설정</h2>
    <?php echo $pg_anchor ?>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시판 기본 설정</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="bo_table">TABLE<?php echo $sound_only ?></label></th>
            <td colspan="2">
                <input type="text" name="bo_table" value="<?php echo $board['bo_table'] ?>" id="bo_table" <?php echo $required ?> <?php echo $readonly ?> class="frm_input <?php echo $readonly ?> <?php echo $required ?> <?php echo $required_valid ?>" maxlength="20">
                <?php if ($w == '') { ?>
                    영문자, 숫자, _ 만 가능 (공백없이 20자 이내)
                <?php } else { ?>
                    <a href="<?php echo get_pretty_url($board['bo_table']) ?>" class="btn_frmline">게시판 바로가기</a>
                    <a href="./board_list.php?<?php echo $qstr;?>" class="btn_frmline">목록으로</a>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="gr_id">그룹<strong class="sound_only">필수</strong></label></th>
            <td colspan="2">
                <?php echo get_group_select('gr_id', $board['gr_id'], 'required'); ?>
                <?php if ($w=='u') { ?>
                    <a href="javascript:document.location.href='./board_list.php?sfl=a.gr_id&stx='+document.fboardform.gr_id.value;" class="btn_frmline">동일그룹 게시판목록</a>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_subject">게시판 제목<strong class="sound_only">필수</strong></label></th>
            <td colspan="2">
                <input type="text" name="bo_subject" value="<?php echo get_text($board['bo_subject']) ?>" id="bo_subject" required class="required frm_input" size="80" maxlength="120">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_mobile_subject">모바일 게시판 제목</label></th>
            <td colspan="2">
                <?php echo help("모바일에서 보여지는 게시판 제목이 다른 경우에 입력합니다. 입력이 없으면 기본 게시판 제목이 출력됩니다.") ?>
                <input type="text" name="bo_mobile_subject" value="<?php echo get_text($board['bo_mobile_subject']) ?>" id="bo_mobile_subject" class="frm_input" size="80" maxlength="120">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_device">접속기기</label></th>
            <td>
                <?php echo help("PC 와 모바일 사용을 구분합니다.") ?>
                <select id="bo_device" name="bo_device">
                    <option value="both"<?php echo get_selected($board['bo_device'], 'both'); ?>>PC와 모바일에서 모두 사용</option>
                    <option value="pc"<?php echo get_selected($board['bo_device'], 'pc'); ?>>PC 전용</option>
                    <option value="mobile"<?php echo get_selected($board['bo_device'], 'mobile'); ?>>모바일 전용</option>
                </select>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_device" value="1" id="chk_grp_device">
                <label for="chk_grp_device">그룹적용</label>
                <input type="checkbox" name="chk_all_device" value="1" id="chk_all_device">
                <label for="chk_all_device">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="bo_category_list">분류</label></th>
            <td>
                <?php echo help('분류와 분류 사이는 | 로 구분하세요. (예: 질문|답변) 첫자로 #은 입력하지 마세요. (예: #질문|#답변 [X])'."\n".'분류명에 일부 특수문자 ()/ 는 사용할수 없습니다.'); ?>
                <input type="text" name="bo_category_list" value="<?php echo get_text($board['bo_category_list']) ?>" id="bo_category_list" class="frm_input" size="70">
                <input type="checkbox" name="bo_use_category" value="1" id="bo_use_category" <?php echo $board['bo_use_category']?'checked':''; ?>>
                <label for="bo_use_category">사용</label>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_grp_category_list" value="1" id="chk_grp_category_list">
                <label for="chk_grp_category_list">그룹적용</label>
                <input type="checkbox" name="chk_all_category_list" value="1" id="chk_all_category_list">
                <label for="chk_all_category_list">전체적용</label>
            </td>
        </tr>
        <?php if ($w == 'u') { ?>
        <tr>
            <th scope="row"><label for="proc_count">카운트 조정</label></th>
            <td colspan="2">
                <?php echo help('현재 원글수 : '.number_format($board['bo_count_write']).', 현재 댓글수 : '.number_format($board['bo_count_comment'])."\n".'게시판 목록에서 글의 번호가 맞지 않을 경우에 체크하십시오.') ?>
                <input type="checkbox" name="proc_count" value="1" id="proc_count">
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>
