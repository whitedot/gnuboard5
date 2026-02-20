<section id="anc_mb_profile">
    <h2>프로필 및 메모</h2>
    
        <table>
            <caption>프로필 및 메모</caption>
            <colgroup>
                <col>
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="mb_signature">서명</label></th>
                    <td><textarea name="mb_signature" id="mb_signature"><?php echo html_purifier($mb['mb_signature']); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_profile">자기 소개</label></th>
                    <td><textarea name="mb_profile" id="mb_profile"><?php echo html_purifier($mb['mb_profile']); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mb_memo">메모</label></th>
                    <td><textarea name="mb_memo" id="mb_memo"><?php echo html_purifier($mb['mb_memo']); ?></textarea></td>
                </tr>
            </tbody>
        </table>
    
</section>
