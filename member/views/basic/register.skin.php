<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<div>

    <form  name="fregister" id="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

    <p><i aria-hidden="true"></i> <?php echo $page_notice_text; ?></p>
    
    <section id="fregister_term">
        <h2>(필수) 회원가입약관</h2>
        <textarea readonly><?php echo $stipulation_text; ?></textarea>
        <fieldset>
            <input type="checkbox" name="agree" value="1" id="agree11">
            <label for="agree11"><span></span><b>회원가입약관의 내용에 동의합니다.</b></label>
        </fieldset>
    </section>

    <section id="fregister_private">
        <h2>(필수) 개인정보 수집 및 이용</h2>
        
            <table>
                <caption>개인정보 수집 및 이용</caption>
                <thead>
                <tr>
                    <th>목적</th>
                    <th>항목</th>
                    <th>보유기간</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>이용자 식별 및 본인여부 확인</td>
                    <td><?php echo $private_info_item_text; ?></td>
                    <td>회원 탈퇴 시까지</td>
                </tr>
                <tr>
                    <td>고객서비스 이용에 관한 통지,<br>CS대응을 위한 이용자 식별</td>
                    <td>연락처 (이메일, 휴대전화번호)</td>
                    <td>회원 탈퇴 시까지</td>
                </tr>
                </tbody>
            </table>
        

        <fieldset>
            <input type="checkbox" name="agree2" value="1" id="agree21">
            <label for="agree21"><span></span><b>개인정보 수집 및 이용의 내용에 동의합니다.</b></label>
       </fieldset>
    </section>
	
	<div id="fregister_chkall">
        <input type="checkbox" name="chk_all" id="chk_all">
        <label for="chk_all"><span></span>회원가입 약관에 모두 동의합니다</label>
    </div>
	    
    <div>
    	<a href="<?php echo $cancel_url ?>">취소</a>
        <button type="submit">회원가입</button>
    </div>

    </form>

    <script>
    function fregister_submit(f)
    {
        if (!f.agree.checked) {
            alert(<?php echo json_encode($agree_alert_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
            f.agree.focus();
            return false;
        }

        if (!f.agree2.checked) {
            alert(<?php echo json_encode($agree2_alert_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
            f.agree2.focus();
            return false;
        }

        return true;
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        var checkAll = document.querySelector("input[name=chk_all]");
        if (!checkAll) {
            return;
        }

        checkAll.addEventListener("click", function() {
            var agreements = document.querySelectorAll("input[name^=agree]");
            agreements.forEach(function(agreement) {
                agreement.checked = checkAll.checked;
            });
        });
    });

    </script>
</div>
