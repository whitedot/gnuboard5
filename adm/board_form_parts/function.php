<section id="anc_bo_function" class="card">
    <div class="card-header">
        <h2 class="card-title">게시판 기능 설정</h2>
    </div>
    <div class="card-body">
        <div class="af-grid">
            <div class="af-row">
            <div class="af-label"><label for="bo_count_modify" class="form-label">원글 수정 불가<strong>필수</strong></label></div>
            <div class="af-field">
            <div class="af-stack"><?php echo help('댓글의 수가 설정 수 이상이면 원글을 수정할 수 없습니다. 0으로 설정하시면 댓글 수에 관계없이 수정할 수있습니다.'); ?>
                <div class="af-inline af-inline-sentence">
                    <span class="af-inline-note">댓글</span>
                    <input type="text" name="bo_count_modify" value="<?php echo $board['bo_count_modify'] ?>" id="bo_count_modify" required class="form-input required af-input-xs" size="3">
                    <span class="af-inline-note">개 이상 달리면 수정불가</span>
                </div>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_count_modify" value="1" id="chk_grp_count_modify">
                <label for="chk_grp_count_modify" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_count_modify" value="1" id="chk_all_count_modify">
                <label for="chk_all_count_modify" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_count_delete" class="form-label">원글 삭제 불가<strong>필수</strong></label></div>
            <div class="af-field">
            <div class="af-inline af-inline-sentence">
                <span class="af-inline-note">댓글</span>
                <input type="text" name="bo_count_delete" value="<?php echo $board['bo_count_delete'] ?>" id="bo_count_delete" required class="form-input required af-input-xs" size="3">
                <span class="af-inline-note">개 이상 달리면 삭제불가</span>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_count_delete" value="1" id="chk_grp_count_delete">
                <label for="chk_grp_count_delete" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_count_delete" value="1" id="chk_all_count_delete">
                <label for="chk_all_count_delete" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_sideview" class="form-label">글쓴이 사이드뷰</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_sideview" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_sideview" value="1" id="bo_use_sideview" <?php echo $board['bo_use_sideview']?'checked':''; ?>>
                    <span class="form-label">사용 (글쓴이 클릭시 나오는 레이어 메뉴)</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_sideview" value="1" id="chk_grp_use_sideview">
                <label for="chk_grp_use_sideview" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_sideview" value="1" id="chk_all_use_sideview">
                <label for="chk_all_use_sideview" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_secret" class="form-label">비밀글 사용</label></div>
            <div class="af-field">
            <div><?php echo help('"체크박스"는 글작성시 비밀글 체크가 가능합니다. "무조건"은 작성되는 모든글을 비밀글로 작성합니다. (관리자는 체크박스로 출력합니다.) 스킨에 따라 적용되지 않을 수 있습니다.') ?>
                <select class="form-select" id="bo_use_secret" name="bo_use_secret">
                    <?php echo option_selected(0, $board['bo_use_secret'], "사용하지 않음"); ?>
                    <?php echo option_selected(1, $board['bo_use_secret'], "체크박스"); ?>
                    <?php echo option_selected(2, $board['bo_use_secret'], "무조건"); ?>
                </select></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_secret" value="1" id="chk_grp_use_secret">
                <label for="chk_grp_use_secret" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_secret" value="1" id="chk_all_use_secret">
                <label for="chk_all_use_secret" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_dhtml_editor" class="form-label">DHTML 에디터 사용</label></div>
            <div class="af-field">
            <div><?php echo help('글작성시 내용을 DHTML 에디터 기능으로 사용할 것인지 설정합니다. 스킨에 따라 적용되지 않을 수 있습니다.') ?>
                <label for="bo_use_dhtml_editor" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_dhtml_editor" value="1" <?php echo $board['bo_use_dhtml_editor']?'checked':''; ?> id="bo_use_dhtml_editor">
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_dhtml_editor" value="1" id="chk_grp_use_dhtml_editor">
                <label for="chk_grp_use_dhtml_editor" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_dhtml_editor" value="1" id="chk_all_use_dhtml_editor">
                <label for="chk_all_use_dhtml_editor" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_select_editor" class="form-label">게시판 에디터 선택</label></div>
            <div class="af-field">
            <div><?php echo help('게시판에 사용할 에디터를 설정합니다. 스킨에 따라 적용되지 않을 수 있습니다.') ?>
                <select class="form-select" name="bo_select_editor" id="bo_select_editor">
                <?php
                $arr = get_skin_dir('', G5_EDITOR_PATH);
                for ($i=0; $i<count($arr); $i++) {
                    if ($i == 0) {
                        echo "<option value=\"\">기본환경설정의 에디터 사용</option>";
                    }
                    echo "<option value=\"".$arr[$i]."\"".get_selected($board['bo_select_editor'], $arr[$i]).">".$arr[$i]."</option>\n";
                }
                ?>
                </select></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_select_editor" value="1" id="chk_grp_select_editor">
                <label for="chk_grp_select_editor" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_select_editor" value="1" id="chk_all_select_editor">
                <label for="chk_all_select_editor" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_rss_view" class="form-label">RSS 보이기 사용</label></div>
            <div class="af-field">
            <div><?php echo help('비회원 글읽기가 가능하고 RSS 보이기 사용에 체크가 되어야만 RSS 지원을 합니다.') ?>
                <label for="bo_use_rss_view" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_rss_view" value="1" <?php echo $board['bo_use_rss_view']?'checked':''; ?> id="bo_use_rss_view">
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_rss_view" value="1" id="chk_grp_use_rss_view">
                <label for="chk_grp_use_rss_view" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_rss_view" value="1" id="chk_all_use_rss_view">
                <label for="chk_all_use_rss_view" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_good" class="form-label">추천 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_good" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_good" value="1" <?php echo $board['bo_use_good']?'checked':''; ?> id="bo_use_good">
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_good" value="1" id="chk_grp_use_good">
                <label for="chk_grp_use_good" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_good" value="1" id="chk_all_use_good">
                <label for="chk_all_use_good" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_nogood" class="form-label">비추천 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_nogood" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_nogood" value="1" id="bo_use_nogood" <?php echo $board['bo_use_nogood']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_nogood" value="1" id="chk_grp_use_nogood">
                <label for="chk_grp_use_nogood" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_nogood" value="1" id="chk_all_use_nogood">
                <label for="chk_all_use_nogood" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_name" class="form-label">이름(실명) 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_name" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_name" value="1" id="bo_use_name" <?php echo $board['bo_use_name']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_name" value="1" id="chk_grp_use_name">
                <label for="chk_grp_use_name" class="form-label">그룹적용</label>

                <input type="checkbox" class="form-checkbox" name="chk_all_use_name" value="1" id="chk_all_use_name">
                <label for="chk_all_use_name" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_signature" class="form-label">서명보이기 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_signature" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_signature" value="1" id="bo_use_signature" <?php echo $board['bo_use_signature']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_signature" value="1" id="chk_grp_use_signature">
                <label for="chk_grp_use_signature" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_signature" value="1" id="chk_all_use_signature">
                <label for="chk_all_use_signature" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_ip_view" class="form-label">IP 보이기 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_ip_view" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_ip_view" value="1" id="bo_use_ip_view" <?php echo $board['bo_use_ip_view']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_ip_view" value="1" id="chk_grp_use_ip_view">
                <label for="chk_grp_use_ip_view" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_ip_view" value="1" id="chk_all_use_ip_view">
                <label for="chk_all_use_ip_view" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_list_content" class="form-label">목록에서 내용 사용</label></div>
            <div class="af-field">
            <div><?php echo help("목록에서 게시판 제목외에 내용도 읽어와야 할 경우에 설정하는 옵션입니다. 기본은 사용하지 않습니다."); ?>
                <label for="bo_use_list_content" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_list_content" value="1" id="bo_use_list_content" <?php echo $board['bo_use_list_content']?'checked':''; ?>>
                    <span class="form-label">사용 (사용시 속도가 느려질 수 있습니다.)</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_list_content" value="1" id="chk_grp_use_list_content">
                <label for="chk_grp_use_list_content" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_list_content" value="1" id="chk_all_use_list_content">
                <label for="chk_all_use_list_content" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_list_file" class="form-label">목록에서 파일 사용</label></div>
            <div class="af-field">
            <div><?php echo help("목록에서 게시판 첨부파일을 읽어와야 할 경우에 설정하는 옵션입니다. 기본은 사용하지 않습니다."); ?>
                <label for="bo_use_list_file" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_list_file" value="1" id="bo_use_list_file" <?php echo $board['bo_use_list_file']?'checked':''; ?>>
                    <span class="form-label">사용 (사용시 속도가 느려질 수 있습니다.)</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_list_file" value="1" id="chk_grp_use_list_file">
                <label for="chk_grp_use_list_file" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_list_file" value="1" id="chk_all_use_list_file">
                <label for="chk_all_use_list_file" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_list_view" class="form-label">전체목록보이기 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_list_view" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_list_view" value="1" id="bo_use_list_view" <?php echo $board['bo_use_list_view']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_list_view" value="1" id="chk_grp_use_list_view">
                <label for="chk_grp_use_list_view" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_list_view" value="1" id="chk_all_use_list_view">
                <label for="chk_all_use_list_view" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_email" class="form-label">메일발송 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_email" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_email" value="1" id="bo_use_email" <?php echo $board['bo_use_email']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_email" value="1" id="chk_grp_use_email">
                <label for="chk_grp_use_email" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_email" value="1" id="chk_all_use_email">
                <label for="chk_all_use_email" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_cert" class="form-label">본인확인 사용</label></div>
            <div class="af-field">
            <div><?php echo help("본인확인 여부에 따라 게시물을 조회 할 수 있도록 합니다."); ?>
                <select class="form-select" id="bo_use_cert" name="bo_use_cert">
                    <?php
                    echo option_selected("", $board['bo_use_cert'], "사용안함");
                    if ($config['cf_cert_use']) {
                        echo option_selected("cert", $board['bo_use_cert'], "본인확인된 회원전체");
                        echo option_selected("adult", $board['bo_use_cert'], "본인확인된 성인회원만");
                    }
                    ?>
                </select></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_cert" value="1" id="chk_grp_use_cert">
                <label for="chk_grp_use_cert" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_cert" value="1" id="chk_all_use_cert">
                <label for="chk_all_use_cert" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_upload_count" class="form-label">파일 업로드 개수<strong>필수</strong></label></div>
            <div class="af-field">
            <div><?php echo help('게시물 한건당 업로드 할 수 있는 파일의 최대 개수 (0 은 파일첨부 사용하지 않음)') ?>
                <input type="text" name="bo_upload_count" value="<?php echo $board['bo_upload_count'] ?>" id="bo_upload_count" required class="form-input required" size="4"></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_upload_count" value="1" id="chk_grp_upload_count">
                <label for="chk_grp_upload_count" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_upload_count" value="1" id="chk_all_upload_count">
                <label for="chk_all_upload_count" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_upload_size" class="form-label">파일 업로드 용량<strong>필수</strong></label></div>
            <div class="af-field">
            <div class="af-stack"><?php echo help('최대 '.ini_get("upload_max_filesize").' 이하 업로드 가능, 1 MB = 1,048,576 bytes') ?>
                <div class="af-inline af-inline-sentence">
                    <span class="af-inline-note">업로드 파일 한개당</span>
                    <input type="text" name="bo_upload_size" value="<?php echo $board['bo_upload_size'] ?>" id="bo_upload_size" required class="form-input required af-input-sm"  size="10">
                    <span class="af-inline-note">bytes 이하</span>
                </div>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_upload_size" value="1" id="chk_grp_upload_size">
                <label for="chk_grp_upload_size" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_upload_size" value="1" id="chk_all_upload_size">
                <label for="chk_all_upload_size" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_file_content" class="form-label">파일 설명 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_file_content" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_file_content" value="1" id="bo_use_file_content" <?php echo $board['bo_use_file_content']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_file_content" value="1" id="chk_grp_use_file_content">
                <label for="chk_grp_use_file_content" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_file_content" value="1" id="chk_all_use_file_content">
                <label for="chk_all_use_file_content" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_write_min" class="form-label">최소 글수 제한</label></div>
            <div class="af-field">
            <div><?php echo help('글 입력시 최소 글자수를 설정. 0을 입력하거나 최고관리자, DHTML 에디터 사용시에는 검사하지 않음') ?>
                <input type="text" class="form-input" name="bo_write_min" value="<?php echo $board['bo_write_min'] ?>" id="bo_write_min" size="4"></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_write_min" value="1" id="chk_grp_write_min">
                <label for="chk_grp_write_min" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_write_min" value="1" id="chk_all_write_min">
                <label for="chk_all_write_min" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_write_max" class="form-label">최대 글수 제한</label></div>
            <div class="af-field">
            <div><?php echo help('글 입력시 최대 글자수를 설정. 0을 입력하거나 최고관리자, DHTML 에디터 사용시에는 검사하지 않음') ?>
                <input type="text" class="form-input" name="bo_write_max" value="<?php echo $board['bo_write_max'] ?>" id="bo_write_max" size="4"></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_write_max" value="1" id="chk_grp_write_max">
                <label for="chk_grp_write_max" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_write_max" value="1" id="chk_all_write_max">
                <label for="chk_all_write_max" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_comment_min" class="form-label">최소 댓글수 제한</label></div>
            <div class="af-field">
            <div><?php echo help('댓글 입력시 최소 글자수를 설정. 0을 입력하면 검사하지 않음') ?>
                <input type="text" class="form-input" name="bo_comment_min" value="<?php echo $board['bo_comment_min'] ?>" id="bo_comment_min" size="4"></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_comment_min" value="1" id="chk_grp_comment_min">
                <label for="chk_grp_comment_min" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_comment_min" value="1" id="chk_all_comment_min">
                <label for="chk_all_comment_min" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_comment_max" class="form-label">최대 댓글수 제한</label></div>
            <div class="af-field">
            <div><?php echo help('댓글 입력시 최대 글자수를 설정. 0을 입력하면 검사하지 않음') ?>
                <input type="text" class="form-input" name="bo_comment_max" value="<?php echo $board['bo_comment_max'] ?>" id="bo_comment_max" size="4"></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_comment_max" value="1" id="chk_grp_comment_max">
                <label for="chk_grp_comment_max" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_comment_max" value="1" id="chk_all_comment_max">
                <label for="chk_all_comment_max" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_sns" class="form-label">SNS 사용</label></div>
            <div class="af-field">
            <div><?php echo help("사용에 체크하시면 소셜네트워크서비스(SNS)에 글을 퍼가거나 댓글을 동시에 등록할수 있습니다.<br>기본환경설정의 SNS 설정을 하셔야 사용이 가능합니다.") ?>
                <label for="bo_use_sns" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_sns" value="1" id="bo_use_sns" <?php echo $board['bo_use_sns']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_sns" value="1" id="chk_grp_use_sns">
                <label for="chk_grp_use_sns" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_sns" value="1" id="chk_all_use_sns">
                <label for="chk_all_use_sns" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_search" class="form-label">전체 검색 사용</label></div>
            <div class="af-field">
            <div>
                <label for="bo_use_search" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_search" value="1" id="bo_use_search" <?php echo $board['bo_use_search']?'checked':''; ?>>
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_search" value="1" id="chk_grp_use_search">
                <label for="chk_grp_use_search" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_search" value="1" id="chk_all_use_search">
                <label for="chk_all_use_search" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_order" class="form-label">출력 순서</label></div>
            <div class="af-field">
            <div><?php echo help('숫자가 낮은 게시판 부터 메뉴나 검색시 우선 출력합니다.') ?>
                <input type="text" class="form-input" name="bo_order" value="<?php echo $board['bo_order'] ?>" id="bo_order" size="4"></div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_order" value="1" id="chk_grp_order">
                <label for="chk_grp_order" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_order" value="1" id="chk_all_order">
                <label for="chk_all_order" class="form-label">전체적용</label></div>
        </div>
        </div>
                <div class="af-row">
            <div class="af-label"><label for="bo_use_captcha" class="form-label">캡챠 사용</label></div>
            <div class="af-field">
            <div><?php echo help("체크하면 글 작성시 캡챠를 무조건 사용합니다.( 회원 + 비회원 모두 )<br>미 체크하면 비회원에게만 캡챠를 사용합니다.") ?>
                <label for="bo_use_captcha" class="af-check form-label">
                    <input type="checkbox" class="form-checkbox" name="bo_use_captcha" value="1" <?php echo $board['bo_use_captcha']?'checked':''; ?> id="bo_use_captcha">
                    <span class="form-label">사용</span>
                </label>
            </div>
            <div class="af-inline"><input type="checkbox" class="form-checkbox" name="chk_grp_use_captcha" value="1" id="chk_grp_use_captcha">
                <label for="chk_grp_use_captcha" class="form-label">그룹적용</label>
                <input type="checkbox" class="form-checkbox" name="chk_all_use_captcha" value="1" id="chk_all_use_captcha">
                <label for="chk_all_use_captcha" class="form-label">전체적용</label></div>
        </div>
        </div>
        </div>
    </div>
</section>




