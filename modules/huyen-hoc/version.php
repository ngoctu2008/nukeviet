<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'Huyen Hoc',
    'modfuncs' => 'main,tu_vi,xem_tuoi,xem_ngay,lo_ban,dat_ten,sim_so,gieo_que',
    'change_alias' => 'main,tu_vi,xem_tuoi,xem_ngay,lo_ban,dat_ten,sim_so,gieo_que',
    'submenu' => 'main,tu_vi,xem_tuoi,xem_ngay,lo_ban,dat_ten,sim_so,gieo_que',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.3.00',
    'date' => 'Mon, 21 Oct 2024 00:00:00 GMT',
    'author' => 'Jules',
    'uploads_dir' => array($module_name),
    'note' => 'Module Huyen Hoc - Sieu ung dung phong thuy, tu vi'
);
