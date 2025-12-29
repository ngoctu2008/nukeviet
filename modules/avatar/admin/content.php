<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$row = array();
$error = array();

if ($nv_Request->isset_request('get_alias_title', 'post')) {
    $alias = $nv_Request->get_title('get_alias_title', 'post', '');
    $alias = change_alias($alias);
    if (ob_get_length()) ob_end_clean();
    die($alias);
}

$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

// Get list of categories
$sql = "SELECT catid, title, lev FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY sort ASC";
$result = $db->query($sql);
$array_cat = array();
while ($cat = $result->fetch()) {
    $array_cat[$cat['catid']] = $cat;
}

if ($nv_Request->isset_request('submit', 'post')) {
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['alias'] = $nv_Request->get_title('alias', 'post', '');
    $row['catid'] = $nv_Request->get_int('catid', 'post', 0);
    $row['image'] = $nv_Request->get_title('image', 'post', '');
    $row['description'] = $nv_Request->get_textarea('description', '', NV_ALLOWED_HTML_TAGS);
    $row['body'] = $nv_Request->get_editor('body', '', NV_ALLOWED_HTML_TAGS);

    if (empty($row['alias'])) {
        $row['alias'] = change_alias($row['title']);
    }

    if (empty($row['title'])) {
        $error[] = $lang_module['error_required_title'];
    } elseif (empty($row['image'])) {
        $error[] = $lang_module['error_required_image'];
    }

    // Check alias unique
    $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE alias = :alias AND id != :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
    $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        $error[] = $lang_module['error_alias'];
    }

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $row['views'] = 0;
                $row['downloads'] = 0;
                $row['add_time'] = NV_CURRENTTIME;
                $row['edit_time'] = NV_CURRENTTIME;

                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (catid, title, alias, image, description, body, views, downloads, add_time, edit_time, weight, status) VALUES (:catid, :title, :alias, :image, :description, :body, :views, :downloads, :add_time, :edit_time, :weight, :status)');

                $stmt->bindParam(':views', $row['views'], PDO::PARAM_INT);
                $stmt->bindParam(':downloads', $row['downloads'], PDO::PARAM_INT);
                $stmt->bindParam(':add_time', $row['add_time'], PDO::PARAM_INT);
                $stmt->bindParam(':edit_time', $row['edit_time'], PDO::PARAM_INT);
                $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows')->fetchColumn();
                $weight = intval($weight) + 1;
                $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);

                $stmt->bindValue(':status', 1, PDO::PARAM_INT);
            } else {
                $row['edit_time'] = NV_CURRENTTIME;
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET catid = :catid, title = :title, alias = :alias, image = :image, description = :description, body = :body, edit_time = :edit_time WHERE id=' . $row['id']);
                $stmt->bindParam(':edit_time', $row['edit_time'], PDO::PARAM_INT);
            }
            $stmt->bindParam(':catid', $row['catid'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $row['image'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR);
            $stmt->bindParam(':body', $row['body'], PDO::PARAM_STR);

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                if (empty($row['id'])) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Content', ' ', $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Content', 'ID: ' . $row['id'], $admin_info['userid']);
                }
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
            }
        } catch(PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); //Remove this line after checks finished
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    }
} else {
    $row['id'] = 0;
    $row['catid'] = 0;
    $row['title'] = '';
    $row['alias'] = '';
    $row['image'] = '';
    $row['description'] = '';
    $row['body'] = '';
}
if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}

$row['description'] = nv_htmlspecialchars(nv_br2nl($row['description']));
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
$row['body'] = htmlspecialchars(nv_editor_br2nl($row['body']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['body'] = nv_aleditor('body', '100%', '300px', $row['body']);
} else {
    $row['body'] = '<textarea style="width:100%;height:300px" name="body">' . $row['body'] . '</textarea>';
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

// Populate Categories
foreach ($array_cat as $cat) {
    $cat['selected'] = ($cat['catid'] == $row['catid']) ? 'selected="selected"' : '';
    $cat['title'] = str_repeat('&nbsp;&nbsp;', $cat['lev']) . $cat['title'];
    $xtpl->assign('CAT', $cat);
    $xtpl->parse('main.cat_list');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['content'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
