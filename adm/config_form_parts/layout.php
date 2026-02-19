    <section id="anc_cf_lay">
        <h2>레이아웃 추가설정</h2>
        <?php echo $pg_anchor; ?>
        <div>
            <p>기본 설정된 파일 경로 및 script, css 를 추가하거나 변경할 수 있습니다.</p>
        </div>

        <div>
            <div>
                                    <div>
                                            <div><label for="cf_add_script">추가 script, css</label>                    </div>
                                            <div>
                            <?php echo help('HTML의 &lt;/HEAD&gt; 태그위로 추가될 JavaScript와 css 코드를 설정합니다.<br>관리자 페이지에서는 이 코드를 사용하지 않습니다.') ?>
                            <textarea name="cf_add_script" id="cf_add_script"><?php echo get_text($config['cf_add_script']); ?></textarea>
                                            </div>
                                    </div>
            </div>
        </div>
    </section>
