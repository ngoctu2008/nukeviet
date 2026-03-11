<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'Huyen Hoc',
    'modfuncs' => 'main,tu-vi,xem-tuoi,xem-ngay,lo-ban,dat-ten,sim-so,gieo-que',
    'change_alias' => 'main,tu-vi,xem-tuoi,xem-ngay,lo-ban,dat-ten,sim-so,gieo-que',
    'submenu' => 'main,tu-vi,xem-tuoi,xem-ngay,lo-ban,dat-ten,sim-so,gieo-que',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.07',
    'date' => 'Wed, 11 Feb 2026 00:00:00 GMT',
    'author' => 'Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)',
    'uploads_dir' => array($module_name),
    'note' => 'Module Huyen Hoc - Sieu ung dung phong thuy, tu vi'
);
