<section id="anc_sitfrm_optional">
    <h2>상세설명설정</h2>
    <?php echo $pg_anchor; ?>

    
        
        
        
        
                <div class="ui-form-row">
            <div class="ui-form-label">상품상단내용</div>
            <div class="ui-form-field">
            <div><?php echo help("상품상세설명 페이지 상단에 출력하는 HTML 내용입니다."); ?><?php echo editor_html('it_head_html', get_text(html_purifier($it['it_head_html']), 0)); ?></div>
            <div><input type="checkbox" name="chk_ca_it_head_html" value="1" id="chk_ca_it_head_html">
                <label for="chk_ca_it_head_html">분류적용</label>
                <input type="checkbox" name="chk_all_it_head_html" value="1" id="chk_all_it_head_html">
                <label for="chk_all_it_head_html">전체적용</label></div>
        </div>
        </div>
                <div class="ui-form-row">
            <div class="ui-form-label">상품하단내용</div>
            <div class="ui-form-field">
            <div><?php echo help("상품상세설명 페이지 하단에 출력하는 HTML 내용입니다."); ?><?php echo editor_html('it_tail_html', get_text(html_purifier($it['it_tail_html']), 0)); ?></div>
            <div><input type="checkbox" name="chk_ca_it_tail_html" value="1" id="chk_ca_it_tail_html">
                <label for="chk_ca_it_tail_html">분류적용</label>
                <input type="checkbox" name="chk_all_it_tail_html" value="1" id="chk_all_it_tail_html">
                <label for="chk_all_it_tail_html">전체적용</label></div>
        </div>
        </div>
        
        
    
</section>
