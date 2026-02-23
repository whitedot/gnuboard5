<section id="anc_mb_profile">
    <h2>프로필 및 메모</h2>
    
        
            
            
            
                        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_signature">서명</label></div>
            <div class="ui-form-field"><textarea name="mb_signature" id="mb_signature"><?php echo html_purifier($mb['mb_signature']); ?></textarea></div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_profile">자기 소개</label></div>
            <div class="ui-form-field"><textarea name="mb_profile" id="mb_profile"><?php echo html_purifier($mb['mb_profile']); ?></textarea></div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_memo">메모</label></div>
            <div class="ui-form-field"><textarea name="mb_memo" id="mb_memo"><?php echo html_purifier($mb['mb_memo']); ?></textarea></div>
        </div>
            
        
    
</section>
