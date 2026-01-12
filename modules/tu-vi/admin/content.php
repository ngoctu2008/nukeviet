<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_TU_VI_ADMIN')) {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin/admin.functions.php';
}

$id = $nv_Request->get_int('id', 'get,post', 0);
$error = '';

if ($nv_Request->isset_request('submit', 'post')) {
    $row = [
        'star_key' => $nv_Request->get_title('star_key', 'post', ''),
        'palace_key' => $nv_Request->get_title('palace_key', 'post', ''),
        'content' => $nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS)
    ];

    if (empty($row['star_key'])) {
        $error = $lang_module['error_star_key'];
    } elseif (empty($row['palace_key'])) {
        $error = $lang_module['error_palace_key'];
    } else {
        if ($id > 0) {
            $stmt = $db->prepare("UPDATE " . NV_PRE_TUVI . "_interpretations SET star_key = :star, palace_key = :palace, content = :content WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO " . NV_PRE_TUVI . "_interpretations (star_key, palace_key, content) VALUES (:star, :palace, :content)");
        }
        $stmt->bindParam(':star', $row['star_key'], PDO::PARAM_STR);
        $stmt->bindParam(':palace', $row['palace_key'], PDO::PARAM_STR);
        $stmt->bindParam(':content', $row['content'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
        } else {
            $error = $lang_global['error_save'];
        }
    }
} else {
    if ($id > 0) {
        $row = $db->query("SELECT * FROM " . NV_PRE_TUVI . "_interpretations WHERE id=" . $id)->fetch();
    } else {
        $row = ['star_key' => '', 'palace_key' => '', 'content' => ''];
    }
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('ROW', $row);
$xtpl->assign('ERROR', $error);
$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content&id=' . $id);

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITOR . '/nv.php';
}
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['content'] = nv_aleditor('content', '100%', '300px', $row['content']);
} else {
    $row['content'] = '<textarea style="width:100%;height:300px" name="content" id="content">' . $row['content'] . '</textarea>';
}
$xtpl->assign('CONTENT', $row['content']);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
