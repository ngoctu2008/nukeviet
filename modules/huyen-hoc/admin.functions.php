<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array('main', 'config', 'tu-vi', 'xem-tuoi', 'xem-ngay', 'lo-ban', 'dat-ten', 'sim-so', 'gieo-que', 'import');

define('NV_IS_FILE_ADMIN', true);
