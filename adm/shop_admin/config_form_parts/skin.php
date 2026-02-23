<section id="anc_scf_skin">
    <h2>스킨설정</h2>
    <?php echo $pg_anchor; ?>
    
        <p>상품 분류리스트, 상품상세보기 등 에서 사용할 스킨을 설정합니다.</p>
    

    
        
        
        
        
                <div class="ui-form-row">
            <div class="ui-form-label"><label for="de_shop_skin">PC용 스킨</label></div>
            <div class="ui-form-field"><?php echo get_skin_select('shop', 'de_shop_skin', 'de_shop_skin', $default['de_shop_skin'], 'required'); ?></div>
        </div>
        
        
    
</section>

<button type="button" class="get_shop_skin">테마 스킨설정 가져오기</button>
