<?php
require_once './_common.php';

$request = admin_read_ajax_token_request($_POST);
admin_complete_ajax_token_request($request);
