<?php
$sub_menu = '300600';
require_once './_common.php';
require_once G5_EDITOR_LIB;

auth_check_menu($auth, $sub_menu, "w");

$co_id = isset($_REQUEST['co_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['co_id']) : '';

$html_title = "내용";

if ($w == "u") {
    $html_title .= " 수정";

    $sql = " select * from {$g5['content_table']} where co_id = '$co_id' ";
    $co = sql_fetch($sql);
    if (!$co['co_id']) {
        alert('등록된 자료가 없습니다.');
    }

    if (function_exists('check_case_exist_title')) check_case_exist_title($co, G5_CONTENT_DIR, false);

} else {
    $html_title .= ' 입력';
    $co = array(
        'co_id' => '',
        'co_subject' => '',
        'co_content' => '',
        'co_include_head' => '',
        'co_include_tail' => '',
        'co_tag_filter_use' => 1,
        'co_html' => 2,
        'co_skin' => 'theme/basic'
    );
}

$g5['title'] = $html_title;
$admin_container_class = 'admin-page-content-form';
$admin_page_subtitle = '내용 페이지의 기본 정보, 에디터 내용, 상하단 경로를 한 화면에서 관리하세요.';
require_once G5_ADMIN_PATH . '/admin.head.php';
?>

<form name="frmcontentform" id="frmcontentform" action="./contentformupdate.php" onsubmit="return frmcontentform_check(this);" method="post" enctype="MULTIPART/FORM-DATA" class="admin-form-layout space-y-5">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="co_html" value="1">
    <input type="hidden" name="token" value="">

    <section class="card">
        <div class="card-header">
            <h2 class="card-title">내용 기본 설정</h2>
        </div>
        <div class="card-body">
            <p class="hint-text">내용 ID와 제목을 설정하고 에디터/스킨/파일 경로 및 상하단 이미지를 함께 관리합니다.</p>

            <div class="af-grid">
                <div class="af-row">
                    <div class="af-label">
                        <label for="co_id" class="form-label">ID<?php echo $w == 'u' ? '' : '<strong>필수</strong>'; ?></label>
                    </div>
                    <div class="af-field">
                        <?php echo help('20자 이내의 영문자, 숫자, _ 만 가능합니다.'); ?>
                        <div class="af-stack">
                            <input type="text" value="<?php echo get_sanitize_input($co['co_id']); ?>" name="co_id" id="co_id" <?php echo $w == 'u' ? 'readonly' : 'required'; ?> class="form-input <?php echo $w == 'u' ? '' : 'required'; ?>" size="20" maxlength="20">
                            <?php if ($w == 'u') { ?>
                                <div>
                                    <a href="<?php echo get_pretty_url('content', $co_id); ?>" class="btn btn-sm btn-surface-default-soft">내용확인</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="co_subject" class="form-label">제목<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <input type="text" name="co_subject" value="<?php echo htmlspecialchars2($co['co_subject']); ?>" id="co_subject" required class="form-input required" size="90">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="co_content" class="form-label">내용<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <?php echo editor_html('co_content', get_text(html_purifier($co['co_content']), 0)); ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="co_skin" class="form-label">스킨 디렉토리<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <?php echo get_skin_select('content', 'co_skin', 'co_skin', $co['co_skin'], 'required class="form-select required"'); ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="co_include_head" class="form-label">상단 파일 경로</label>
                    </div>
                    <div class="af-field">
                        <?php echo help("설정값이 없으면 기본 상단 파일을 사용합니다."); ?>
                        <input type="text" name="co_include_head" value="<?php echo get_sanitize_input($co['co_include_head']); ?>" id="co_include_head" size="60" class="form-input">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="co_include_tail" class="form-label">하단 파일 경로</label>
                    </div>
                    <div class="af-field">
                        <?php echo help("설정값이 없으면 기본 하단 파일을 사용합니다."); ?>
                        <input type="text" name="co_include_tail" value="<?php echo get_sanitize_input($co['co_include_tail']); ?>" id="co_include_tail" size="60" class="form-input">
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
                        <label for="co_himg" class="form-label">상단이미지</label>
                    </div>
                    <div class="af-field">
                        <div class="af-stack">
                            <input type="file" name="co_himg" id="co_himg" class="form-input">
                        </div>
                        <?php
                        $himg = G5_DATA_PATH . '/content/' . $co['co_id'] . '_h';
                        $himg_str = '';
                        if (file_exists($himg)) {
                            $size = @getimagesize($himg);
                            if ($size[0] && $size[0] > 750) {
                                $width = 750;
                            } else {
                                $width = $size[0];
                            }

                            echo '<label for="co_himg_del" class="af-check form-label"><input type="checkbox" name="co_himg_del" value="1" id="co_himg_del" class="form-checkbox"><span class="form-label">삭제</span></label>';
                            $himg_str = '<img src="' . G5_DATA_URL . '/content/' . $co['co_id'] . '_h" width="' . $width . '" alt="">';
                        }
                        if ($himg_str) {
                            echo '<div class="banner_or_img">';
                            echo $himg_str;
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="co_timg" class="form-label">하단이미지</label>
                    </div>
                    <div class="af-field">
                        <div class="af-stack">
                            <input type="file" name="co_timg" id="co_timg" class="form-input">
                        </div>
                        <?php
                        $timg = G5_DATA_PATH . '/content/' . $co['co_id'] . '_t';
                        $timg_str = '';
                        if (file_exists($timg)) {
                            $size = @getimagesize($timg);
                            if ($size[0] && $size[0] > 750) {
                                $width = 750;
                            } else {
                                $width = $size[0];
                            }

                            echo '<label for="co_timg_del" class="af-check form-label"><input type="checkbox" name="co_timg_del" value="1" id="co_timg_del" class="form-checkbox"><span class="form-label">삭제</span></label>';
                            $timg_str = '<img src="' . G5_DATA_URL . '/content/' . $co['co_id'] . '_t" width="' . $width . '" alt="">';
                        }
                        if ($timg_str) {
                            echo '<div class="banner_or_img">';
                            echo $timg_str;
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-between border-default-300 border-t border-dashed pt-4">
        <a href="./contentlist.php" class="btn btn-surface-default-soft">목록</a>
        <button type="submit" accesskey="s" class="btn btn-solid-primary">저장</button>
    </div>
</form>

<?php
// [KVE-2018-2089] 취약점 으로 인해 파일 경로 수정시에만 자동등록방지 코드 사용
?>
<script>
    var captcha_chk = false;

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
        var co_include_head = "<?php echo $co['co_include_head']; ?>";
        var co_include_tail = "<?php echo $co['co_include_tail']; ?>";
        var head = jQuery.trim(jQuery("#co_include_head").val());
        var tail = jQuery.trim(jQuery("#co_include_tail").val());

        if (co_include_head !== head || co_include_tail !== tail) {
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
            $("#co_include_head, #co_include_tail").on("change paste keyup", function(e) {
                frm_check_file();
            });

            use_captcha_check();
        }
    });

    function frmcontentform_check(f) {
        errmsg = "";
        errfld = "";

        <?php echo get_editor_js('co_content'); ?>
        <?php echo chk_editor_js('co_content'); ?>

        check_field(f.co_id, "ID를 입력하세요.");
        check_field(f.co_subject, "제목을 입력하세요.");
        check_field(f.co_content, "내용을 입력하세요.");

        if (errmsg != "") {
            alert(errmsg);
            errfld.focus();
            return false;
        }

        if (captcha_chk) {
            <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함 ?>
        }

        return true;
    }
</script>

<?php
require_once G5_ADMIN_PATH . '/admin.tail.php';


