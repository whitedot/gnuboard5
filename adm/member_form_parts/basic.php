<section id="anc_mb_basic">
    <h2>기본 정보</h2>
    
        
            
            
            
                        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_id">아이디<?php echo $sound_only ?></label></div>
            <div class="ui-form-field"><input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="<?php echo $required_mb_id_class ?>" size="15" maxlength="20">
                        <?php if ($w == 'u') { ?><a href="./boardgroupmember_form.php?mb_id=<?php echo $mb['mb_id'] ?>">접근가능그룹보기</a><?php } ?></div>
        </div>
        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_password">비밀번호<?php echo $sound_only ?></label></div>
            <div class="ui-form-field"><div>
                        <input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="<?php echo $required_mb_password ?>" size="15" maxlength="20">
                        </div>
                        <div id="mb_password_captcha_wrap" style="display:none">
                            <?php
                            require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
                            $captcha_html = captcha_html();
                            $captcha_js   = chk_captcha_js();
                            echo $captcha_html;
                            ?>
                        </div></div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_name">이름(실명)<strong>필수</strong></label></div>
            <div class="ui-form-field"><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required" size="15" maxlength="20"></div>
        </div>
        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_nick">닉네임<strong>필수</strong></label></div>
            <div class="ui-form-field"><input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick" required class="required" size="15" maxlength="20"></div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_level">회원 권한</label></div>
            <div class="ui-form-field"><?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?></div>
        </div>
        <div class="ui-form-row">
            <div class="ui-form-label">포인트</div>
            <div class="ui-form-field"><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $mb['mb_id'] ?>" target="_blank"><?php echo number_format($mb['mb_point']) ?></a> 점</div>
        </div>
                        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_email">E-mail<strong>필수</strong></label></div>
            <div class="ui-form-field"><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required" size="30"></div>
        </div>
        <div class="ui-form-row">
            <div class="ui-form-label"><label for="mb_homepage">홈페이지</label></div>
            <div class="ui-form-field"><input type="text" name="mb_homepage" value="<?php echo $mb['mb_homepage'] ?>" id="mb_homepage" maxlength="255" size="15"></div>
        </div>
            
        
    
</section>
