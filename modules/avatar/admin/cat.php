<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['categories'];

// Change status
if ($nv_Request->isset_request('change_status', 'post, get')) {
    $catid = $nv_Request->get_int('catid', 'post, get', 0);
    $content = 'NO_' . $catid;

    $query = 'SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid;
    $row = $db->query($query)->fetch();
    if (isset($row['status'])) {
        $status = ($row['status']) ? 0 : 1;
        $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET status=' . intval($status) . ' WHERE catid=' . $catid;
        $db->query($query);
        $content = 'OK_' . $catid;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

// Ajax Action (Weight)
if ($nv_Request->isset_request('ajax_action', 'post')) {
    $catid = $nv_Request->get_int('catid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $content = 'NO_' . $catid;
    if ($new_vid > 0) {
        // Get parentid of current cat to only reorder siblings
        $parentid = $db->query("SELECT parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . $catid)->fetchColumn();

        $sql = 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid . ' AND catid!=' . $catid . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight == $new_vid) ++$weight;
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . $row['catid'];
            $db->query($sql);
        }
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $new_vid . ' WHERE catid=' . $catid;
        $db->query($sql);

        // Fix order recursively just in case
        nv_avatar_fix_cat_order($parentid);

        $content = 'OK_' . $catid;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('get_alias_title', 'post')) {
    $alias = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($alias);
    die($alias);
}

// Call nv_avatar_fix_cat_order to ensure order is correct on load
nv_avatar_fix_cat_order();

$catid = $nv_Request->get_int('catid', 'get', 0);
$parentid = $nv_Request->get_int('parentid', 'get', 0);

// Get all cats for Parent Dropdown (Flat list with level indentation)
$sql = "SELECT catid, title, lev, parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY sort ASC";
$result = $db->query($sql);
$array_cat_list = array();
while ($row = $result->fetch()) {
    $array_cat_list[$row['catid']] = $row;
}

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
        $row['parentid'] = $array_cat_list[$row['catid']]['parentid'];
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
                    nv_avatar_fix_cat_order();
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
                     nv_avatar_fix_cat_order();
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
    $row = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . $catid)->fetch();
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
    $checkss = $nv_Request->get_string('checkss', 'post', '');

    if ($catid > 0 and $checkss == md5($catid . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $check_parent = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . $catid)->fetchColumn();
        if ($check_parent > 0) {
            die('ERR_PARENT');
        }
        $check_rows = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE catid=" . $catid)->fetchColumn();
        if ($check_rows > 0) {
            die('ERR_ROWS');
        }
        $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . $catid);
        nv_avatar_fix_cat_order();
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Category', "ID: " . $catid, $admin_info['userid']);
        die('OK');
    } else {
        die('NO');
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
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// FETCH LIST FOR TABLE (Pagination on Root only)
$per_page = isset($module_config['per_page_cat']) ? intval($module_config['per_page_cat']) : 20;
$page = $nv_Request->get_int('page', 'get', 1);

// Count root categories
$num_items = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=0")->fetchColumn();

// Get root categories
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=0 ORDER BY weight ASC LIMIT " . (($page - 1) * $per_page) . "," . $per_page;
$result = $db->query($sql);

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat";
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.view.generate_page');
}

function recursive_cat_list($parentid, $xtpl, $groups_list, $module_upload, $module_name, $client_info) {
    global $db, $module_data;

    // Fetch children
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . $parentid . " ORDER BY weight ASC";
    $result = $db->query($sql);

    // Count siblings for weight select
    $num_siblings = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . $parentid)->fetchColumn();

    while ($row = $result->fetch()) {
        $row['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&catid=" . $row['catid'];
        $row['link_delete'] = "javascript:void(0);";
        $row['onclick_delete'] = "nv_del_cat(" . $row['catid'] . ", '" . md5($row['catid'] . NV_CACHE_PREFIX . $client_info['session_id']) . "')";

        // Image
        if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
        } else {
             $row['image'] = '';
        }

        // Permissions Display
        $groups_view = !empty($row['groups_view']) ? explode(',', $row['groups_view']) : array();
        $row['groups_view_str'] = array();
        foreach ($groups_view as $gid) {
            if (isset($groups_list[$gid])) $row['groups_view_str'][] = $groups_list[$gid];
        }
        $row['groups_view_str'] = implode(', ', $row['groups_view_str']);

        $groups_use = !empty($row['groups_use']) ? explode(',', $row['groups_use']) : array();
        $row['groups_use_str'] = array();
        foreach ($groups_use as $gid) {
            if (isset($groups_list[$gid])) $row['groups_use_str'][] = $groups_list[$gid];
        }
        $row['groups_use_str'] = implode(', ', $row['groups_use_str']);

        // Weight Select
        for ($i = 1; $i <= $num_siblings; ++$i) {
            $xtpl->assign('WEIGHT', array(
                'key' => $i,
                'title' => $i,
                'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.view.loop.weight_loop');
        }

        $row['title'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $row['lev']) . ($row['lev'] > 0 ? '↳ ' : '') . $row['title'];
        $row['check_status'] = $row['status'] == 1 ? 'checked' : '';

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.view.loop');

        // Recursive call for children
        recursive_cat_list($row['catid'], $xtpl, $groups_list, $module_upload, $module_name, $client_info);
    }
}

// Start recursion with roots from current page (already fetched above)
// Note: recursive_cat_list usually fetches children. But we need to handle the roots we already have from pagination.
$num_siblings_root = $num_items; // Approximately, for weight loop of roots

while ($cat_item = $result->fetch()) {
     // Identical processing for Root Rows
     $cat_item['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&catid=" . $cat_item['catid'];
     $cat_item['link_delete'] = "javascript:void(0);";
     $cat_item['onclick_delete'] = "nv_del_cat(" . $cat_item['catid'] . ", '" . md5($cat_item['catid'] . NV_CACHE_PREFIX . $client_info['session_id']) . "')";

     if (!empty($cat_item['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $cat_item['image'])) {
         $cat_item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $cat_item['image'];
     } else {
         $cat_item['image'] = '';
     }

     $groups_view = !empty($cat_item['groups_view']) ? explode(',', $cat_item['groups_view']) : array();
     $cat_item['groups_view_str'] = array();
     foreach ($groups_view as $gid) {
         if (isset($groups_list[$gid])) $cat_item['groups_view_str'][] = $groups_list[$gid];
     }
     $cat_item['groups_view_str'] = implode(', ', $cat_item['groups_view_str']);

     $groups_use = !empty($cat_item['groups_use']) ? explode(',', $cat_item['groups_use']) : array();
     $cat_item['groups_use_str'] = array();
     foreach ($groups_use as $gid) {
         if (isset($groups_list[$gid])) $cat_item['groups_use_str'][] = $groups_list[$gid];
     }
     $cat_item['groups_use_str'] = implode(', ', $cat_item['groups_use_str']);

     for ($i = 1; $i <= $num_siblings_root; ++$i) {
         $xtpl->assign('WEIGHT', array(
             'key' => $i,
             'title' => $i,
             'selected' => ($i == $cat_item['weight']) ? ' selected="selected"' : ''
         ));
         $xtpl->parse('main.view.loop.weight_loop');
     }

     $cat_item['check_status'] = $cat_item['status'] == 1 ? 'checked' : '';

     $xtpl->assign('ROW', $cat_item);
     $xtpl->parse('main.view.loop');

     // Render Children
     recursive_cat_list($cat_item['catid'], $xtpl, $groups_list, $module_upload, $module_name, $client_info);
}

$xtpl->parse('main.view');


// Form
$xtpl->assign('DATA', $row);

// Parent select (Use flat list)
$xtpl->assign('parentid', $row['parentid']);
foreach ($array_cat_list as $cat) {
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
