<?php
include_once('./_common.php');
$g5['title'] = isset($confirm_view['header_text']) ? $confirm_view['header_text'] : '';
include_once(G5_PATH.'/head.sub.php');
?>

<script>
var conf = "<?php echo $confirm_view['confirm_message']; ?>";
if (confirm(conf)) {
    document.location.replace("<?php echo $confirm_view['confirm_url']; ?>");
} else {
    document.location.replace("<?php echo $confirm_view['cancel_url']; ?>");
}
</script>

<noscript>
<article id="confirm_check">
<header>
    <hgroup>
        <h1><?php echo $confirm_view['header_text']; ?></h1>
        <h2>아래 내용을 확인해 주세요.</h2>
    </hgroup>
</header>
<p>
    <?php echo $confirm_view['message_text']; ?>
</p>

<a href="<?php echo $confirm_view['confirm_url']; ?>">확인</a>
<a href="<?php echo $confirm_view['cancel_url']; ?>">취소</a><br><br>
<a href="<?php echo $confirm_view['back_url']; ?>">돌아가기</a>
</article>
</noscript>

<?php
include_once(G5_PATH.'/tail.sub.php');
