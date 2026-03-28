<?php

/**
 * @Project NUKEVIET 4.5.07
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2026 Phạm Ngọc Tú. All rights reserved
 * @Createdate Sat, 07/02/2026 06:27:27 GMT
 */

if (!defined('NV_IS_MOD_POPUP')) {
    die('Stop!!!');
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

// This module is primarily for blocks. Redirect to home or show specific content if needed.
// For now, redirect to home to prevent direct access.
nv_redirect_location(NV_BASE_SITEURL);
