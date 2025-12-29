<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_IS_MOD_AVATAR')) {
    die('Stop!!!');
}

/**
 * Frontend main view
 */
function nv_theme_avatar_main($array_cat, $generate_page = '')
{
    global $module_info, $lang_module, $module_file, $op, $module_name;

    $tp = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
    if (!file_exists($tp . '/main.tpl')) {
        $tp = NV_ROOTDIR . '/themes/default/modules/' . $module_file;
    }

    $xtpl = new XTemplate('main.tpl', $tp);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    foreach ($array_cat as $cat) {
        if (!empty($cat['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $cat['image'])) {
            $cat['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $cat['image'];
        }
        $xtpl->assign('CAT', $cat);
        if (!empty($cat['image'])) $xtpl->parse('main.cat.image');
        $xtpl->parse('main.cat');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * View Category
 */
function nv_theme_avatar_viewcat($viewcat, $cat_info, $list, $generate_page, $allow_use = true)
{
    global $module_info, $lang_module, $module_file, $module_upload, $module_name;

    $tp = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
    if (!file_exists($tp . '/' . $viewcat . '.tpl')) {
        $tp = NV_ROOTDIR . '/themes/default/modules/' . $module_file;
    }

    $xtpl = new XTemplate($viewcat . '.tpl', $tp);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CAT_INFO', $cat_info);
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('MODULE_NAME', $module_name);

    if ($allow_use) {
        $xtpl->assign('ALLOW_USE', 1);
        $xtpl->parse('main.allow_use');
    }

    foreach ($list as $row) {
        if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
        }
        $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat_info['alias'] . "/" . $row['alias'] . "-" . $row['id'];

        $xtpl->assign('ROW', $row);
        if ($allow_use) {
            $xtpl->parse('main.row.allow_use');
        }
        $xtpl->parse('main.row');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Detail View (Editor)
 */
function nv_theme_avatar_detail($row, $cat_info, $allow_use = true)
{
    global $module_info, $lang_module, $module_file, $module_upload, $module_name;

    $tp = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
    if (!file_exists($tp . '/detail.tpl')) {
        $tp = NV_ROOTDIR . '/themes/default/modules/' . $module_file;
    }

    $xtpl = new XTemplate('detail.tpl', $tp);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);

    if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
        $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
    }

    $xtpl->assign('ROW', $row);
    $xtpl->assign('CAT_INFO', $cat_info);

    if ($allow_use) {
        $xtpl->parse('main.allow_use');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
