<?php
admin_register_menu_group($menu, 'menu100', array(
    array('100000', '환경설정', G5_ADMIN_URL . '/config_form.php', 'config'),
    array('100100', '기본환경설정', G5_ADMIN_URL . '/config_form.php', 'cf_basic'),
));
