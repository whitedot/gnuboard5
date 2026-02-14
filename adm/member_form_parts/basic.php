<section id="anc_mb_basic">
    <h2 class="h2_frm">湲곕낯 ?뺣낫</h2>
    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption>湲곕낯 ?뺣낫</caption>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="mb_id">?꾩씠???php echo $sr_only ?></label></th>
                    <td>
                        <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15" maxlength="20">
                        <?php if ($w == 'u') { ?><a href="./boardgroupmember_form.php?mb_id=<?php echo $mb['mb_id'] ?>" class="btn_frmline">?묎렐媛?κ렇猷밸낫湲?/a><?php } ?>
                    </td>
                    <th scope="row"><label for="mb_password">鍮꾨?踰덊샇<?php echo $sr_only ?></label></th>
                    <td>
                        <div>
                        <input type="password" name="mb_password" id="mb_password" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20">
                        </div>
                        <div id="mb_password_captcha_wrap" style="display:none">
                            <?php
                            require_once G5_CAPTCHA_PATH . '/captcha.lib.php';
                            $captcha_html = captcha_html();
                            $captcha_js   = chk_captcha_js();
                            echo $captcha_html;
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_name">?대쫫(?ㅻ챸)<strong class="sr-only">?꾩닔</strong></label></th>
                    <td><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required frm_input" size="15" maxlength="20"></td>
                    <th scope="row"><label for="mb_nick">?됰꽕??strong class="sr-only">?꾩닔</strong></label></th>
                    <td><input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick" required class="required frm_input" size="15" maxlength="20"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_level">?뚯썝 沅뚰븳</label></th>
                    <td><?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?></td>
                    <th scope="row">?ъ씤??/th>
                    <td><a href="./point_list.php?sfl=mb_id&amp;stx=<?php echo $mb['mb_id'] ?>" target="_blank"><?php echo number_format($mb['mb_point']) ?></a> ??/td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_email">E-mail<strong class="sr-only">?꾩닔</strong></label></th>
                    <td><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required frm_input email" size="30"></td>
                    <th scope="row"><label for="mb_homepage">?덊럹?댁?</label></th>
                    <td><input type="text" name="mb_homepage" value="<?php echo $mb['mb_homepage'] ?>" id="mb_homepage" class="frm_input" maxlength="255" size="15"></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
