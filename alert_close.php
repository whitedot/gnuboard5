<?php
include_once('./_common.php');
$g5['title'] = isset($alert_close_view['title']) ? $alert_close_view['title'] : '결과안내 페이지';
include_once(G5_PATH.'/head.sub.php');
?>

<script>
alert("<?php echo $alert_close_view['message_text']; ?>");
try {
    window.close();
} catch(error) {
    history.back();
}

setTimeout(function() {
    if (window.history.length) {
        window.history.back();
    }
}, 500);
</script>

<noscript>
<div id="validation_check">
    <h1><?php echo $alert_close_view['header_text'] ?></h1>
    <p>
        <?php echo $alert_close_view['message_html'] ?>
    </p>
    <p>
        <?php echo $alert_close_view['description_text'] ?>
    </p>
</div>
</noscript>

<?php
include_once(G5_PATH.'/tail.sub.php');
