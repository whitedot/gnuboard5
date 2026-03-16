<?php
require_once './_common.php';

if ($is_admin != 'super') {
    die('최고관리자만 접근 가능합니다.');
}
?>

<table>
    <colgroup>
        <col>
        <col>
    </colgroup>
    <tbody>
        <tr>
            <th scope="row"><label for="me_name">메뉴<strong> 필수</strong></label></th>
            <td><input type="text" name="me_name" id="me_name" required class="required"></td>
        </tr>
        <tr>
            <th scope="row"><label for="me_link">링크<strong> 필수</strong></label></th>
            <td>
                <?php echo help('링크는 http://를 포함해서 입력해 주세요.'); ?>
                <input type="text" name="me_link" id="me_link" required class="required">
            </td>
        </tr>
    </tbody>
</table>

<div>
    <button type="button" id="add_manual">추가</button>
    <button type="button" onclick="window.close();">창닫기</button>
</div>
