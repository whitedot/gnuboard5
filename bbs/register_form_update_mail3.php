<?php
// E-mail 수정시 인증 메일 (회원님께 발송)
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>회원 인증 메일</title>
</head>

<body>

<div>
    <div>
        <h1>
            회원 인증 메일입니다.
        </h1>
        <span>
            <a href="<?php echo G5_URL ?>" target="_blank"><?php echo $config['cf_title'] ?></a>
        </span>
        <p>
            <?php if($w == 'u') { ?>
            <b><?php echo $mb_name ?></b> 님의 E-mail 주소가 변경되었습니다.<br><br>
            <?php } ?>

            아래의 주소를 클릭하시면 인증이 완료됩니다.<br>
            <a href="<?php echo $certify_href ?>" target="_blank"><b><?php echo $certify_href ?></b></a><br><br>

            회원님의 성원에 보답하고자 더욱 더 열심히 하겠습니다.<br>
            감사합니다.
        </p>
        <a href="<?php echo G5_BBS_URL ?>/login.php" target="_blank"><?php echo $config['cf_title'] ?> 로그인</a>
    </div>
</div>

</body>
</html>
