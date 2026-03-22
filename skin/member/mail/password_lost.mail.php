<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<div>
    <div>
        <h1>회원정보 찾기 안내</h1>
        <span><a href="<?php echo G5_URL; ?>" target="_blank"><?php echo $config['cf_title']; ?></a></span>
        <p>
            <?php echo addslashes($mb['mb_name']).' ('.addslashes($mb['mb_nick']).')'; ?> 회원님은 <?php echo G5_TIME_YMDHIS; ?> 에 회원정보 찾기 요청을 하셨습니다.<br>
            저희 사이트는 관리자라도 회원님의 비밀번호를 알 수 없기 때문에, 비밀번호를 알려드리는 대신 새로운 비밀번호를 생성하여 안내 해드리고 있습니다.<br>
            아래에서 변경될 비밀번호를 확인하신 후, <span><strong>비밀번호 변경</strong> 링크를 클릭 하십시오.</span><br>
            비밀번호가 변경되었다는 인증 메세지가 출력되면, 홈페이지에서 회원아이디와 변경된 비밀번호를 입력하시고 로그인 하십시오.<br>
            로그인 후에는 정보수정 메뉴에서 새로운 비밀번호로 변경해 주십시오.
        </p>
        <p>
            <span>회원아이디</span> <?php echo $mb['mb_id']; ?><br>
            <span>변경될 비밀번호</span> <strong><?php echo $change_password; ?></strong>
        </p>
        <a href="<?php echo $href; ?>" target="_blank">비밀번호 변경</a>
    </div>
</div>
