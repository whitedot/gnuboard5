<?php
// 회원가입축하 메일 (회원님께 발송)
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>회원가입 축하 메일</title>
</head>

<body>

<div>
    <div>
        <h1>
            회원가입을 축하합니다.
        </h1>
        <span>
            <a href="<?php echo G5_URL ?>" target="_blank"><?php echo $config['cf_title'] ?></a>
        </span>
        <p>
            <b><?php echo $mb_name ?></b> 님의 회원가입을 진심으로 축하합니다.<br>
            회원님의 성원에 보답하고자 더욱 더 열심히 하겠습니다.<br>
            <?php if ($config['cf_use_email_certify']) { ?>아래의 <strong>메일인증</strong>을 클릭하시면 회원가입이 완료됩니다.<br><?php } ?>
            감사합니다.
        </p>

        <?php if ($config['cf_use_email_certify']) { ?>
        <a href="<?php echo $certify_href ?>" target="_blank">메일인증</a>
        <?php } else { ?>
        <a href="<?php echo G5_URL ?>" target="_blank">사이트바로가기</a>
        <?php } ?>
    </div>
</div>

</body>
</html>
