<?php
$sub_menu = "300500";
require_once './_common.php';
require_once G5_EDITOR_LIB;

auth_check_menu($auth, $sub_menu, 'r');

// DB 테이블 생성
if (!sql_query(" DESCRIBE `{$g5['qa_config_table']}` ", false)) {
    sql_query(
        " CREATE TABLE IF NOT EXISTS `{$g5['qa_config_table']}` (
                  `qa_id` int(11) NOT NULL auto_increment,
                  `qa_title` varchar(255) NOT NULL DEFAULT'',
                  `qa_category` varchar(255) NOT NULL DEFAULT'',
                  `qa_skin` varchar(255) NOT NULL DEFAULT '',
                  `qa_use_email` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_req_email` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_use_hp` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_req_hp` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_use_sms` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_send_number` varchar(255) NOT NULL DEFAULT '',
                  `qa_admin_hp` varchar(255) NOT NULL DEFAULT '',
                  `qa_use_editor` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_subject_len` int(11) NOT NULL DEFAULT '0',
                  `qa_page_rows` int(11) NOT NULL DEFAULT '0',
                  `qa_image_width` int(11) NOT NULL DEFAULT '0',
                  `qa_upload_size` int(11) NOT NULL DEFAULT '0',
                  `qa_insert_content` text NOT NULL,
                  `qa_include_head` varchar(255) NOT NULL DEFAULT '',
                  `qa_include_tail` varchar(255) NOT NULL DEFAULT '',
                  `qa_content_head` text NOT NULL,
                  `qa_content_tail` text NOT NULL,
                  PRIMARY KEY (`qa_id`)
                )",
        true
    );
    sql_query(
        " CREATE TABLE IF NOT EXISTS `{$g5['qa_content_table']}` (
                  `qa_id` int(11) NOT NULL AUTO_INCREMENT,
                  `qa_num` int(11) NOT NULL DEFAULT '0',
                  `qa_parent` int(11) NOT NULL DEFAULT '0',
                  `qa_related` int(11) NOT NULL DEFAULT '0',
                  `mb_id` varchar(20) NOT NULL DEFAULT '',
                  `qa_name` varchar(255) NOT NULL DEFAULT '',
                  `qa_email` varchar(255) NOT NULL DEFAULT '',
                  `qa_hp` varchar(255) NOT NULL DEFAULT '',
                  `qa_type` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_category` varchar(255) NOT NULL DEFAULT '',
                  `qa_email_recv` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_sms_recv` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_html` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_subject` varchar(255) NOT NULL DEFAULT '',
                  `qa_content` text NOT NULL,
                  `qa_status` tinyint(4) NOT NULL DEFAULT '0',
                  `qa_file1` varchar(255) NOT NULL DEFAULT '',
                  `qa_source1` varchar(255) NOT NULL DEFAULT '',
                  `qa_file2` varchar(255) NOT NULL DEFAULT '',
                  `qa_source2` varchar(255) NOT NULL DEFAULT '',
                  `qa_ip` varchar(255) NOT NULL DEFAULT '',
                  `qa_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  PRIMARY KEY (`qa_id`),
                  KEY `qa_num_parent` (`qa_num`,`qa_parent`)
                )",
        true
    );
}

$sql = " SHOW COLUMNS FROM `{$g5['qa_content_table']}` LIKE 'qa_content' ";
$row = sql_fetch($sql);
if (strpos($row['Type'], 'text') === false) {
    sql_query(" ALTER TABLE `{$g5['qa_content_table']}` CHANGE `qa_content` `qa_content` text NOT NULL ", true);
}

$qaconfig = get_qa_config();

if (empty($qaconfig)) {
    $sql = " insert into `{$g5['qa_config_table']}`
                ( qa_title, qa_category, qa_skin, qa_use_email, qa_req_email, qa_use_hp, qa_req_hp, qa_use_editor, qa_subject_len, qa_page_rows, qa_image_width, qa_upload_size, qa_insert_content )
              values
                ( '1:1문의', '회원|포인트', 'theme/basic', '1', '0', '1', '0', '1', '60', '15', '600', '1048576', '' ) ";
    sql_query($sql);

    $qaconfig = get_qa_config();
}

// 관리자 이메일필드 추가
if (!isset($qaconfig['qa_admin_email'])) {
    sql_query(
        " ALTER TABLE `{$g5['qa_config_table']}`
                    ADD `qa_admin_email` varchar(255) NOT NULL DEFAULT '' AFTER `qa_admin_hp` ",
        true
    );
}

// 상단 하단 설정 필드 추가
if (!isset($qaconfig['qa_include_head'])) {
    sql_query(
        " ALTER TABLE `{$g5['qa_config_table']}`
                    ADD `qa_include_head` varchar(255) NOT NULL DEFAULT '' AFTER `qa_insert_content`,
                    ADD `qa_include_tail` varchar(255) NOT NULL DEFAULT '' AFTER `qa_include_head`,
                    ADD `qa_content_head` text NOT NULL AFTER `qa_include_tail`,
                    ADD `qa_content_tail` text NOT NULL AFTER `qa_content_head` ",
        true
    );
}

$g5['title'] = '1:1문의 설정';
$admin_container_class = 'admin-page-qa-config';
$admin_page_subtitle = '문의 분류, 입력 항목, 알림 옵션을 한 화면에서 관리하세요.';
require_once './admin.head.php';
?>

<form name="fqaconfigform" id="fqaconfigform" method="post" onsubmit="return fqaconfigform_submit(this);" autocomplete="off" class="admin-form-layout space-y-5">
    <input type="hidden" name="token" value="" id="token">

    <section id="anc_cf_qa_config" class="card">
        <div class="card-header">
            <h2 class="card-title">1:1문의 설정</h2>
        </div>
        <div class="card-body">
            <p class="hint-text">문의 기본 정보, 입력 항목, 알림 조건, 에디터 옵션을 설정합니다.</p>

            <div class="af-grid">
                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_title" class="form-label">타이틀<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <div class="af-inline">
                            <input type="text" name="qa_title" value="<?php echo get_sanitize_input($qaconfig['qa_title']); ?>" id="qa_title" required class="form-input required" size="40">
                            <a href="<?php echo G5_BBS_URL; ?>/qalist.php" class="btn btn-sm btn-surface-default-soft">1:1문의 바로가기</a>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_category" class="form-label">분류<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <?php echo help('분류와 분류 사이는 | 로 구분하세요. (예: 질문|답변) 첫자로 #은 입력하지 마세요. (예: #질문|#답변 [X])') ?>
                        <input type="text" name="qa_category" value="<?php echo get_sanitize_input($qaconfig['qa_category']); ?>" id="qa_category" required class="form-input required" size="70">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_skin" class="form-label">스킨 디렉토리<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <?php echo get_skin_select('qa', 'qa_skin', 'qa_skin', $qaconfig['qa_skin'], 'required class="form-select required"'); ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_use_email" class="form-label">이메일 입력</label>
                    </div>
                    <div class="af-field">
                        <div class="af-inline">
                            <label for="qa_use_email" class="af-check form-label">
                                <input type="checkbox" name="qa_use_email" value="1" id="qa_use_email" <?php echo $qaconfig['qa_use_email'] ? 'checked' : ''; ?> class="form-checkbox">
                                <span class="form-label">보이기</span>
                            </label>
                            <label for="qa_req_email" class="af-check form-label">
                                <input type="checkbox" name="qa_req_email" value="1" id="qa_req_email" <?php echo $qaconfig['qa_req_email'] ? 'checked' : ''; ?> class="form-checkbox">
                                <span class="form-label">필수입력</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_use_hp" class="form-label">휴대폰 입력</label>
                    </div>
                    <div class="af-field">
                        <div class="af-inline">
                            <label for="qa_use_hp" class="af-check form-label">
                                <input type="checkbox" name="qa_use_hp" value="1" id="qa_use_hp" <?php echo $qaconfig['qa_use_hp'] ? 'checked' : ''; ?> class="form-checkbox">
                                <span class="form-label">보이기</span>
                            </label>
                            <label for="qa_req_hp" class="af-check form-label">
                                <input type="checkbox" name="qa_req_hp" value="1" id="qa_req_hp" <?php echo $qaconfig['qa_req_hp'] ? 'checked' : ''; ?> class="form-checkbox">
                                <span class="form-label">필수입력</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_use_sms" class="form-label">SMS 알림</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('휴대폰 입력을 사용하실 경우 문의글 등록시 등록자가 답변등록시 SMS 알림 수신을 선택할 수 있도록 합니다.<br>SMS 알림을 사용하기 위해서는 기본환경설정 > <a href="' . G5_ADMIN_URL . '/config_form.php#anc_cf_sms">SMS 설정</a>을 하셔야 합니다.') ?>
                        <select name="qa_use_sms" id="qa_use_sms" class="form-select">
                            <?php echo option_selected(0, $qaconfig['qa_use_sms'], '사용안함'); ?>
                            <?php echo option_selected(1, $qaconfig['qa_use_sms'], '사용함'); ?>
                        </select>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_send_number" class="form-label">SMS 발신번호</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('SMS 알림 전송시 발신번호로 사용됩니다.'); ?>
                        <input type="text" name="qa_send_number" value="<?php echo get_sanitize_input($qaconfig['qa_send_number']); ?>" id="qa_send_number" size="30" class="form-input">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_admin_hp" class="form-label">관리자 휴대폰번호</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('관리자 휴대폰번호를 입력하시면 문의글 등록시 등록하신 번호로 SMS 알림이 전송됩니다.<br>SMS 알림을 사용하지 않으시면 알림이 전송되지 않습니다.'); ?>
                        <input type="text" name="qa_admin_hp" value="<?php echo get_sanitize_input($qaconfig['qa_admin_hp']); ?>" id="qa_admin_hp" size="30" class="form-input">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_admin_email" class="form-label">관리자 이메일</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('관리자 이메일을 입력하시면 문의글 등록시 등록하신 이메일로 알림이 전송됩니다.'); ?>
                        <input type="text" name="qa_admin_email" value="<?php echo get_sanitize_input($qaconfig['qa_admin_email']); ?>" id="qa_admin_email" size="50" class="form-input">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_use_editor" class="form-label">DHTML 에디터 사용</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('글작성시 내용을 DHTML 에디터 기능으로 사용할 것인지 설정합니다. 스킨에 따라 적용되지 않을 수 있습니다.'); ?>
                        <select name="qa_use_editor" id="qa_use_editor" class="form-select">
                            <?php echo option_selected(0, $qaconfig['qa_use_editor'], '사용안함'); ?>
                            <?php echo option_selected(1, $qaconfig['qa_use_editor'], '사용함'); ?>
                        </select>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_subject_len" class="form-label">제목 길이<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <?php echo help('목록에서의 제목 글자수') ?>
                        <input type="text" name="qa_subject_len" value="<?php echo $qaconfig['qa_subject_len'] ?>" id="qa_subject_len" required class="form-input required af-input-xs" size="4">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_page_rows" class="form-label">페이지당 목록 수<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <input type="text" name="qa_page_rows" value="<?php echo $qaconfig['qa_page_rows'] ?>" id="qa_page_rows" required class="form-input required af-input-xs" size="4">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_image_width" class="form-label">이미지 폭 크기<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <?php echo help('게시판에서 출력되는 이미지의 폭 크기') ?>
                        <div class="af-inline af-inline-sentence">
                            <input type="text" name="qa_image_width" value="<?php echo $qaconfig['qa_image_width'] ?>" id="qa_image_width" required class="form-input required af-input-xs" size="4">
                            <span class="af-inline-note">픽셀</span>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_upload_size" class="form-label">파일 업로드 용량<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <?php echo help('최대 ' . ini_get("upload_max_filesize") . ' 이하 업로드 가능, 1 MB = 1,048,576 bytes') ?>
                        <div class="af-inline af-inline-sentence">
                            <span class="af-inline-note">업로드 파일 한개당</span>
                            <input type="text" name="qa_upload_size" value="<?php echo $qaconfig['qa_upload_size'] ?>" id="qa_upload_size" required class="form-input required af-input-sm" size="10">
                            <span class="af-inline-note">bytes 이하</span>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_include_head" class="form-label">상단 파일 경로</label>
                    </div>
                    <div class="af-field">
                        <input type="text" name="qa_include_head" value="<?php echo get_sanitize_input($qaconfig['qa_include_head']); ?>" id="qa_include_head" size="50" class="form-input">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_include_tail" class="form-label">하단 파일 경로</label>
                    </div>
                    <div class="af-field">
                        <input type="text" name="qa_include_tail" value="<?php echo get_sanitize_input($qaconfig['qa_include_tail']); ?>" id="qa_include_tail" size="50" class="form-input">
                    </div>
                </div>

                <div class="af-row hidden" id="admin_captcha_box">
                    <div class="af-label">
                        <label for="captcha_key" class="form-label">자동등록방지</label>
                    </div>
                    <div class="af-field">
                        <?php
                        echo help("파일 경로를 입력 또는 수정시 캡챠를 반드시 입력해야 합니다.");
                        require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
                        $captcha_html = captcha_html();
                        $captcha_js   = chk_captcha_js();
                        echo $captcha_html;
                        ?>
                        <script>
                            jQuery("#captcha_key").removeAttr("required").removeClass("required");
                        </script>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_content_head" class="form-label">상단 내용</label>
                    </div>
                    <div class="af-field">
                        <?php echo editor_html("qa_content_head", get_text(html_purifier($qaconfig['qa_content_head']), 0)); ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_content_tail" class="form-label">하단 내용</label>
                    </div>
                    <div class="af-field">
                        <?php echo editor_html("qa_content_tail", get_text(html_purifier($qaconfig['qa_content_tail']), 0)); ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="qa_insert_content" class="form-label">글쓰기 기본 내용</label>
                    </div>
                    <div class="af-field">
                        <textarea id="qa_insert_content" name="qa_insert_content" rows="5" class="form-textarea"><?php echo html_purifier($qaconfig['qa_insert_content']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-end border-default-300 border-t border-dashed pt-4">
        <button type="submit" accesskey="s" class="btn btn-solid-primary">저장</button>
    </div>

</form>

<script>
    var captcha_chk = false,
        qa_include_head = jQuery.trim(jQuery("#qa_include_head").val()),
        qa_include_tail = jQuery.trim(jQuery("#qa_include_tail").val());

    function use_captcha_check() {
        $.ajax({
            type: "POST",
            url: g5_admin_url + "/ajax.use_captcha.php",
            data: {
                admin_use_captcha: "1"
            },
            cache: false,
            async: false,
            dataType: "json",
            success: function(data) {}
        });
    }

    function frm_check_file() {
        var head = jQuery.trim(jQuery("#qa_include_head").val());
        var tail = jQuery.trim(jQuery("#qa_include_tail").val());

        if (qa_include_head !== head || qa_include_tail !== tail) {
            // 캡챠를 사용합니다.
            jQuery("#admin_captcha_box").show();
            captcha_chk = true;

            use_captcha_check();

            return false;
        } else {
            jQuery("#admin_captcha_box").hide();
        }

        return true;
    }

    jQuery(function($) {
        if (window.self !== window.top) { // frame 또는 iframe을 사용할 경우 체크
            $("#qa_include_head, #qa_include_tail").on("change paste keyup", function(e) {
                frm_check_file();
            });

            use_captcha_check();
        }
    });

    function fqaconfigform_submit(f) {
        <?php echo get_editor_js("qa_content_head"); ?>
        <?php echo get_editor_js("qa_content_tail"); ?>

        if (captcha_chk) {
            <?php echo isset($captcha_js) ? $captcha_js : ''; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>
        }

        f.action = "./qa_config_update.php";
        return true;
    }
</script>

<?php
require_once './admin.tail.php';

