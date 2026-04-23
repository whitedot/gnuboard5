<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
<form name="fmemberautopost" method="post" action="<?php echo htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>">
<?php foreach ($fields as $name => $value) { ?>
<input type="hidden" name="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>">
<?php } ?>
</form>
<script>
<?php if ($message) { ?>
alert(<?php echo json_encode($message); ?>);
<?php } ?>
document.fmemberautopost.submit();
</script>
</body>
</html>
