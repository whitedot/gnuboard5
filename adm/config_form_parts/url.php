<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$is_use_apache = (stripos($_SERVER['SERVER_SOFTWARE'], 'apache') !== false);
$is_use_nginx = (stripos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false);
$is_use_iis = !$is_use_apache && (stripos($_SERVER['SERVER_SOFTWARE'], 'microsoft-iis') !== false);

$is_write_file = false;
$is_apache_need_rules = false;
$is_apache_rewrite = false;

if (!($is_use_apache || $is_use_nginx || $is_use_iis)) {
    $is_use_apache = true;
    $is_use_nginx = true;
}

if ($is_use_nginx) {
    $is_write_file = false;
}

if ($is_use_apache) {
    $is_write_file = (is_writable(G5_PATH) || (file_exists(G5_PATH . '/.htaccess') && is_writable(G5_PATH . '/.htaccess')));
    $is_apache_need_rules = check_need_rewrite_rules();
    $is_apache_rewrite = function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules());
}
?>
<section id="anc_cf_url" class="card">
    <div class="card-header">
        <h2 class="card-title">짧은 주소 설정</h2>
    </div>
    <div class="card-body">
        <p>
            게시판과 컨텐츠 페이지에 짧은 URL 을 사용합니다. <a href="https://sir.kr/manual/g5/286" target="_blank" rel="noopener noreferrer">설정 관련 메뉴얼 보기</a>
            <?php if ($is_use_apache && !$is_use_nginx) { ?>
                <?php if (!$is_apache_rewrite) { ?>
                    <br><strong>Apache 서버인 경우 rewrite_module 이 비활성화 되어 있으면 짧은 주소를 사용할수 없습니다.</strong>
                <?php } elseif (!$is_write_file && $is_apache_need_rules) { ?>
                    <br><strong>짧은 주소 사용시 아래 Apache 설정 코드를 참고하여 설정해 주세요.</strong>
                <?php } ?>
            <?php } ?>
        </p>

        <div class="af-grid">
            <div class="af-row">
                <div class="af-label">
                    <label for="cf_bbs_rewrite_0" class="form-label">짧은 주소 유형</label>
                </div>
                <div class="af-field">
                    <div class="af-stack">
                        <?php
                        $short_url_arrs = array(
                            '0' => array('label' => '사용안함', 'url' => G5_URL . '/board.php?bo_table=free&wr_id=123'),
                            '1' => array('label' => '숫자', 'url' => G5_URL . '/free/123'),
                            '2' => array('label' => '글 이름', 'url' => G5_URL . '/free/안녕하세요/'),
                        );
                        foreach ($short_url_arrs as $k => $v) {
                            $checked = ((int) $config['cf_bbs_rewrite'] === (int) $k) ? 'checked' : '';
                        ?>
                        <label for="cf_bbs_rewrite_<?php echo $k; ?>" class="af-url-option">
                            <span class="af-check form-label">
                                <input name="cf_bbs_rewrite" id="cf_bbs_rewrite_<?php echo $k; ?>" type="radio" value="<?php echo $k; ?>" <?php echo $checked; ?> class="form-radio">
                                <span class="form-label"><?php echo $v['label']; ?></span>
                            </span>
                            <code><?php echo $v['url']; ?></code>
                        </label>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="af-row">
                <div class="af-label">
                    <label class="form-label">설정 코드</label>
                </div>
                <div class="af-field">
                    <details class="af-url-details">
                        <summary class="af-url-summary">설정 코드 보기 / 숨기기</summary>
                        <div class="af-stack">
                            <div class="af-stack">
                                <?php if ($is_use_apache) { ?>
                                    <p>Apache 설정 코드는 <code>.htaccess</code> 파일에 적용할 코드입니다.</p>
                                <?php } ?>
                                <?php if ($is_use_nginx) { ?>
                                    <p>Nginx 설정 코드는 서버의 nginx 설정 파일에 적용할 코드입니다.</p>
                                <?php } ?>
                            </div>

                            <?php if ($is_use_apache) { ?>
                            <div class="af-stack">
                                <?php if (!$is_apache_rewrite) { ?>
                                    <p>Apache 서버인 경우 rewrite_module 이 비활성화 되어 있으면 짧은 주소를 사용할수 없습니다.</p>
                                <?php } elseif (!$is_write_file && $is_apache_need_rules) { ?>
                                    <p>자동으로 .htaccess 파일을 수정 할수 있는 권한이 없습니다.<br>.htaccess 파일이 없다면 생성 후에, 아래 코드가 없으면 코드를 복사하여 붙여넣기 해 주세요.</p>
                                <?php } elseif (!$is_apache_need_rules) { ?>
                                    <p>정상적으로 적용된 상태입니다.</p>
                                <?php } ?>
                                <textarea readonly="readonly" rows="12" class="form-textarea"><?php echo get_mod_rewrite_rules(true); ?></textarea>
                            </div>
                            <?php } ?>

                            <?php if ($is_use_nginx) { ?>
                            <div class="af-stack">
                                <textarea readonly="readonly" rows="12" class="form-textarea"><?php echo get_nginx_conf_rules(true); ?></textarea>
                            </div>
                            <?php } ?>
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>
</section>
