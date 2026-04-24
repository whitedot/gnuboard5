<?php

include_once('./_common.php');

$g5['title'] = isset($alert_view['title']) ? $alert_view['title'] : '결과안내 페이지';
include_once(G5_PATH.'/head.sub.php');
?>

<script>
alert("<?php echo $alert_view['message_text']; ?>");
<?php if ($alert_view['redirect_url'] !== '') { ?>
document.location.replace("<?php echo $alert_view['redirect_url_js']; ?>");
<?php } else { ?>
history.back();
<?php } ?>
</script>

<noscript>
<div id="validation_check">
    <h1><?php echo $alert_view['header_text'] ?></h1>
    <p>
        <?php echo $alert_view['message_html'] ?>
    </p>
    <?php if ($alert_view['show_post_form']) { ?>
    <form method="post" action="<?php echo $alert_view['redirect_url'] ?>">
    <?php
    foreach ($alert_view['post_fields'] as $field) {
    ?>
    <input type="hidden" name="<?php echo $field['name']; ?>" value="<?php echo $field['value']; ?>">
    <?php
    }
    ?>
    <input type="submit" value="돌아가기">
    </form>
    <?php } else { ?>
    <div>
        <a href="<?php echo $alert_view['redirect_url'] ?>">돌아가기</a>
    </div>
    <?php } ?>
</div>
</noscript>

<?php
include_once(G5_PATH.'/tail.sub.php');
