<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_TU_VI_ADMIN')) {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin.functions.php';
}

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get,post', 0);
$error = '';

$topics = [
    'tong_quan' => $lang_module['topic_tong_quan'],
    'tinh_cach' => $lang_module['topic_tinh_cach'],
    'ngoai_hinh' => $lang_module['topic_ngoai_hinh'],
    'cong_danh' => $lang_module['topic_cong_danh'],
    'tai_loc' => $lang_module['topic_tai_loc'],
    'tinh_duyen' => $lang_module['topic_tinh_duyen'],
    'tai_san' => $lang_module['topic_tai_san'],
    'benh_tat' => $lang_module['topic_benh_tat'],
    'van_han' => $lang_module['topic_van_han'],
    'quan_he' => $lang_module['topic_quan_he']
];

if ($nv_Request->isset_request('submit', 'post')) {
    $row = [
        'star_key' => $nv_Request->get_title('star_key', 'post', ''),
        'palace_key' => $nv_Request->get_title('palace_key', 'post', ''),
        'topic' => $nv_Request->get_title('topic', 'post', 'tong_quan'),
        'content' => $nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS),
        'weight' => $nv_Request->get_int('weight', 'post', 0)
    ];

    if (empty($row['star_key'])) {
        $error = $lang_module['error_star_key'];
    } elseif (empty($row['palace_key'])) {
        $error = $lang_module['error_palace_key'];
    } else {
        if ($id > 0) {
            $stmt = $db->prepare("UPDATE " . NV_PRE_TUVI . "_interpretations SET star_key = :star, palace_key = :palace, topic = :topic, content = :content, weight = :weight WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO " . NV_PRE_TUVI . "_interpretations (star_key, palace_key, topic, content, weight) VALUES (:star, :palace, :topic, :content, :weight)");
        }
        $stmt->bindParam(':star', $row['star_key'], PDO::PARAM_STR);
        $stmt->bindParam(':palace', $row['palace_key'], PDO::PARAM_STR);
        $stmt->bindParam(':topic', $row['topic'], PDO::PARAM_STR);
        $stmt->bindParam(':content', $row['content'], PDO::PARAM_STR);
        $stmt->bindParam(':weight', $row['weight'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=data');
        } else {
            $error = $lang_global['error_save'];
        }
    }
} else {
    if ($id > 0) {
        $row = $db->query("SELECT * FROM " . NV_PRE_TUVI . "_interpretations WHERE id=" . $id)->fetch();
    } else {
        $row = ['star_key' => '', 'palace_key' => '', 'topic' => 'tong_quan', 'content' => '', 'weight' => 0];
    }
}

$xtpl = new XTemplate('data.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('ROW', $row);
$xtpl->assign('ERROR', $error);
$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=data&id=' . $id);

// Assign Topics
foreach ($topics as $key => $title) {
    $xtpl->assign('TOPIC', [
        'key' => $key,
        'title' => $title,
        'selected' => ($key == $row['topic']) ? 'selected="selected"' : ''
    ]);
    $xtpl->parse('main.form.topic');
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['content'] = nv_aleditor('content', '100%', '300px', $row['content']);
} else {
    $row['content'] = '<textarea style="width:100%;height:300px" name="content" id="content">' . $row['content'] . '</textarea>';
}
$xtpl->assign('CONTENT', $row['content']);

// List View
$sql = "SELECT * FROM " . NV_PRE_TUVI . "_interpretations ORDER BY id DESC LIMIT 50";
$result = $db->query($sql);
while ($item = $result->fetch()) {
    $item['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=data&id=' . $item['id'];
    $item['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=del&id=' . $item['id'];

    // Map topic key to title
    $topic_lang_key = 'topic_' . $item['topic'];
    if (isset($lang_module[$topic_lang_key])) {
        $item['topic'] = $lang_module[$topic_lang_key];
    }

    $xtpl->assign('ITEM', $item);
    $xtpl->parse('main.list.loop');
}
$xtpl->parse('main.list');

$xtpl->parse('main.form');
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
