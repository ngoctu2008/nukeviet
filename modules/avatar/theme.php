<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOUR NAME (email@domain.com)
 * @Copyright (C) 2025
 * @License GNU/GPLv3
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_IS_MOD_AVATAR')) {
    die('Stop!!!');
}

/**
 * Frontend main view
 */
function nv_theme_avatar_main($array_cat)
{
    global $module_info, $lang_module, $module_file, $global_config, $module_name, $op, $db, $db_config, $lang, $module_data;

    $tp = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
    if (!file_exists($tp . '/main.tpl')) {
        $tp = NV_ROOTDIR . '/themes/default/modules/' . $module_file;
    }

    $xtpl = new XTemplate('main.tpl', $tp);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    foreach ($array_cat as $cat) {
        $xtpl->assign('CAT', $cat);
        $xtpl->parse('main.cat');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
