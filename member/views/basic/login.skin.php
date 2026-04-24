<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<div id="mb_login">
    <div>
        <h1><?php echo $page_title ?></h1>
        <div>
            <h2><span>회원</span>로그인</h2>
            <a href="<?php echo $register_url ?>">회원가입</a>
        </div>
        <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
        <input type="hidden" name="url" value="<?php echo $login_url ?>">
        
        <fieldset id="login_fs">
            <legend>회원로그인</legend>
            <label for="login_id">회원아이디<strong> 필수</strong></label>
            <input type="text" name="mb_id" id="login_id" required size="20" maxLength="20" placeholder="아이디">
            <label for="login_pw">비밀번호<strong> 필수</strong></label>
            <input type="password" name="mb_password" id="login_pw" required size="20" maxLength="20" placeholder="비밀번호">
            <button type="submit">로그인</button>
            
            <div id="login_info">
                <div>
                    <input type="checkbox" name="auto_login" id="login_auto_login">
                    <label for="login_auto_login"><span></span> 자동로그인</label>  
                </div>
                
                    <a href="<?php echo $password_lost_url ?>">ID/PW 찾기</a>  
                
            </div>
        </fieldset> 
        </form>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var autoLogin = document.getElementById("login_auto_login");
    if (!autoLogin) {
        return;
    }

    autoLogin.addEventListener("click", function() {
        if (this.checked) {
            this.checked = confirm(<?php echo json_encode($auto_login_confirm_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        }
    });
});

function flogin_submit(f)
{
    var loginSubmitEvent = new CustomEvent("login_sumit", {
        bubbles: true,
        cancelable: true,
        detail: {
            form: f,
            formId: "flogin"
        }
    });

    document.body.dispatchEvent(loginSubmitEvent);
    return !loginSubmitEvent.defaultPrevented;
}
</script>
