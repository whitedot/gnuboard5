<section id="anc_scf_info">
    <h2 class="h2_frm">사업자정보</h2>
    <?php echo $pg_anchor; ?>
    <div class="local_desc02 local_desc">
        <p>
            사업자정보는 tail.php 와 content.php 에서 표시합니다.<br>
            대표전화번호는 SMS 발송번호로 사용되므로 사전등록된 발신번호와 일치해야 합니다.
        </p>
    </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>사업자정보 입력</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="de_admin_company_name">회사명</label></th>
            <td>
                <input type="text" name="de_admin_company_name" value="<?php echo get_sanitize_input($default['de_admin_company_name']); ?>" id="de_admin_company_name" class="frm_input" size="30">
            </td>
            <th scope="row"><label for="de_admin_company_saupja_no">사업자등록번호</label></th>
            <td>
                <input type="text" name="de_admin_company_saupja_no"  value="<?php echo get_sanitize_input($default['de_admin_company_saupja_no']); ?>" id="de_admin_company_saupja_no" class="frm_input" size="30">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_admin_company_owner">대표자명</label></th>
            <td colspan="3">
                <input type="text" name="de_admin_company_owner" value="<?php echo get_sanitize_input($default['de_admin_company_owner']); ?>" id="de_admin_company_owner" class="frm_input" size="30">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_admin_company_tel">대표전화번호</label></th>
            <td>
                <input type="text" name="de_admin_company_tel" value="<?php echo get_sanitize_input($default['de_admin_company_tel']); ?>" id="de_admin_company_tel" class="frm_input" size="30">
            </td>
            <th scope="row"><label for="de_admin_company_fax">팩스번호</label></th>
            <td>
                <input type="text" name="de_admin_company_fax" value="<?php echo get_sanitize_input($default['de_admin_company_fax']); ?>" id="de_admin_company_fax" class="frm_input" size="30">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_admin_tongsin_no">통신판매업 신고번호</label></th>
            <td>
                <input type="text" name="de_admin_tongsin_no" value="<?php echo get_sanitize_input($default['de_admin_tongsin_no']); ?>" id="de_admin_tongsin_no" class="frm_input" size="30">
            </td>
            <th scope="row"><label for="de_admin_buga_no">부가통신 사업자번호</label></th>
            <td>
                <input type="text" name="de_admin_buga_no" value="<?php echo get_sanitize_input($default['de_admin_buga_no']); ?>" id="de_admin_buga_no" class="frm_input" size="30">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_admin_company_zip">사업장우편번호</label></th>
            <td>
                <input type="text" name="de_admin_company_zip" value="<?php echo get_sanitize_input($default['de_admin_company_zip']); ?>" id="de_admin_company_zip" class="frm_input" size="10">
            </td>
            <th scope="row"><label for="de_admin_company_addr">사업장주소</label></th>
            <td>
                <input type="text" name="de_admin_company_addr" value="<?php echo get_sanitize_input($default['de_admin_company_addr']); ?>" id="de_admin_company_addr" class="frm_input" size="30">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="de_admin_info_name">정보관리책임자명</label></th>
            <td>
                <input type="text" name="de_admin_info_name" value="<?php echo get_sanitize_input($default['de_admin_info_name']); ?>" id="de_admin_info_name" class="frm_input" size="30">
            </td>
            <th scope="row"><label for="de_admin_info_email">정보책임자 e-mail</label></th>
            <td>
                <input type="text" name="de_admin_info_email" value="<?php echo get_sanitize_input($default['de_admin_info_email']); ?>" id="de_admin_info_email" class="frm_input" size="30">
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>
