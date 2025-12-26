<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['categories'];

// Call nv_fix_cat_order to ensure order is correct on load
nv_fix_cat_order();

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY sort ASC";
$result = $db->query($sql);
$array_cat = array();
while ($row = $result->fetch()) {
    $array_cat[$row['catid']] = $row;
}

$catid = $nv_Request->get_int('catid', 'get', 0);
$parentid = $nv_Request->get_int('parentid', 'get', 0);

if ($nv_Request->isset_request('save', 'post')) {
    $row = array();
    $row['catid'] = $nv_Request->get_int('catid', 'post', 0);
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['alias'] = $nv_Request->get_title('alias', 'post', '');
    $row['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
    $row['description'] = $nv_Request->get_string('description', 'post', '');
    $row['image'] = $nv_Request->get_string('image', 'post', '');
    $row['viewcat'] = $nv_Request->get_string('viewcat', 'post', 'view_grid');
    $row['groups_view'] = $nv_Request->get_typed_array('groups_view', 'post', 'int', array());
    $row['groups_use'] = $nv_Request->get_typed_array('groups_use', 'post', 'int', array());

    $row['groups_view'] = implode(',', $row['groups_view']);
    $row['groups_use'] = implode(',', $row['groups_use']);

    if (empty($row['alias'])) {
        $row['alias'] = change_alias($row['title']);
    }

    if ($row['catid'] > 0 && $row['catid'] == $row['parentid']) {
        $row['parentid'] = $array_cat[$row['catid']]['parentid'];
    }

    if (empty($row['title'])) {
        $error = $lang_module['error_title'];
    } else {
        if ($row['catid'] == 0) {
            // Check alias unique
            $stmt = $db->prepare("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE alias= :alias");
            $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $error = $lang_module['error_alias'];
            } else {
                $weight = $db->query("SELECT max(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . $row['parentid'])->fetchColumn();
                $weight = intval($weight) + 1;

                $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_cat (parentid, title, alias, description, image, groups_view, groups_use, weight, viewcat, status) VALUES (:parentid, :title, :alias, :description, :image, :groups_view, :groups_use, :weight, :viewcat, 1)";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':parentid', $row['parentid'], PDO::PARAM_INT);
                $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
                $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR);
                $stmt->bindParam(':image', $row['image'], PDO::PARAM_STR);
                $stmt->bindParam(':groups_view', $row['groups_view'], PDO::PARAM_STR);
                $stmt->bindParam(':groups_use', $row['groups_use'], PDO::PARAM_STR);
                $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
                $stmt->bindParam(':viewcat', $row['viewcat'], PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $catid = $db->lastInsertId();
                    nv_fix_cat_order();
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Category', "ID: " . $catid, $admin_info['userid']);
                    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat");
                    die();
                } else {
                    $error = $lang_module['error_insert'];
                }
            }
        } else {
             // Check alias unique
             $stmt = $db->prepare("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE alias= :alias AND catid != :catid");
             $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
             $stmt->bindParam(':catid', $row['catid'], PDO::PARAM_INT);
             $stmt->execute();
             if ($stmt->fetchColumn() > 0) {
                 $error = $lang_module['error_alias'];
             } else {
                 $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_cat SET parentid=:parentid, title=:title, alias=:alias, description=:description, image=:image, groups_view=:groups_view, groups_use=:groups_use, viewcat=:viewcat WHERE catid=:catid";
                 $stmt = $db->prepare($sql);
                 $stmt->bindParam(':parentid', $row['parentid'], PDO::PARAM_INT);
                 $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
                 $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
                 $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR);
                 $stmt->bindParam(':image', $row['image'], PDO::PARAM_STR);
                 $stmt->bindParam(':groups_view', $row['groups_view'], PDO::PARAM_STR);
                 $stmt->bindParam(':groups_use', $row['groups_use'], PDO::PARAM_STR);
                 $stmt->bindParam(':viewcat', $row['viewcat'], PDO::PARAM_STR);
                 $stmt->bindParam(':catid', $row['catid'], PDO::PARAM_INT);

                 if ($stmt->execute()) {
                     nv_fix_cat_order();
                     nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Category', "ID: " . $row['catid'], $admin_info['userid']);
                     Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat");
                     die();
                 } else {
                     $error = $lang_module['error_update'];
                 }
             }
        }
    }
} elseif ($catid > 0) {
    $row = $array_cat[$catid];
} else {
    $row = array(
        'catid' => 0,
        'parentid' => $parentid,
        'title' => '',
        'alias' => '',
        'description' => '',
        'image' => '',
        'groups_view' => '6', // Global visible default
        'groups_use' => '6',
        'viewcat' => 'view_grid'
    );
}

// Delete
if ($nv_Request->isset_request('delete', 'post')) {
    $catid = $nv_Request->get_int('catid', 'post', 0);
    if ($catid > 0) {
        $check_parent = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . $catid)->fetchColumn();
        if ($check_parent > 0) {
            die('ERR_PARENT');
        }
        $check_rows = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE catid=" . $catid)->fetchColumn();
        if ($check_rows > 0) {
            die('ERR_ROWS');
        }
        $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . $catid);
        nv_fix_cat_order();
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Category', "ID: " . $catid, $admin_info['userid']);
        die('OK');
    }
}

// Groups
$groups_list = nv_groups_list();

$xtpl = new XTemplate('cat.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// List Categories
foreach ($array_cat as $cat) {
    $cat['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&catid=" . $cat['catid'];
    $cat['link_delete'] = "javascript:void(0);";
    $cat['onclick_delete'] = "nv_del_cat(" . $cat['catid'] . ")";
    $xtpl->assign('ROW', $cat);
    $xtpl->parse('main.list.row');
}
$xtpl->parse('main.list');

// Form
$xtpl->assign('DATA', $row);

// Parent select
$xtpl->assign('parentid', $row['parentid']);
foreach ($array_cat as $cat) {
    if ($cat['catid'] != $row['catid']) {
        $cat['selected'] = ($cat['catid'] == $row['parentid']) ? 'selected="selected"' : '';
        $cat['title'] = str_repeat('&nbsp;&nbsp;', $cat['lev']) . $cat['title'];
        $xtpl->assign('CAT', $cat);
        $xtpl->parse('main.form.cat_list');
    }
}

// Groups View
$row['groups_view'] = !empty($row['groups_view']) ? explode(',', $row['groups_view']) : array();
foreach ($groups_list as $group_id => $group_title) {
    $xtpl->assign('GROUP_VIEW', array(
        'value' => $group_id,
        'checked' => in_array($group_id, $row['groups_view']) ? 'checked="checked"' : '',
        'title' => $group_title
    ));
    $xtpl->parse('main.form.groups_view');
}

// Groups Use
$row['groups_use'] = !empty($row['groups_use']) ? explode(',', $row['groups_use']) : array();
foreach ($groups_list as $group_id => $group_title) {
    $xtpl->assign('GROUP_USE', array(
        'value' => $group_id,
        'checked' => in_array($group_id, $row['groups_use']) ? 'checked="checked"' : '',
        'title' => $group_title
    ));
    $xtpl->parse('main.form.groups_use');
}

// Layout
$layouts = array('view_grid' => 'Grid', 'view_list' => 'List');
foreach ($layouts as $key => $val) {
    $xtpl->assign('LAYOUT', array(
        'key' => $key,
        'val' => $val,
        'selected' => ($key == $row['viewcat']) ? 'selected="selected"' : ''
    ));
    $xtpl->parse('main.form.layout');
}

$xtpl->parse('main.form');
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
