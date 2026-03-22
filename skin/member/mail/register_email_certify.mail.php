<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>이메일 인증 메일</title>
</head>
<body>
<div>
    <div>
        <h1>이메일 인증 안내</h1>
        <span>
            <a href="<?php echo G5_URL; ?>" target="_blank"><?php echo $config['cf_title']; ?></a>
        </span>
        <p>
            <b><?php echo $mb_name; ?></b> 님의 회원가입을 확인하고 있습니다.<br>
            아래의 <strong>메일인증</strong>을 클릭하시면 회원가입이 완료됩니다.<br>
            본인이 요청하지 않은 메일이라면 이 메일을 무시해 주세요.
        </p>
        <a href="<?php echo $certify_href; ?>" target="_blank">메일인증</a>
    </div>
</div>
</body>
</html>
