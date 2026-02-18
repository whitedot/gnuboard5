<section id="anc_mb_history">
    <h2 class="section-title">인증 및 활동 내역</h2>
    <div class="form-card table-shell">
        <table>
            <caption>인증 및 활동 내역</caption>
            <colgroup>
                <col class="col-4">
                <col>
                <col class="col-4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="mb_cert_history">본인인증 내역</label></th>
                    <td colspan="3">
                        <?php
                        $cnt = 0;
                        if ($mb_cert_history) {
                            sql_data_seek($mb_cert_history, 0);
                            while ($row = sql_fetch_array($mb_cert_history)) {
                                $cnt++;
                                $cert_type = '';
                                switch ($row['ch_type']) {
                                    case 'simple':
                                        $cert_type = '간편인증';
                                        break;
                                    case 'hp':
                                        $cert_type = '휴대폰';
                                        break;
                                    case 'ipin':
                                        $cert_type = '아이핀';
                                        break;
                                }
                            ?>
                                <div>
                                    [<?php echo $row['ch_datetime']; ?>]
                                    <?php echo $row['mb_id']; ?> /
                                    <?php echo $row['ch_name']; ?> /
                                    <?php echo $row['ch_hp']; ?> /
                                    <?php echo $cert_type; ?>
                                </div>
                            <?php 
                            }
                        } ?>

                        <?php if ($cnt == 0) { ?>
                            본인인증 내역이 없습니다.
                        <?php } ?>
                    </td>
                </tr>

                <?php if ($w == 'u') { ?>
                    <tr>
                        <th scope="row">회원가입일</th>
                        <td><?php echo $mb['mb_datetime'] ?></td>
                        <th scope="row">최근접속일</th>
                        <td><?php echo $mb['mb_today_login'] ?></td>
                    </tr>
                    <tr>
                        <th scope="row">IP</th>
                        <td colspan="3"><?php echo $mb['mb_ip'] ?></td>
                    </tr>
                    <?php if ($config['cf_use_email_certify']) { ?>
                        <tr>
                            <th scope="row">인증일시</th>
                            <td colspan="3">
                                <?php if ($mb['mb_email_certify'] == '0000-00-00 00:00:00') { ?>
                                    <?php echo help('회원님이 메일을 수신할 수 없는 경우 등에 직접 인증처리를 하실 수 있습니다.') ?>
                                    <input type="checkbox" name="passive_certify" id="passive_certify">
                                    <label for="passive_certify">수동인증</label>
                                <?php } else { ?>
                                    <?php echo $mb['mb_email_certify'] ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                <?php if ($config['cf_use_recommend']) { // 추천인 사용 ?>
                    <tr>
                        <th scope="row">추천인</th>
                        <td colspan="3"><?php echo ((isset($mb['mb_recommend']) && $mb['mb_recommend']) ? get_text($mb['mb_recommend']) : '없음'); ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <th scope="row"><label for="mb_leave_date">탈퇴일자</label></th>
                    <td>
                        <input type="text" name="mb_leave_date" value="<?php echo $mb['mb_leave_date'] ?>" id="mb_leave_date" class="form-input" maxlength="8">
                        <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_leave_date_set_today" onclick="if (this.form.mb_leave_date.value==this.form.mb_leave_date.defaultValue) { this.form.mb_leave_date.value=this.value; } else { this.form.mb_leave_date.value=this.form.mb_leave_date.defaultValue; }">
                        <label for="mb_leave_date_set_today">탈퇴일을 오늘로 지정</label>
                    </td>
                    <th scope="row">접근차단일자</th>
                    <td>
                        <input type="text" name="mb_intercept_date" value="<?php echo $mb['mb_intercept_date'] ?>" id="mb_intercept_date" class="form-input" maxlength="8">
                        <input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_intercept_date_set_today" onclick="if (this.form.mb_intercept_date.value==this.form.mb_intercept_date.defaultValue) { this.form.mb_intercept_date.value=this.value; } else { this.form.mb_intercept_date.value=this.form.mb_intercept_date.defaultValue; }">
                        <label for="mb_intercept_date_set_today">접근차단일을 오늘로 지정</label>
                    </td>
                </tr>

                <?php
                //소셜계정이 있다면
                if (function_exists('social_login_link_account') && $mb['mb_id']) {
                    if ($my_social_accounts = social_login_link_account($mb['mb_id'], false, 'get_data')) { ?>
                        <tr>
                            <th>소셜계정목록</th>
                            <td colspan="3">
                                <ul class="social_link_box">
                                    <li class="social_login_container">
                                        <h4>연결된 소셜 계정 목록</h4>
                                        <?php foreach ($my_social_accounts as $account) {     //반복문
                                            if (empty($account)) {
                                                continue;
                                            }

                                            $provider = strtolower($account['provider']);
                                            $provider_name = social_get_provider_service_name($provider);
                                        ?>
                                            <div class="account_provider" data-mpno="social_<?php echo $account['mp_no']; ?>">
                                                <div class="sns-wrap-32 sns-wrap-over">
                                                    <span class="sns-icon sns-<?php echo $provider; ?>" title="<?php echo $provider_name; ?>">
                                                        <span class="ico"></span>
                                                        <span class="txt"><?php echo $provider_name; ?></span>
                                                    </span>

                                                    <span class="provider_name"><?php echo $provider_name;   //서비스이름 ?> ( <?php echo $account['displayname']; ?> )</span>
                                                    <span class="account_hidden" style="display:none"><?php echo $account['mb_id']; ?></span>
                                                </div>
                                                <div class="btn_info"><a href="<?php echo G5_SOCIAL_LOGIN_URL . '/unlink.php?mp_no=' . $account['mp_no'] ?>" class="social_unlink" data-provider="<?php echo $account['mp_no']; ?>">연동해제</a> <span class="sr-only"><?php echo substr($account['mp_register_day'], 2, 14); ?></span></div>
                                            </div>
                                        <?php } //end foreach ?>
                                    </li>
                                </ul>
                                <script>
                                    jQuery(function($) {
                                        $(".account_provider").on("click", ".social_unlink", function(e) {
                                            e.preventDefault();

                                            if (!confirm('정말 이 계정 연결을 삭제하시겠습니까?')) {
                                                return false;
                                            }

                                            var ajax_url = "<?php echo G5_SOCIAL_LOGIN_URL . '/unlink.php' ?>";
                                            var mb_id = '',
                                                mp_no = $(this).attr("data-provider"),
                                                $mp_el = $(this).parents(".account_provider");

                                            mb_id = $mp_el.find(".account_hidden").text();

                                            if (!mp_no) {
                                                alert('잘못된 요청! mp_no 값이 없습니다.');
                                                return;
                                            }

                                            $.ajax({
                                                url: ajax_url,
                                                type: 'POST',
                                                data: {
                                                    'mp_no': mp_no,
                                                    'mb_id': mb_id
                                                },
                                                dataType: 'json',
                                                async: false,
                                                success: function(data, textStatus) {
                                                    if (data.error) {
                                                        alert(data.error);
                                                        return false;
                                                    } else {
                                                        alert("연결이 해제 되었습니다.");
                                                        $mp_el.fadeOut("normal", function() {
                                                            $(this).remove();
                                                        });
                                                    }
                                                }
                                            });

                                            return;
                                        });
                                    });
                                </script>

                            </td>
                        </tr>

                <?php
                    }   //end if
                }   //end if

                run_event('admin_member_form_add', $mb, $w, 'table');
                ?>
            </tbody>
        </table>
    </div>
</section>
