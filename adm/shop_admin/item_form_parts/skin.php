<section id="anc_sitfrm_skin">
    <h2>스킨설정</h2>
    <?php echo $pg_anchor; ?>
    
        <p>상품상세보기에서 사용할 스킨을 설정합니다.</p>
    

    
        
        
      
        
                <div class="ui-form-row">
            <div class="ui-form-label"><label for="it_skin">스킨</label></div>
            <div class="ui-form-field">
            <div><?php echo get_skin_select('shop', 'it_skin', 'it_skin', $it['it_skin']); ?></div>
            <div><input type="checkbox" name="chk_ca_it_skin" value="1" id="chk_ca_it_skin">
                <label for="chk_ca_it_skin">분류적용</label>
                <input type="checkbox" name="chk_all_it_skin" value="1" id="chk_all_it_skin">
                <label for="chk_all_it_skin">전체적용</label></div>
        </div>
        </div>
        
        
    
</section>
