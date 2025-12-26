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
    global $module_info, $lang_module, $module_file, $op;

    $tp = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
    if (!file_exists($tp . '/main.tpl')) {
        $tp = NV_ROOTDIR . '/themes/default/modules/' . $module_file;
    }

    $xtpl = new XTemplate('main.tpl', $tp);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('MODULE_NAME', $module_info['module_name']);
    $xtpl->assign('OP', $op);

    foreach ($array_cat as $cat) {
        $xtpl->assign('CAT', $cat);
        $xtpl->parse('main.cat');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * View Category
 */
function nv_theme_avatar_viewcat($viewcat, $cat_info, $list, $generate_page)
{
    global $module_info, $lang_module, $module_file, $module_upload;

    $tp = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
    if (!file_exists($tp . '/' . $viewcat . '.tpl')) {
        $tp = NV_ROOTDIR . '/themes/default/modules/' . $module_file;
    }

    $xtpl = new XTemplate($viewcat . '.tpl', $tp);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CAT_INFO', $cat_info);
    $xtpl->assign('GENERATE_PAGE', $generate_page);

    foreach ($list as $row) {
        if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
        }
        $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_info['module_name'] . "&amp;" . NV_OP_VARIABLE . "=" . $cat_info['alias'] . "/" . $row['alias'] . "-" . $row['id'];

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.row');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Detail View
 */
function nv_theme_avatar_detail($row, $cat_info)
{
    global $module_info, $lang_module, $module_file, $module_upload;

    $tp = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
    if (!file_exists($tp . '/detail.tpl')) {
        $tp = NV_ROOTDIR . '/themes/default/modules/' . $module_file;
    }

    $xtpl = new XTemplate('detail.tpl', $tp);
    $xtpl->assign('LANG', $lang_module);

    if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
        $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
    }

    $xtpl->assign('ROW', $row);
    $xtpl->assign('CAT_INFO', $cat_info);

    $xtpl->parse('main');
    return $xtpl->text('main');
}
