<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

// Call the function in content.php or just define menu here
// Usually admin.menu.php defines the menu items.
// This main.php is the default entry point.

// If we want to show a list of interpretations or config, we can redirect or include.
// Let's forward to 'content' (Manage Interpretations) as the main view.

header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content');
exit();
