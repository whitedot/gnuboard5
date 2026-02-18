    <section id="anc_cf_sns">
        <h2 class="h2_frm">소셜네트워크서비스(SNS : Social Network Service)</h2>
        <?php echo $pg_anchor ?>

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>소셜네트워크서비스 설정</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="row"><label for="cf_social_login_use">소셜로그인설정</label></th>
                        <td colspan="3">
                            <?php echo help('소셜로그인을 사용합니다. <a href="https://sir.kr/manual/g5/276" class="btn btn_03" target="_blank" style="margin-left:10px" >설정 관련 메뉴얼 보기</a> ') ?>
                            <input type="checkbox" name="cf_social_login_use" value="1" id="cf_social_login_use" <?php echo (!empty($config['cf_social_login_use'])) ? 'checked' : ''; ?>> 사용
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_social_servicelist">소셜로그인설정</label></th>
                        <td colspan="3" class="social_config_explain">
                            <div class="explain_box">
                                <input type="checkbox" name="cf_social_servicelist[]" id="check_social_naver" value="naver" <?php echo option_array_checked('naver', $config['cf_social_servicelist']); ?>>
                                <label for="check_social_naver">네이버 로그인을 사용합니다</label>
                                <div>
                                    <h3>네이버 CallbackURL</h3>
                                    <p><?php echo get_social_callbackurl('naver'); ?></p>
                                </div>
                            </div>
                            <div class="explain_box">
                                <input type="checkbox" name="cf_social_servicelist[]" id="check_social_kakao" value="kakao" <?php echo option_array_checked('kakao', $config['cf_social_servicelist']); ?>>
                                <label for="check_social_kakao">카카오 로그인을 사용합니다</label>
                                <div>
                                    <h3>카카오 로그인 Redirect URI</h3>
                                    <p><?php echo get_social_callbackurl('kakao', true); ?></p>
                                </div>
                            </div>
                            <div class="explain_box">
                                <input type="checkbox" name="cf_social_servicelist[]" id="check_social_facebook" value="facebook" <?php echo option_array_checked('facebook', $config['cf_social_servicelist']); ?>>
                                <label for="check_social_facebook">페이스북 로그인을 사용합니다</label>
                                <div>
                                    <h3>페이스북 유효한 OAuth 리디렉션 URI</h3>
                                    <p><?php echo get_social_callbackurl('facebook'); ?></p>
                                </div>
                            </div>
                            <div class="explain_box">
                                <input type="checkbox" name="cf_social_servicelist[]" id="check_social_google" value="google" <?php echo option_array_checked('google', $config['cf_social_servicelist']); ?>>
                                <label for="check_social_google">구글 로그인을 사용합니다</label>
                                <div>
                                    <h3>구글 승인된 리디렉션 URI</h3>
                                    <p><?php echo get_social_callbackurl('google'); ?></p>
                                </div>
                            </div>
                            <div class="explain_box">
                                <input type="checkbox" name="cf_social_servicelist[]" id="check_social_twitter" value="twitter" <?php echo option_array_checked('twitter', $config['cf_social_servicelist']); ?>>
                                <label for="check_social_twitter">트위터 로그인을 사용합니다</label>
                                <div>
                                    <h3>트위터 CallbackURL</h3>
                                    <p><?php echo get_social_callbackurl('twitter'); ?></p>
                                </div>
                            </div>
                            <div class="explain_box">
                                <input type="checkbox" name="cf_social_servicelist[]" id="check_social_payco" value="payco" <?php echo option_array_checked('payco', $config['cf_social_servicelist']); ?>>
                                <label for="check_social_payco">페이코 로그인을 사용합니다</label>
                                <div>
                                    <h3>페이코 CallbackURL</h3>
                                    <p><?php echo get_social_callbackurl('payco', false, true); ?></p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_naver_clientid">네이버 Client ID</label></th>
                        <td>
                            <input type="text" name="cf_naver_clientid" value="<?php echo get_sanitize_input($config['cf_naver_clientid']); ?>" id="cf_naver_clientid" class="frm_input form-input" size="40"> <a href="https://developers.naver.com/apps/#/register" target="_blank" class="btn_frmline">앱 등록하기</a>
                        </td>
                        <th scope="row"><label for="cf_naver_secret">네이버 Client Secret</label></th>
                        <td>
                            <input type="text" name="cf_naver_secret" value="<?php echo get_sanitize_input($config['cf_naver_secret']); ?>" id="cf_naver_secret" class="frm_input form-input" size="45">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_facebook_appid">페이스북 앱 ID</label></th>
                        <td>
                            <input type="text" name="cf_facebook_appid" value="<?php echo get_sanitize_input($config['cf_facebook_appid']); ?>" id="cf_facebook_appid" class="frm_input form-input" size="40"> <a href="https://developers.facebook.com/apps" target="_blank" class="btn_frmline">앱 등록하기</a>
                        </td>
                        <th scope="row"><label for="cf_facebook_secret">페이스북 앱 Secret</label></th>
                        <td>
                            <input type="text" name="cf_facebook_secret" value="<?php echo get_sanitize_input($config['cf_facebook_secret']); ?>" id="cf_facebook_secret" class="frm_input form-input" size="45">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_twitter_key">트위터 컨슈머 Key</label></th>
                        <td>
                            <input type="text" name="cf_twitter_key" value="<?php echo get_sanitize_input($config['cf_twitter_key']); ?>" id="cf_twitter_key" class="frm_input form-input" size="40"> <a href="https://developer.twitter.com/en/apps" target="_blank" class="btn_frmline">앱 등록하기</a>
                        </td>
                        <th scope="row"><label for="cf_twitter_secret">트위터 컨슈머 Secret</label></th>
                        <td>
                            <input type="text" name="cf_twitter_secret" value="<?php echo get_sanitize_input($config['cf_twitter_secret']); ?>" id="cf_twitter_secret" class="frm_input form-input" size="45">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_google_clientid">구글 Client ID</label></th>
                        <td>
                            <input type="text" name="cf_google_clientid" value="<?php echo get_sanitize_input($config['cf_google_clientid']); ?>" id="cf_google_clientid" class="frm_input form-input" size="40"> <a href="https://console.developers.google.com" target="_blank" class="btn_frmline">앱 등록하기</a>
                        </td>
                        <th scope="row"><label for="cf_google_secret">구글 Client Secret</label></th>
                        <td>
                            <input type="text" name="cf_google_secret" value="<?php echo get_sanitize_input($config['cf_google_secret']); ?>" id="cf_google_secret" class="frm_input form-input" size="45">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_googl_shorturl_apikey">구글 짧은주소 API Key</label></th>
                        <td colspan="3">
                            <input type="text" name="cf_googl_shorturl_apikey" value="<?php echo get_sanitize_input($config['cf_googl_shorturl_apikey']); ?>" id="cf_googl_shorturl_apikey" class="frm_input form-input" size="40"> <a href="http://code.google.com/apis/console/" target="_blank" class="btn_frmline">API Key 등록하기</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_kakao_rest_key">카카오 REST API 키</label></th>
                        <td>
                            <input type="text" name="cf_kakao_rest_key" value="<?php echo get_sanitize_input($config['cf_kakao_rest_key']); ?>" id="cf_kakao_rest_key" class="frm_input form-input" size="40"> <a href="https://developers.kakao.com/product/kakaoLogin" target="_blank" class="btn_frmline">앱 등록하기</a>
                        </td>
                        <th scope="row"><label for="cf_kakao_client_secret">카카오 Client Secret</label></th>
                        <td>
                            <input type="text" name="cf_kakao_client_secret" value="<?php echo get_sanitize_input($config['cf_kakao_client_secret']); ?>" id="cf_kakao_client_secret" class="frm_input form-input" size="45">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_kakao_js_apikey">카카오 JavaScript 키</label></th>
                        <td colspan="3">
                            <input type="text" name="cf_kakao_js_apikey" value="<?php echo get_sanitize_input($config['cf_kakao_js_apikey']); ?>" id="cf_kakao_js_apikey" class="frm_input form-input" size="45">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="cf_payco_clientid">페이코 Client ID</label></th>
                        <td>
                            <input type="text" name="cf_payco_clientid" value="<?php echo get_sanitize_input($config['cf_payco_clientid']); ?>" id="cf_payco_clientid" class="frm_input form-input" size="40"> <a href="https://developers.payco.com/guide" target="_blank" class="btn_frmline">앱 등록하기</a>
                        </td>
                        <th scope="row"><label for="cf_payco_secret">페이코 Secret</label></th>
                        <td>
                            <input type="text" name="cf_payco_secret" value="<?php echo get_sanitize_input($config['cf_payco_secret']); ?>" id="cf_payco_secret" class="frm_input form-input" size="45">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
