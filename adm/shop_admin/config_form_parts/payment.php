<section id ="anc_scf_payment">
    <h2 class="section-title">결제설정</h2>
    <?php echo $pg_anchor; ?>

    <div class="form-card table-shell">
        <table>
        <caption>결제설정 입력</caption>
        <colgroup>
            <col class="col-4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="de_bank_use">무통장입금사용</label></th>
            <td>
                <?php echo help("주문시 무통장으로 입금을 가능하게 할것인지를 설정합니다.\n사용할 경우 은행계좌번호를 반드시 입력하여 주십시오.", 50); ?>
                <select id="de_bank_use" name="de_bank_use">
                    <option value="0" <?php echo get_selected($default['de_bank_use'], 0); ?>>사용안함</option>
                    <option value="1" <?php echo get_selected($default['de_bank_use'], 1); ?>>사용</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_bank_account">은행계좌번호</label></th>
            <td>
                <textarea name="de_bank_account" id="de_bank_account"><?php echo html_purifier($default['de_bank_account']); ?></textarea>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_iche_use">계좌이체 결제사용</label></th>
            <td>
            <?php echo help("주문시 실시간 계좌이체를 가능하게 할것인지를 설정합니다.", 50); ?>
                <select id="de_iche_use" name="de_iche_use">
                    <option value="0" <?php echo get_selected($default['de_iche_use'], 0); ?>>사용안함</option>
                    <option value="1" <?php echo get_selected($default['de_iche_use'], 1); ?>>사용</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_vbank_use">가상계좌 결제사용</label></th>
            <td>
                <?php echo help("주문별로 유일하게 생성되는 일회용 계좌번호입니다. 주문자가 가상계좌에 입금시 상점에 실시간으로 통보가 되므로 업무처리가 빨라집니다.", 50); ?>
                <select name="de_vbank_use" id="de_vbank_use">
                    <option value="0" <?php echo get_selected($default['de_vbank_use'], 0); ?>>사용안함</option>
                    <option value="1" <?php echo get_selected($default['de_vbank_use'], 1); ?>>사용</option>
                </select>
            </td>
        </tr>
        <tr id="kcp_vbank_url" class="pg_vbank_url">
            <th scope="row">NHN KCP 가상계좌<br>입금통보 URL</th>
            <td>
                <?php echo help("NHN KCP 가상계좌 사용시 다음 주소를 <strong><a href=\"http://admin.kcp.co.kr\" target=\"_blank\">NHN KCP 관리자</a> &gt; 상점정보관리 &gt; 정보변경 &gt; 공통URL 정보 &gt; 공통URL 변경후</strong>에 넣으셔야 상점에 자동으로 입금 통보됩니다."); ?>
                <?php echo G5_SHOP_URL; ?>/settle_kcp_common.php</td>
        </tr>
        <tr id="inicis_vbank_url" class="pg_vbank_url">
            <th scope="row">KG이니시스 가상계좌<br>입금통보 URL</th>
            <td>
                <?php echo help("KG이니시스 가상계좌 사용시 다음 주소를 <strong><a href=\"https://iniweb.inicis.com/\" target=\"_blank\">KG이니시스 관리자</a> &gt; 거래내역 &gt; 가상계좌 &gt; 입금통보방식선택 &gt; URL 수신 설정</strong>에 넣으셔야 상점에 자동으로 입금 통보됩니다."); ?>
                <?php echo G5_SHOP_URL; ?>/settle_inicis_common.php</td>
        </tr>
        <tr id="nicepay_vbank_url" class="pg_vbank_url">
            <th scope="row">NICEPAY 가상계좌<br>입금통보 URL</th>
            <td>
                <?php echo help("NICEPAY 가상계좌 사용시 다음 주소를 <strong><a href=\"https://npg.nicepay.co.kr/\" target=\"_blank\">NICEPAY 관리자</a> &gt; 가맹점관리자페이지 설정 (메인화면 → 가맹점정보 클릭)</strong>에 넣으셔야 상점에 자동으로 입금 통보됩니다."); ?>
                <?php echo G5_SHOP_URL; ?>/settle_nicepay_common.php</td>
        </tr>
        <tr id="toss_vbank_url" class="pg_vbank_url">
            <th scope="row">토스페이먼츠 가상계좌<br>입금통보 URL</th>
            <td>
                <?php echo help("토스페이먼츠 가상계좌 사용시 다음 주소를 <strong><a href=\"https://app.tosspayments.com/\" target=\"_blank\">토스페이먼츠 상점관리자</a> &gt; 개발자센터 &gt; 웹훅 &gt; 웹훅 등록하기에 URL</strong>에 넣으시고, <strong>구독할 이벤트를 [DEPOSIT_CALLBACK]</strong>을 선택하셔야 상점에 자동으로 입금 통보됩니다."); ?>
                <?php echo G5_SHOP_URL; ?>/settle_toss_common.php</td>
        </tr>
        <tr>
            <th scope="row"><label for="de_hp_use">휴대폰결제사용</label></th>
            <td>
                <?php echo help("주문시 휴대폰 결제를 가능하게 할것인지를 설정합니다.", 50); ?>
                <select id="de_hp_use" name="de_hp_use">
                    <option value="0" <?php echo get_selected($default['de_hp_use'], 0); ?>>사용안함</option>
                    <option value="1" <?php echo get_selected($default['de_hp_use'], 1); ?>>사용</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_card_use">신용카드결제사용</label></th>
            <td>
                <?php echo help("주문시 신용카드 결제를 가능하게 할것인지를 설정합니다.", 50); ?>
                <select id="de_card_use" name="de_card_use">
                    <option value="0" <?php echo get_selected($default['de_card_use'], 0); ?>>사용안함</option>
                    <option value="1" <?php echo get_selected($default['de_card_use'], 1); ?>>사용</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_card_noint_use">신용카드 무이자할부사용<br>( KCP 만 해당 )</label></th>
            <td>
                <?php echo help("주문시 신용카드 무이자할부를 가능하게 할것인지를 설정합니다.<br>사용으로 설정하시면 KCP PG사 가맹점 관리자 페이지에서 설정하신 무이자할부 설정이 적용됩니다.<br>사용안함으로 설정하시면 KCP PG사 무이자 이벤트 카드를 제외한 모든 카드의 무이자 설정이 적용되지 않습니다.", 50); ?>
                <select id="de_card_noint_use" name="de_card_noint_use">
                    <option value="0" <?php echo get_selected($default['de_card_noint_use'], 0); ?>>사용안함</option>
                    <option value="1" <?php echo get_selected($default['de_card_noint_use'], 1); ?>>사용</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_easy_pay_use">PG사 간편결제 버튼 사용</label></th>
            <td>
                <?php echo help("주문서 작성 페이지에 PG사 간편결제(PAYCO, PAYNOW, KPAY) 버튼의 별도 사용 여부를 설정합니다.", 50); ?>
                <select id="de_easy_pay_use" name="de_easy_pay_use">
                    <option value="0" <?php echo get_selected($default['de_easy_pay_use'], 0); ?>>노출안함</option>
                    <option value="1" <?php echo get_selected($default['de_easy_pay_use'], 1); ?>>노출함</option>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="de_taxsave_use">현금영수증<br>발급사용</label></th>
            <td>
                <?php echo help("관리자는 설정에 관계없이 <a href=\"".G5_ADMIN_URL."/shop_admin/orderlist.php\">주문내역</a> &gt; 보기에서 발급이 가능합니다.\n현금영수증 발급 취소는 PG사에서 지원하는 현금영수증 취소 기능을 사용하시기 바랍니다.", 50); ?>
                <select id="de_taxsave_use" name="de_taxsave_use">
                    <option value="0" <?php echo get_selected($default['de_taxsave_use'], 0); ?>>사용안함</option>
                    <option value="1" <?php echo get_selected($default['de_taxsave_use'], 1); ?>>사용</option>
                </select>
            </td>
        </tr>
        <?php
        $account_checked = $vbank_checked = $transfer_checked = '';

        if (strstr($default['de_taxsave_types'], 'account')) {
            $account_checked = 'checked="checked"';
        }
        if (strstr($default['de_taxsave_types'], 'vbank')) {
            $vbank_checked = 'checked="checked"';
        }
        if (strstr($default['de_taxsave_types'], 'transfer')) {
            $transfer_checked = 'checked="checked"';
        }
        ?>
        <tr id="de_taxsave_types" class="de_taxsave_types">
            <th scope="row">현금영수증<br>적용수단</th>
            <td>
                <?php echo help("현금영수증 발급 사용일 경우 해당됩니다.<br>현금 영수증 발급은 무통장입금, 가상계좌, 실시간계좌에만 적용됩니다.<br>아래 체크된 수단에 한해서 회원이 직접 주문 보기 페이지에서 현금영수증을 발급 받을수 있습니다.<br>!!! 만약 PG사 상점관리자에서 가상계좌 또는 실시간계좌이체가 자동으로 현금영수증이 발급되는 경우이면, 아래 가상계좌와 실시간계좌이체 체크박스를 해제하여 사용해 주세요.( 중복으로 발급되는 것을 막기 위함입니다. )", 50); ?>
                <input type="checkbox" id="de_taxsave_types_account" name="de_taxsave_types_account" value="account" <?php echo $account_checked; ?> > <label for="de_taxsave_types_account" disabled>무통장입금</label><br>
                <input type="checkbox" id="de_taxsave_types_vbank" name="de_taxsave_types_vbank" value="vbank" <?php echo $vbank_checked; ?> > <label for="de_taxsave_types_vbank">가상계좌</label><br>
                <input type="checkbox" id="de_taxsave_types_transfer" name="de_taxsave_types_transfer" value="transfer" <?php echo $transfer_checked; ?> > <label for="de_taxsave_types_transfer">실시간계좌이체</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="cf_use_point">포인트 사용</label></th>
            <td>
                <?php echo help("<a href=\"".G5_ADMIN_URL."/config_form.php#frm_board\" target=\"_blank\">환경설정 &gt; 기본환경설정</a>과 동일한 설정입니다."); ?>
                <input type="checkbox" name="cf_use_point" value="1" id="cf_use_point"<?php echo $config['cf_use_point']?' checked':''; ?>> 사용
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_settle_min_point">결제 최소포인트</label></th>
            <td>
                <?php echo help("회원의 포인트가 설정값 이상일 경우만 주문시 결제에 사용할 수 있습니다.\n포인트 사용을 하지 않는 경우에는 의미가 없습니다."); ?>
                <input type="text" name="de_settle_min_point" value="<?php echo get_sanitize_input($default['de_settle_min_point']); ?>" id="de_settle_min_point" class="form-input" size="10"> 점
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_settle_max_point">최대 결제포인트</label></th>
            <td>
                <?php echo help("주문 결제시 최대로 사용할 수 있는 포인트를 설정합니다.\n포인트 사용을 하지 않는 경우에는 의미가 없습니다."); ?>
                <input type="text" name="de_settle_max_point" value="<?php echo get_sanitize_input($default['de_settle_max_point']); ?>" id="de_settle_max_point" class="form-input" size="10"> 점
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_settle_point_unit">결제 포인트단위</label></th>
            <td>
                <?php echo help("주문 결제시 사용되는 포인트의 절사 단위를 설정합니다."); ?>
                <select id="de_settle_point_unit" name="de_settle_point_unit">
                    <option value="100" <?php echo get_selected($default['de_settle_point_unit'], 100); ?>>100</option>
                    <option value="10"  <?php echo get_selected($default['de_settle_point_unit'],  10); ?>>10</option>
                    <option value="1"   <?php echo get_selected($default['de_settle_point_unit'],   1); ?>>1</option>
                </select> 점
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_card_point">포인트부여</label></th>
            <td>
                <?php echo help("신용카드, 계좌이체, 휴대폰 결제시 포인트를 부여할지를 설정합니다. (기본값은 '아니오')"); ?>
                <select id="de_card_point" name="de_card_point">
                    <option value="0" <?php echo get_selected($default['de_card_point'], 0); ?>>아니오</option>
                    <option value="1" <?php echo get_selected($default['de_card_point'], 1); ?>>예</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_point_days">주문완료 포인트</label></th>
            <td>
                <?php echo help("주문자가 회원일 경우에만 주문완료시 포인트를 지급합니다. 주문취소, 반품 등을 고려하여 포인트를 지급할 적당한 기간을 입력하십시오. (기본값은 7일)\n0일로 설정하는 경우에는 주문완료와 동시에 포인트를 지급합니다."); ?>
                주문 완료 <input type="text" name="de_point_days" value="<?php echo get_sanitize_input($default['de_point_days']); ?>" id="de_point_days" class="form-input" size="2"> 일 이후에 포인트를 지급
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_pg_service">결제대행사</label></th>
            <td>
                <input type="hidden" name="de_pg_service" id="de_pg_service" value="<?php echo $default['de_pg_service']; ?>" >
                <?php echo help('쇼핑몰에서 사용할 결제대행사를 선택합니다.'); ?>
                <ul class="de_pg_tab">
                    <li class="<?php if($default['de_pg_service'] == 'kcp') echo 'tab-current'; ?>"><a href="#kcp_info_anchor" data-value="kcp" title="NHN KCP 선택하기" >NHN KCP</a></li>
                    <li class="<?php if($default['de_pg_service'] == 'lg') echo 'tab-current'; ?>"><a href="#lg_info_anchor" data-value="lg" title="토스페이먼츠(구버전) 선택하기">토스페이먼츠(구버전)</a></li>
                    <li class="<?php if($default['de_pg_service'] == 'toss') echo 'tab-current'; ?>"><a href="#lg_info_anchor" data-value="toss" title="토스페이먼츠 선택하기">토스페이먼츠</a></li>
                    <li class="<?php if($default['de_pg_service'] == 'inicis') echo 'tab-current'; ?>"><a href="#inicis_info_anchor" data-value="inicis" title="KG이니시스 선택하기">KG이니시스</a></li>
                    <li class="<?php if($default['de_pg_service'] == 'nicepay') echo 'tab-current'; ?>"><a href="#nicepay_info_anchor" data-value="nicepay" title="NICEPAY 선택하기">NICEPAY</a></li>
                </ul>
            </td>
        </tr>
        <tr class="pg_info_fld kcp_info_fld" id="kcp_info_anchor">
            <th scope="row">
                <label for="de_kcp_mid">KCP SITE CODE</label><br>
                <a href="http://sir.kr/main/service/p_pg.php" target="_blank" id="scf_kcpreg" class="kcp_btn">NHN KCP 신청하기</a>
            </th>
            <td>
                <?php echo help("NHN KCP 에서 받은 SR 로 시작하는 영대문자, 숫자 혼용 총 5자리 중 SR 을 제외한 나머지 3자리 SITE CODE 를 입력하세요.\n만약, 사이트코드가 SR로 시작하지 않는다면 NHN KCP에 사이트코드 변경 요청을 하십시오. 예) SR9A3"); ?>
                <span class="sitecode">SR</span> <input type="text" name="de_kcp_mid" value="<?php echo get_sanitize_input($default['de_kcp_mid']); ?>" id="de_kcp_mid" class="form-input code_input" size="2" maxlength="3"> 영대문자, 숫자 혼용 3자리
            </td>
        </tr>
        <tr class="pg_info_fld kcp_info_fld">
            <th scope="row"><label for="de_kcp_site_key">NHN KCP SITE KEY</label></th>
            <td>
                <?php echo help("25자리 영대소문자와 숫자 - 그리고 _ 로 이루어 집니다. SITE KEY 발급 NHN KCP 전화: 1544-8660\n예) 1Q9YRV83gz6TukH8PjH0xFf__"); ?>
                <input type="text" name="de_kcp_site_key" value="<?php echo get_sanitize_input($default['de_kcp_site_key']); ?>" id="de_kcp_site_key" class="form-input" size="36" maxlength="25">
            </td>
        </tr>
        <tr class="pg_info_fld kcp_info_fld">
            <th scope="row"><label for="de_kcp_easy_pays">NHN KCP 간편결제</label></th>
            <td>
                <?php echo help("체크시 NHN KCP 간편결제들을 활성화 합니다.\nNHN_KCP > 네이버페이, 카카오페이는 테스트결제가 되지 않습니다.\n애플페이는 IOS 기기에 모바일결제만 가능합니다."); ?>
                <input type="checkbox" id="de_easy_nhnkcp_payco" name="de_easy_pays[]" value="nhnkcp_payco" <?php if(stripos($default['de_easy_pay_services'], 'nhnkcp_payco') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nhnkcp_payco" disabled>PAYCO (페이코)</label><br>
                <input type="checkbox" id="de_easy_nhnkcp_naverpay" name="de_easy_pays[]" value="nhnkcp_naverpay" <?php if(stripos($default['de_easy_pay_services'], 'nhnkcp_naverpay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nhnkcp_naverpay">NAVERPAY (네이버페이)</label><br>
                <input type="checkbox" id="de_easy_nhnkcp_kakaopay" name="de_easy_pays[]" value="nhnkcp_kakaopay" <?php if(stripos($default['de_easy_pay_services'], 'nhnkcp_kakaopay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nhnkcp_kakaopay">KAKAOPAY (카카오페이)</label><br>
                <input type="checkbox" id="de_easy_nhnkcp_applepay" name="de_easy_pays[]" value="nhnkcp_applepay" <?php if(stripos($default['de_easy_pay_services'], 'nhnkcp_applepay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nhnkcp_applepay">APPLEPAY (애플페이)</label>
            </td>
        </tr>
        <tr class="pg_info_fld kcp_info_fld">
            <th scope="row"><label for="de_global_nhnkcp_naverpay">NHN KCP 네이버페이 사용</label></th>
            <td>
                <?php echo help("체크시 타 PG (토스페이먼츠, KG 이니시스) 사용중일때도 NHN_KCP 를 통한 네이버페이 간편결제를 사용할수 있습니다.\n실결제시 반드시 결제대행사 NHN_KCP 항목에 KCP SITE CODE와 NHN KCP SITE KEY를 입력해야 합니다."); ?>
                <input type="checkbox" id="de_global_nhnkcp_naverpay" name="de_easy_pays[]" value="global_nhnkcp_naverpay" <?php if(stripos($default['de_easy_pay_services'], 'global_nhnkcp_naverpay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_global_nhnkcp_naverpay">NAVERPAY (네이버페이)</label><br>
            </td>
        </tr>
        <tr class="pg_info_fld kcp_info_fld">
            <th scope="row"><label for="used_nhnkcp_naverpay_point">NHN KCP 네이버페이<br>포인트결제 사용</label></th>
            <td>
                <?php echo help("체크시 NHN_KCP 를 통한 네이버페이 결제시 네이버페이 포인트결제가 활성화 됩니다.\n체크를 했는데도 [DR02] 실결제시 가맹점 설정정보가 올바르지 않습니다 라고 메시지가 뜬다면, 체크를 해제하고 NHN_KCP 에 위에서 설정한 KCP SITE CODE 로 네이버페이 포인트 결제가 가능한지 문의해 주세요."); ?>
                <input type="checkbox" id="used_nhnkcp_naverpay_point" name="de_easy_pays[]" value="used_nhnkcp_naverpay_point" <?php if(stripos($default['de_easy_pay_services'], 'used_nhnkcp_naverpay_point') !== false){ echo 'checked="checked"'; } ?> > <label for="used_nhnkcp_naverpay_point">NAVERPAY POINT (네이버페이 포인트 사용)</label><br>
            </td>
        </tr>
        <tr class="pg_info_fld lg_info_fld" id="lg_info_anchor">
            <th scope="row">
                <label for="cf_lg_mid">토스페이먼츠 상점아이디</label><br>
                <a href="http://sir.kr/main/service/lg_pg.php" target="_blank" id="scf_lgreg" class="lg_btn">토스페이먼츠 신청하기</a>
            </th>
            <td>
                <?php echo help("토스페이먼츠에서 받은 si_ 로 시작하는 상점 ID를 입력하세요.\n만약, 상점 ID가 si_로 시작하지 않는다면 토스페이먼츠에 사이트코드 변경 요청을 하십시오. 예) si_lguplus\n<a href=\"".G5_ADMIN_URL."/config_form.php#anc_cf_cert\">기본환경설정 &gt; 본인확인</a> 설정의 토스페이먼츠 상점아이디와 동일합니다."); ?>
                <span class="sitecode">si_</span> <input type="text" name="cf_lg_mid" value="<?php echo get_sanitize_input($config['cf_lg_mid']); ?>" id="cf_lg_mid" class="form-input code_input" size="10" maxlength="20"> 영문자, 숫자 혼용
            </td>
        </tr>
        <tr class="pg_info_fld lg_info_fld">
            <th scope="row"><label for="cf_lg_mert_key">토스페이먼츠(구버전) MERT KEY</label></th>
            <td>
                <?php echo help("토스페이먼츠(구버전) 상점 MertKey는 상점관리자 -> 개발자센터 -> API키 -> 머트 키에서 확인하실 수 있습니다.\n예) 95160cce09854ef44d2edb2bfb05f9f3"); ?>
                <input type="text" name="cf_lg_mert_key" value="<?php echo get_sanitize_input($config['cf_lg_mert_key']); ?>" id="cf_lg_mert_key" class="form-input" size="36" maxlength="50">
            </td>
        </tr>
        <tr class="pg_info_fld lg_info_fld_v2">
            <th scope="row"><label for="cf_toss_client_key">토스페이먼츠 API Client Key</label></th>
            <td>
                <?php echo help("토스페이먼츠 API 클라이언트 키는 상점관리자 -> 개발자센터 -> API키 -> 클라이언트 키에서 확인하실 수 있습니다. 예) live_ck_tosspayment\n실결제용 [라이브] 키와 테스트용 [테스트] 키는 서로 다르므로, <b>테스트로 결제시에는 [테스트] 키</b>로 변경하여 사용해주시기 바랍니다. 예) 테스트 키: test_ck_tosspayment"); ?>
                <input type="text" name="cf_toss_client_key" value="<?php echo get_sanitize_input($config['cf_toss_client_key']); ?>" id="cf_toss_client_key" class="form-input" size="40" maxlength="50">
            </td>
        </tr>
        <tr class="pg_info_fld lg_info_fld_v2">
            <th scope="row"><label for="cf_toss_secret_key">토스페이먼츠 API Secret Key</label></th>
            <td>
                <?php echo help("토스페이먼츠 API 시크릿 키는 상점관리자 -> 개발자센터 -> API키 -> 시크릿 키에서 확인하실 수 있습니다. 예) live_sk_tosspayment\n실결제용 [라이브] 키와 테스트용 [테스트] 키는 서로 다르므로, <b>테스트로 결제시에는 [테스트] 키</b>로 변경하여 사용해주시기 바랍니다. 예) 테스트 키: test_sk_tosspayment"); ?>
                <input type="text" name="cf_toss_secret_key" value="<?php echo get_sanitize_input($config['cf_toss_secret_key']); ?>" id="cf_toss_secret_key" class="form-input" size="40" maxlength="50">
            </td>
        </tr>
        <tr class="pg_info_fld inicis_info_fld" id="inicis_info_anchor">
            <th scope="row">
                <label for="de_inicis_mid">KG이니시스 상점아이디</label><br>
                <a href="http://sir.kr/main/service/inicis_pg.php" target="_blank" id="scf_kgreg" class="kg_btn">KG이니시스 신청하기</a>
            </th>
            <td>
                <?php echo help("KG이니시스로 부터 발급 받으신 상점아이디(MID) 10자리 중 SIR 을 제외한 나머지 7자리를 입력 합니다.\n만약, 상점아이디가 SIR로 시작하지 않는다면 계약담당자에게 변경 요청을 해주시기 바랍니다. (Tel. 02-3430-5858) 예) SIRpaytest"); ?>
                <span class="sitecode">SIR</span> <input type="text" name="de_inicis_mid" value="<?php echo $default['de_inicis_mid']; ?>" id="de_inicis_mid" class="form-input code_input" size="10" maxlength="10"> 영문소문자(숫자포함 가능)
            </td>
        </tr>
        <tr class="pg_info_fld inicis_info_fld">
            <th scope="row"><label for="de_inicis_sign_key">KG이니시스 웹결제 사인키</label></th>
            <td>
                <?php echo help("KG이니시스에서 발급받은 웹결제 사인키를 입력합니다.\n<a href='https://iniweb.inicis.com/' target='_blank'>KG이니시스 가맹점관리자</a> > 상점정보 > 계약정보 > 부가정보의 웹결제 signkey생성 조회 버튼 클릭, 팝업창에서 생성 버튼 클릭 후 해당 값을 입력합니다."); ?>
                <input type="text" name="de_inicis_sign_key" value="<?php echo get_sanitize_input($default['de_inicis_sign_key']); ?>" id="de_inicis_sign_key" class="form-input" size="40" maxlength="50">
            </td>
        </tr>
        <tr class="pg_info_fld inicis_info_fld">
            <th scope="row"><label for="de_inicis_iniapi_key">KG이니시스 INIAPI KEY</label></th>
            <td>
                <?php echo help("<a href='https://iniweb.inicis.com/' target='_blank'>KG이니시스 가맹점관리자</a> > 상점정보 > 계약정보 > 부가정보 > INIAPI key 생성 조회 하여 KEY를 여기에 입력합니다.\n이 항목은 영카트 주문에서 kg이니시스 PG 결제 취소, 부분취소, 에스크로 배송등록, 현금영수증 발급에 필요합니다."); ?>
                <input type="text" name="de_inicis_iniapi_key" value="<?php echo get_sanitize_input($default['de_inicis_iniapi_key']); ?>" id="de_inicis_iniapi_key" class="form-input" size="30" maxlength="30">
            </td>
        </tr>
        <tr class="pg_info_fld inicis_info_fld">
            <th scope="row"><label for="de_inicis_iniapi_iv">KG이니시스 INIAPI IV</label></th>
            <td>
                <?php echo help("<a href='https://iniweb.inicis.com/' target='_blank'>KG이니시스 가맹점관리자</a> > 상점정보 > 계약정보 > 부가정보 > INIAPI IV 생성 조회 하여 KEY를 여기에 입력합니다.\n이 항목은 영카트 주문에서 kg이니시스 현금영수증 발급에 필요합니다."); ?>
                <input type="text" name="de_inicis_iniapi_iv" value="<?php echo get_sanitize_input($default['de_inicis_iniapi_iv']); ?>" id="de_inicis_iniapi_iv" class="form-input" size="30" maxlength="30">
            </td>
        </tr>
        <tr class="pg_info_fld inicis_info_fld">
            <th scope="row">
                <label for="de_samsung_pay_use">KG이니시스 삼성페이 사용</label>
                <a href="http://sir.kr/main/service/samsungpay.php" target="_blank" class="kg_btn">삼성페이 서비스신청하기</a>
            </th>
            <td>
                <?php echo help("KG이니시스와 별도로 <strong>삼성페이 사용 계약을 하신 경우</strong>에만 체크해주세요. (모바일 주문서 결제수단에 삼성페이가 노출됩니다.) <br >실결제시 반드시 결제대행사 KG이니시스 항목에 상점 아이디와 웹결제 사인키를 입력해 주세요.", 50); ?>
                <input type="checkbox" name="de_samsung_pay_use" value="1" id="de_samsung_pay_use"<?php echo $default['de_samsung_pay_use']?' checked':''; ?>> <label for="de_samsung_pay_use">사용</label>
            </td>
        </tr>
        <tr class="pg_info_fld inicis_info_fld">
            <th scope="row">
                <label for="de_inicis_lpay_use">KG이니시스 L.pay 사용</label>
            </th>
            <td>
                <?php echo help("체크시 KG이니시스 L.pay를 사용합니다. <br >실결제시 반드시 결제대행사 KG이니시스 항목의 상점 정보( 아이디, 웹결제 사인키 )를 입력해 주세요.", 50); ?>
                <input type="checkbox" name="de_inicis_lpay_use" value="1" id="de_inicis_lpay_use"<?php echo $default['de_inicis_lpay_use']?' checked':''; ?>> <label for="de_inicis_lpay_use">사용</label>
            </td>
        </tr>
        <tr class="pg_info_fld inicis_info_fld">
            <th scope="row">
                <label for="de_inicis_kakaopay_use">KG이니시스 카카오페이 사용</label>
            </th>
            <td>
                <?php echo help("체크시 KG이니시스 결제의 카카오페이를 사용합니다. 주문서 결제수단에 카카오페이가 노출됩니다. <br>실결제시 반드시 결제대행사 KG이니시스 항목의 상점 정보( 아이디, 웹결제 사인키 )를 입력해 주세요.", 50); ?>
                <input type="checkbox" name="de_inicis_kakaopay_use" value="1" id="de_inicis_kakaopay_use"<?php echo $default['de_inicis_kakaopay_use']?' checked':''; ?>> <label for="de_inicis_kakaopay_use">사용</label>
            </td>
        </tr>
        <tr class="pg_info_fld inicis_info_fld">
            <th scope="row">
                <label for="de_inicis_cartpoint_use">KG이니시스 신용카드 포인트 결제</label>
            </th>
            <td>
                <?php echo help("신용카드 포인트 결제에 대해 이니시스와 계약을 맺은 상점에서만 적용하는 옵션입니다.<br>체크시 pc 결제에서는 신용카드 포인트 사용 여부에 대한 팝업창에 사용 버튼과 사용안함 버튼이 표기되어 결제하는 고객의 선택여부에 따라 신용카드 포인트 결제가 가능합니다.<br >모바일에서는 신용카드 포인트 사용이 가능합니다.", 50); ?>
                <input type="checkbox" name="de_inicis_cartpoint_use" value="1" id="de_inicis_cartpoint_use"<?php echo $default['de_inicis_cartpoint_use']?' checked':''; ?>> <label for="de_inicis_cartpoint_use">사용</label>
            </td>
        </tr>
        <tr class="kakao_info_fld">
            <th scope="row">
                <label for="de_kakaopay_mid">카카오페이 상점아이디<br>( KG이니시스 )</label>
                <a href="http://sir.kr/main/service/kakaopay.php?kk=yc5" target="_blank" class="kakao_btn">카카오페이 서비스신청하기</a>
            </th>
            <td>
                <?php echo help("KG이니시스로 부터 카카오페이 간편결제만 사용용도로 발급 받으신 상점아이디(MID) 10자리 중 SIRK 을 제외한 나머지 6자리를 입력 합니다."); ?>
                <span class="sitecode">SIRK</span> <input type="text" name="de_kakaopay_mid" value="<?php echo get_sanitize_input($default['de_kakaopay_mid']); ?>" id="de_kakaopay_mid" class="form-input code_input" size="10" maxlength="7">
            </td>
        </tr>
        <tr class="kakao_info_fld">
            <th scope="row"><label for="de_kakaopay_key">카카오페이 상점키<br>( KG이니시스 )</label></th>
            <td>
                <?php echo help("SIRK****** 아이디로 KG이니시스에서 발급받은 웹결제 사인키를 입력합니다.\nKG이니시스 상점관리자 > 상점정보 > 계약정보 > 부가정보의 웹결제 signkey생성 조회 버튼 클릭, 팝업창에서 생성 버튼 클릭 후 해당 값을 입력합니다."); ?>
                <input type="text" name="de_kakaopay_key" value="<?php echo get_sanitize_input($default['de_kakaopay_key']); ?>" id="de_kakaopay_key" class="form-input" size="100">
            </td>
        </tr>
        <tr class="kakao_info_fld">
            <th scope="row"><label for="de_kakaopay_cancelpwd">카카오페이 키패스워드<br>( KG이니시스 )</label></th>
            <td>
                <?php echo help("SIRK****** 아이디로 KG이니시스에서 발급받은 4자리 상점 키패스워드를 입력합니다.\nKG이니시스 상점관리자 패스워드와 관련이 없습니다.\n키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오"); ?>
                <input type="text" name="de_kakaopay_cancelpwd" value="<?php echo get_sanitize_input($default['de_kakaopay_cancelpwd']); ?>" id="de_kakaopay_cancelpwd" class="form-input" size="20">
            </td>
        </tr>
        <tr class="kakao_info_fld">
            <th scope="row">
                <label for="de_kakaopay_enckey">KG이니시스<br>카카오페이 사용</label>
            </th>
            <td>
                <?php echo help("체크시 카카오페이 (KG 이니시스)를 사용합니다. <br >KG 이니시스의 SIRK****** 아이디를 받은 상점만 해당됩니다.", 50); ?>
                <input type="checkbox" name="de_kakaopay_enckey" value="1" id="de_kakaopay_enckey"<?php echo $default['de_kakaopay_enckey']?' checked':''; ?>> <label for="de_kakaopay_enckey">사용</label>
            </td>
        </tr>
        <tr class="kakao_info_fld" style="display:none">
            <th scope="row"><label for="de_kakaopay_hashkey">카카오페이 상점 HashKey</label></th>
            <td>
                <?php echo help("카카오페이로 부터 발급 받으신 상점 인증 전용 HashKey를 입력합니다."); ?>
                <input type="text" name="de_kakaopay_hashkey" value="<?php echo get_sanitize_input($default['de_kakaopay_hashkey']); ?>" id="de_kakaopay_hashkey" class="form-input" size="20">
            </td>
        </tr>

        <tr class="pg_info_fld nicepay_info_fld" id="nicepay_info_anchor">
            <th scope="row"><label for="de_nicepay_mid">NICEPAY MID</label><br><a href="http://sir.kr/main/service/nicepayments_pg.php" target="_blank" id="scf_nicepay_reg" class="nicepay_btn">NICEPAY 신청하기</a></th>
            <td>
                <span class="frm_info">NICEPAY로 부터 발급 받으신 상점MID를 SR 을 제외한 나머지 자리를 입력 합니다.<br>NICEPAY 상점관리자 > 가맹점정보 > KEY관리에서 확인 할수 있습니다.<br>만약, 상점아이디가 SR로 시작하지 않는다면 계약담당자에게 변경 요청을 해주시기 바랍니다. 예) SRpaytestm</span>
                <span class="sitecode">SR</span>
                <input type="text" name="de_nicepay_mid" value="<?php echo get_sanitize_input($default['de_nicepay_mid']); ?>" id="de_nicepay_mid" class="form-input" size="12" maxlength="12">
                영문소문자(숫자포함 가능)
            </td>
        </tr>
        <tr class="pg_info_fld nicepay_info_fld">
            <th scope="row"><label for="de_nicepay_key">NICEPAY KEY</label></th>
            <td>
                <input type="text" name="de_nicepay_key" value="<?php echo get_sanitize_input($default['de_nicepay_key']); ?>" id="de_nicepay_key" class="form-input" size="100" maxlength="100">
            </td>
        </tr>

        <tr class="pg_info_fld nicepay_info_fld">
            <th scope="row"><label for="de_nicepay_easy_pays">NICEPAY 간편결제</label></th>
            <td>
                <?php echo help("체크시 NICEPAY 간편결제들을 활성화 합니다.\nNICEPAY > 간편결제는 테스트결제가 되지 않습니다. 실결제에만 정상작동 합니다.\n애플페이는 IOS 기기에 모바일결제만 가능합니다."); ?>
                <input type="checkbox" id="de_easy_nicepay_samsungpay" name="de_easy_pays[]" value="nicepay_samsungpay" <?php if(stripos($default['de_easy_pay_services'], 'nicepay_samsungpay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nicepay_samsungpay" disabled>삼성페이</label><br>
                <input type="checkbox" id="de_easy_nicepay_naverpay" name="de_easy_pays[]" value="nicepay_naverpay" <?php if(stripos($default['de_easy_pay_services'], 'nicepay_naverpay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nicepay_naverpay">NAVERPAY (네이버페이)</label><br>
                <input type="checkbox" id="de_easy_nicepay_kakaopay" name="de_easy_pays[]" value="nicepay_kakaopay" <?php if(stripos($default['de_easy_pay_services'], 'nicepay_kakaopay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nicepay_kakaopay">KAKAOPAY (카카오페이)</label><br>
                <input type="checkbox" id="de_easy_nicepay_applepay" name="de_easy_pays[]" value="nicepay_applepay" <?php if(stripos($default['de_easy_pay_services'], 'nicepay_applepay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nicepay_applepay">APPLEPAY (애플페이)</label><br>
                <input type="checkbox" id="de_easy_nicepay_paycopay" name="de_easy_pays[]" value="nicepay_paycopay" <?php if(stripos($default['de_easy_pay_services'], 'nicepay_paycopay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nicepay_paycopay">페이코</label><br>
                <input type="checkbox" id="de_easy_nicepay_skpay" name="de_easy_pays[]" value="nicepay_skpay" <?php if(stripos($default['de_easy_pay_services'], 'nicepay_skpay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nicepay_skpay">SK페이</label><br>
                <input type="checkbox" id="de_easy_nicepay_ssgpay" name="de_easy_pays[]" value="nicepay_ssgpay" <?php if(stripos($default['de_easy_pay_services'], 'nicepay_ssgpay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nicepay_ssgpay">SSG페이</label><br>
                <input type="checkbox" id="de_easy_nicepay_lpay" name="de_easy_pays[]" value="nicepay_lpay" <?php if(stripos($default['de_easy_pay_services'], 'nicepay_lpay') !== false){ echo 'checked="checked"'; } ?> > <label for="de_easy_nicepay_lpay">LPAY</label>
            </td>
        </tr>

        <?php if (defined('G5_SHOP_DIRECT_NAVERPAY') && G5_SHOP_DIRECT_NAVERPAY) { ?>
        <tr class="naver_info_fld">
            <th scope="row">
                <label for="de_naverpay_mid">네이버페이 가맹점 아이디</label>
                <a href="http://sir.kr/main/service/naverpay.php" target="_blank" class="naver_btn">네이버페이 서비스신청하기</a>
            </th>
            <td>
                <?php echo help("네이버페이 가맹점 아이디를 입력합니다."); ?>
                <input type="text" name="de_naverpay_mid" value="<?php echo get_sanitize_input($default['de_naverpay_mid']); ?>" id="de_naverpay_mid" class="form-input" size="20" maxlength="50">
             </td>
        </tr>
        <tr class="naver_info_fld">
            <th scope="row">
                <label for="de_naverpay_cert_key">네이버페이 가맹점 인증키</label>
            </th>
            <td>
                <?php echo help("네이버페이 가맹점 인증키를 입력합니다."); ?>
                <input type="text" name="de_naverpay_cert_key" value="<?php echo get_sanitize_input($default['de_naverpay_cert_key']); ?>" id="de_naverpay_cert_key" class="form-input" size="50" maxlength="100">
             </td>
        </tr>
        <tr class="naver_info_fld">
            <th scope="row">
                <label for="de_naverpay_button_key">네이버페이 버튼 인증키</label>
            </th>
            <td>
                <?php echo help("네이버페이 버튼 인증키를 입력합니다."); ?>
                <input type="text" name="de_naverpay_button_key" value="<?php echo get_sanitize_input($default['de_naverpay_button_key']); ?>" id="de_naverpay_button_key" class="form-input" size="50" maxlength="100">
             </td>
        </tr>
        <tr class="naver_info_fld">
            <th scope="row"><label for="de_naverpay_test">네이버페이 결제테스트</label></th>
            <td>
                <?php echo help("네이버페이 결제테스트 여부를 설정합니다. 검수 과정 중에는 <strong>예</strong>로 설정해야 하며 최종 승인 후 <strong>아니오</strong>로 설정합니다."); ?>
                <select id="de_naverpay_test" name="de_naverpay_test">
                    <option value="1" <?php echo get_selected($default['de_naverpay_test'], 1); ?>>예</option>
                    <option value="0" <?php echo get_selected($default['de_naverpay_test'], 0); ?>>아니오</option>
                </select>
            </td>
        </tr>
        <tr class="naver_info_fld">
            <th scope="row">
                <label for="de_naverpay_mb_id">네이버페이 결제테스트 아이디</label>
            </th>
            <td>
                <?php echo help("네이버페이 결제테스트를 위한 테스트 회원 아이디를 입력합니다. 네이버페이 검수 과정에서 필요합니다."); ?>
                <input type="text" name="de_naverpay_mb_id" value="<?php echo get_sanitize_input($default['de_naverpay_mb_id']); ?>" id="de_naverpay_mb_id" class="form-input" size="20" maxlength="20">
             </td>
        </tr>
        <tr class="naver_info_fld">
            <th scope="row">네이버페이 상품정보 XML URL</th>
            <td>
                <?php echo help("네이버페이에 상품정보를 XML 데이터로 제공하는 페이지입니다. 검수과정에서 아래의 URL 정보를 제공해야 합니다."); ?>
                <?php echo G5_SHOP_URL; ?>/naverpay/naverpay_item.php
             </td>
        </tr>
        <tr class="naver_info_fld">
            <th scope="row">
                <label for="de_naverpay_sendcost">네이버페이 추가배송비 안내</label>
            </th>
            <td>
                <?php echo help("네이버페이를 통한 결제 때 구매자에게 보여질 추가배송비 내용을 입력합니다.<br>예) 제주도 3,000원 추가, 제주도 외 도서·산간 지역 5,000원 추가"); ?>
                <input type="text" name="de_naverpay_sendcost" value="<?php echo get_sanitize_input($default['de_naverpay_sendcost']); ?>" id="de_naverpay_sendcost" class="form-input" size="70">
             </td>
        </tr>
        <?php } // defined('G5_SHOP_DIRECT_NAVERPAY') ?>
        <tr>
            <th scope="row">에스크로 사용</th>
            <td>
                <?php echo help("에스크로 결제를 사용하시려면, 반드시 결제대행사 상점 관리자 페이지에서 에스크로 서비스를 신청하신 후 사용하셔야 합니다.\n에스크로 사용시 배송과의 연동은 되지 않으며 에스크로 결제만 지원됩니다."); ?>
                    <input type="radio" name="de_escrow_use" value="0" <?php echo $default['de_escrow_use']==0?"checked":""; ?> id="de_escrow_use1">
                    <label for="de_escrow_use1">일반결제 사용</label>
                    <input type="radio" name="de_escrow_use" value="1" <?php echo $default['de_escrow_use']==1?"checked":""; ?> id="de_escrow_use2">
                    <label for="de_escrow_use2"> 에스크로결제 사용</label>
            </td>
        </tr>
        <tr>
            <th scope="row">결제 테스트</th>
            <td>
                <?php echo help("PG사의 결제 테스트를 하실 경우에 체크하세요. 결제단위 최소 1,000원"); ?>
                <input type="radio" name="de_card_test" value="0" <?php echo $default['de_card_test']==0?"checked":""; ?> id="de_card_test1">
                <label for="de_card_test1">실결제 </label>
                <input type="radio" name="de_card_test" value="1" <?php echo $default['de_card_test']==1?"checked":""; ?> id="de_card_test2">
                <label for="de_card_test2">테스트결제</label>
                <div class="scf_cardtest kcp_cardtest">
                    <a href="http://admin.kcp.co.kr/" target="_blank" class="btn-inline">실결제 관리자</a>
                    <a href="http://testadmin8.kcp.co.kr/" target="_blank" class="btn-inline">테스트 관리자</a>
                </div>
                <div class="scf_cardtest lg_cardtest">
                    <a href="https://app.tosspayments.com/" target="_blank" class="btn-inline">실결제 관리자</a>
                    <a href="https://pgweb.tosspayments.com/tmert" target="_blank" class="btn-inline">테스트 관리자</a>
                </div>
                <div class="scf_cardtest toss_cardtest">
                    <a href="https://app.tosspayments.com/" target="_blank" class="btn-inline">상점 관리자</a>
                </div>
                <div class="scf_cardtest inicis_cardtest">
                    <a href="https://iniweb.inicis.com/" target="_blank" class="btn-inline">상점 관리자</a>
                </div>
                <div id="scf_cardtest_tip">
                    <strong>일반결제 사용시 테스트 결제</strong>
                    <dl>
                        <dt>신용카드</dt><dd>1000원 이상, 모든 카드가 테스트 되는 것은 아니므로 여러가지 카드로 결제해 보셔야 합니다.<br>(BC, 현대, 롯데, 삼성카드)</dd>
                        <dt>계좌이체</dt><dd>150원 이상, 계좌번호, 비밀번호는 가짜로 입력해도 되며, 주민등록번호는 공인인증서의 것과 일치해야 합니다.</dd>
                        <dt>가상계좌</dt><dd>1원 이상, 모든 은행이 테스트 되는 것은 아니며 "해당 은행 계좌 없음" 자주 발생함.<br>(광주은행, 하나은행)</dd>
                        <dt>휴대폰</dt><dd>1004원, 실결제가 되며 다음날 새벽에 일괄 취소됨</dd>
                    </dl>
                    <strong>에스크로 사용시 테스트 결제</strong><br>
                    <dl>
                        <dt>신용카드</dt><dd>1000원 이상, 모든 카드가 테스트 되는 것은 아니므로 여러가지 카드로 결제해 보셔야 합니다.<br>(BC, 현대, 롯데, 삼성카드)</dd>
                        <dt>계좌이체</dt><dd>150원 이상, 계좌번호, 비밀번호는 가짜로 입력해도 되며, 주민등록번호는 공인인증서의 것과 일치해야 합니다.</dd>
                        <dt>가상계좌</dt><dd>1원 이상, 입금통보는 제대로 되지 않음.</dd>
                        <dt>휴대폰</dt><dd>테스트 지원되지 않음.</dd>
                    </dl>
                    <ul id="kcp_cardtest_tip" class="scf_cardtest_tip_adm scf_cardtest_tip_adm_hide">
                        <li>테스트결제의 <a href="http://testadmin8.kcp.co.kr/assist/login.LoginAction.do" target="_blank">상점관리자</a> 로그인 정보는 NHN KCP로 문의하시기 바랍니다. (기술지원 1544-8661)</li>
                        <li><b>일반결제</b>의 테스트 사이트코드는 <b>T0000</b> 이며, <b>에스크로 결제</b>의 테스트 사이트코드는 <b>T0007</b> 입니다.</li>
                    </ul>
                    <ul id="lg_cardtest_tip" class="scf_cardtest_tip_adm scf_cardtest_tip_adm_hide">
                        <li>테스트결제의 <a href="https://pgweb.tosspayments.com/tmert" target="_blank">상점관리자</a> 로그인 정보는 토스페이먼츠 상점아이디 첫 글자에 t를 추가해서 로그인하시기 바랍니다. 예) tsi_lguplus</li>
                    </ul>
                    <ul id="toss_cardtest_tip" class="scf_cardtest_tip_adm scf_cardtest_tip_adm_hide">
                        <li>테스트 결제 시 <a href="https://app.tosspayments.com/" target="_blank">상점관리자</a> 로그인 정보는 실결제용 키와는 다르니 반드시 <b>[테스트] API 연동 키</b>로 로그인해야 합니다. 예) test_ck_toss</li>
                    </ul>
                    <ul id="inicis_cardtest_tip" class="scf_cardtest_tip_adm scf_cardtest_tip_adm_hide">
                        <li><b>일반결제</b>의 테스트 사이트 mid는 <b>INIpayTest</b> 이며, <b>에스크로 결제</b>의 테스트 사이트 mid는 <b>iniescrow0</b> 입니다.</li>
                    </ul>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_tax_flag_use">복합과세 결제</label></th>
            <td>
                 <?php echo help("복합과세(과세, 비과세) 결제를 사용하려면 체크하십시오.\n복합과세 결제를 사용하기 전 PG사에 별도로 결제 신청을 해주셔야 합니다. 사용시 PG사로 문의하여 주시기 바랍니다."); ?>
                <input type="checkbox" name="de_tax_flag_use" value="1" id="de_tax_flag_use"<?php echo $default['de_tax_flag_use']?' checked':''; ?>> 사용
            </td>
        </tr>
        </tbody>
        </table>
        <script>
        $('#scf_cardtest_tip').addClass('scf_cardtest_tip');
        $('<button type="button" class="scf_cardtest_btn btn-inline">테스트결제 팁 더보기</button>').appendTo('.scf_cardtest');

        $(".scf_cardtest").addClass("scf_cardtest_hide");
        $(".<?php echo $default['de_pg_service']; ?>_cardtest").removeClass("scf_cardtest_hide");
        $("#<?php echo $default['de_pg_service']; ?>_cardtest_tip").removeClass("scf_cardtest_tip_adm_hide");
        </script>
    </div>
</section>
