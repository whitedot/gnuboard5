<?php
// 설문조사 기타의견 입력시 관리자께 보내는 메일을 수정하고 싶으시다면 이 파일을 수정하십시오. 
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>설문조사 기타의견 메일</title>
</head>

<body>

<div>
    <div>
        <h1>
            <?php echo $subject ?>
        </h1>
        <span>
            작성자 <?php echo $name ?> (<?php echo $mb_id ?>)
        </span>
        <p>
            <?php echo $content ?>
        </p>
    </div>
</div>

</body>
</html>
