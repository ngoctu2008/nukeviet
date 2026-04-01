<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_MOD_PDF_DOC')) {
    die('Stop!!!');
}

$filename = $nv_Request->get_title('file', 'get', '');
$name = $nv_Request->get_title('name', 'get', 'document');

if (empty($filename) || !preg_match('/^[a-zA-Z0-9]+$/', explode('.', $filename)[0])) {
    die('Invalid file');
}

$upload_dir = NV_ROOTDIR . '/uploads/' . $module_name . '/temp';
$filepath = $upload_dir . '/' . $filename;

if (file_exists($filepath)) {
    // Check cleanup time vs file time logic could be here, but simpler is just to serve if exists
    // Basic security: make sure it is in the temp dir (realpath check)
    if (strpos(realpath($filepath), realpath($upload_dir)) !== 0) {
        die('Access denied');
    }

    // Fix: Use namespaced Download class for NukeViet 4.5+
    // No need to require file manually as it is autoloaded
    $download = new NukeViet\Files\Download($filepath, $upload_dir, $name);
    $download->download_file();
    exit();
}

die('File not found');
