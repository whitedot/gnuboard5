<?php
// 게시물 입력시 게시자, 관리자에게 드리는 메일을 수정하고 싶으시다면 이 파일을 수정하십시오.
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo $wr_subject ?> 메일</title>
</head>

<body>

<div>
    <div>
        <h1>
            <?php echo $wr_subject ?>
        </h1>
        <span>
            작성자 <?php echo $wr_name ?>
        </span>
        <div>
            <?php echo $wr_content ?>
        </div>
        <a href="<?php echo $link_url ?>">사이트에서 게시물 확인하기</a>
    </div>
</div>

</body>
</html>
