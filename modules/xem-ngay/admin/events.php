<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOURNAME (email@domain.com)
 * @Copyright (C) 2024 YOURNAME. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 26 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = "Quản lý Sự kiện khác";
$module_table_name = str_replace('-', '_', $module_data);
$table_events = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_table_name . "_events";

$xtpl = new XTemplate('events.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events');

// Robust Table Check
$table_exists = false;
try {
    $db->query("SELECT 1 FROM " . $table_events . " LIMIT 1");
    $table_exists = true;
} catch (PDOException $e) {
    $table_exists = false;
}

if (!$table_exists) {
    $sql_create = "CREATE TABLE " . $table_events . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        description mediumtext,
        config mediumtext,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $db->query($sql_create);
} else {
    // Check for config column
    try {
        $result = $db->query("SHOW COLUMNS FROM " . $table_events . " LIKE 'config'");
        if ($result->rowCount() == 0) {
            $db->query("ALTER TABLE " . $table_events . " ADD COLUMN config MEDIUMTEXT");
        }
    } catch (PDOException $e) {
        // Column check failed, maybe syntax or perm?
    }
}

// Handle Delete
$del_id = $nv_Request->get_int('del_id', 'get', 0);
if ($del_id > 0) {
    $db->query("DELETE FROM " . $table_events . " WHERE id = " . $del_id);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events');
}

// Fetch Data for Edit
$id = $nv_Request->get_int('id', 'get', 0);
$row_edit = [];
$current_config = [];

if ($id > 0) {
    $sql = "SELECT * FROM " . $table_events . " WHERE id = " . $id;
    $result = $db->query($sql);
    $row_edit = $result->fetch();
    if (!empty($row_edit)) {
        $xtpl->assign('DATA', $row_edit);
        $xtpl->assign('FORM_TITLE', 'Sửa sự kiện');
        if (!empty($row_edit['config'])) {
            $current_config = unserialize($row_edit['config']);
        }
    }
} else {
    $xtpl->assign('FORM_TITLE', 'Thêm sự kiện mới');
}

// Assign Config Checkboxes
$logic_options = [
    'check_kim_lau' => 'Kiểm tra Kim Lâu',
    'check_hoang_oc' => 'Kiểm tra Hoang Ốc',
    'check_tam_tai' => 'Kiểm tra Tam Tai'
];
foreach ($logic_options as $key => $label) {
    $xtpl->assign('LOGIC', [
        'key' => $key,
        'label' => $label,
        'checked' => (isset($current_config['logic']) && in_array($key, $current_config['logic'])) ? 'checked' : ''
    ]);
    $xtpl->parse('main.logic_option');
}

$input_options = [
    'input_partner' => 'Nhập tuổi Đối tác / Vợ chồng'
];
foreach ($input_options as $key => $label) {
    $xtpl->assign('INPUT', [
        'key' => $key,
        'label' => $label,
        'checked' => (isset($current_config['inputs']) && in_array($key, $current_config['inputs'])) ? 'checked' : ''
    ]);
    $xtpl->parse('main.input_option');
}

// Handle Save (Add/Edit)
if ($nv_Request->isset_request('save', 'post')) {
    $save_id = $nv_Request->get_int('id', 'post', 0);
    $title = $nv_Request->get_string('title', 'post', '');
    $description = $nv_Request->get_string('description', 'post', '');

    $logic_sel = $nv_Request->get_array('logic_config', 'post', []);
    $input_sel = $nv_Request->get_array('input_config', 'post', []);

    $config_arr = [
        'logic' => $logic_sel,
        'inputs' => $input_sel
    ];
    $config_str = serialize($config_arr);

    if (!empty($title)) {
        if ($save_id > 0) {
            $sql = "UPDATE " . $table_events . " SET title = :title, description = :description, config = :config WHERE id = " . $save_id;
            $db->query($sql, [':title' => $title, ':description' => $description, ':config' => $config_str]);
        } else {
            $sql = "INSERT INTO " . $table_events . " (title, description, config) VALUES (:title, :description, :config)";
            $data_insert = [
                'title' => $title,
                'description' => $description,
                'config' => $config_str
            ];
            $db->insert_id($sql, 'id', $data_insert);
        }
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events');
    }
}

// List Data
$sql = "SELECT * FROM " . $table_events . " ORDER BY id DESC";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events&id=' . $row['id'];
    $row['delete_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events&del_id=' . $row['id'];
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
