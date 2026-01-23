<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_MOD_TU_VI')) {
    exit('Stop!!!');
}

/**
 * nv_tu_vi_theme_main()
 *
 * @param mixed $array_data
 * @return
 */
function nv_tu_vi_theme_main($array_data)
{
    global $module_info, $lang_module, $lang_global, $op;

    // Use specific module template path
    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    // Basic assignments
    $xtpl->assign('MODULE_NAME', $module_info['module_name']);
    $xtpl->assign('OP', $op);

    // Parse Action Form
    $xtpl->assign('ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_info['module_name'] . '&amp;' . NV_OP_VARIABLE . '=view');

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_tu_vi_theme_view()
 *
 * @param mixed $data
 * @return
 */
function nv_tu_vi_theme_view($data)
{
    global $module_info, $lang_module, $lang_global;

    $xtpl = new XTemplate('view.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('DATA', $data);

    // ... Logic to parse chart will go here ...
    // For now just basic dump

    // Example Loop for 12 Cung
    if (!empty($data['cung'])) {
        foreach ($data['cung'] as $cung) {
            $xtpl->assign('CUNG', $cung);
            $xtpl->parse('main.cung');
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
