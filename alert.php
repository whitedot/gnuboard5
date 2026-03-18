<?php

include_once('./_common.php');

if($error) {
    $g5['title'] = "오류안내 페이지";
} else {
    $g5['title'] = "결과안내 페이지";
}
include_once(G5_PATH.'/head.sub.php');

$msg = isset($msg) ? strip_tags($msg) : '';
$msg2 = str_replace("\\n", "<br>", $msg);

$url = isset($url) ? clean_xss_tags($url, 1) : '';
if (!$url) $url = isset($_SERVER['HTTP_REFERER']) ? clean_xss_tags($_SERVER['HTTP_REFERER'], 1) : '';

$url = preg_replace("/[\<\>\'\"\\\'\\\"\(\)]/", "", $url);
$url = preg_replace('/\r\n|\r|\n|[^\x20-\x7e]/','', $url);

check_url_host($url, $msg);

if($error) {
    $header2 = "다음 항목에 오류가 있습니다.";
} else {
    $header2 = "다음 내용을 확인해 주세요.";
}
?>

<script>
alert("<?php echo $msg; ?>");
<?php if ($url) { ?>
document.location.replace("<?php echo str_replace('&amp;', '&', $url); ?>");
<?php } else { ?>
history.back();
<?php } ?>
</script>

<noscript>
<div id="validation_check">
    <h1><?php echo $header2 ?></h1>
    <p>
        <?php echo $msg2 ?>
    </p>
    <?php if($post) { ?>
    <form method="post" action="<?php echo $url ?>">
    <?php
    foreach($_POST as $key => $value) {
        $key = clean_xss_tags($key);
        $value = clean_xss_tags($value);

        if(strlen($value) < 1)
            continue;

        if(preg_match("/pass|pwd|capt|url/", $key))
            continue;
    ?>
    <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
    <?php
    }
    ?>
    <input type="submit" value="돌아가기">
    </form>
    <?php } else { ?>
    <div>
        <a href="<?php echo $url ?>">돌아가기</a>
    </div>
    <?php } ?>
</div>
</noscript>

<?php
include_once(G5_PATH.'/tail.sub.php');
