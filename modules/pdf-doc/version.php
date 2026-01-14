<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'Pdf Tools',
    'modfuncs' => 'main,pdf2word,word2pdf,split,merge',
    'change_alias' => 'main,pdf2word,word2pdf,split,merge',
    'submenu' => 'main,pdf2word,word2pdf,split,merge',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.07',
    'date' => 'Wed, 23 Oct 2024 00:00:00 GMT',
    'author' => 'Jules',
    'uploads_dir' => array($module_name, $module_name . '/temp'),
    'note' => 'Module xử lý file PDF trực tuyến'
);
