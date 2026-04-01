<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

// Cleanup Temp Files
$upload_dir = NV_ROOTDIR . '/uploads/' . $module_name . '/temp';

if (is_dir($upload_dir)) {
    $cleanup_time = isset($module_config[$module_name]['cleanup_time']) ? (int)$module_config[$module_name]['cleanup_time'] : 30;
    // Minimum 5 minutes safety
    if ($cleanup_time < 5) $cleanup_time = 5;

    $files = scandir($upload_dir);
    $now = time();
    $expire_seconds = $cleanup_time * 60;

    foreach ($files as $file) {
        if ($file == '.' || $file == '..' || $file == '.htaccess' || $file == 'index.html') {
            continue;
        }

        $filepath = $upload_dir . '/' . $file;
        if (is_file($filepath)) {
            if ($now - filemtime($filepath) >= $expire_seconds) {
                @unlink($filepath);
            }
        }
    }
}
