<?php
// 회원가입 메일 (관리자 메일로 발송)
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>회원가입 알림 메일</title>
</head>

<body>

<div>
    <div>
        <h1>
            회원가입 알림 메일
        </h1>
        <span>
            <a href="<?php echo G5_URL ?>" target="_blank"><?php echo $config['cf_title'] ?></a>
        </span>
        <p>
            <b><?php echo $mb_name ?></b> 님께서 회원가입 하셨습니다.<br>
            회원 아이디 : <b><?php echo $mb_id ?></b><br>
            회원 이름 : <?php echo $mb_name ?><br>
            회원 닉네임 : <?php echo $mb_nick ?><br>
            추천인아이디 : <?php echo $mb_recommend ?>
        </p>
        <a href="<?php echo G5_ADMIN_URL ?>/member_form.php?w=u&amp;mb_id=<?php echo $mb_id ?>">관리자에서 회원정보 확인하기</a>
    </div>
</div>

</body>
</html>
