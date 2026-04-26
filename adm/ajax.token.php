<?php
require_once './_common.php';

$request = admin_read_ajax_token_request(g5_get_runtime_post_input());
admin_complete_ajax_token_request($request);
