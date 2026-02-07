<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['add_popup'];

$id = $nv_Request->get_int('id', 'get', 0);
$row = [
    'id' => 0,
    'title' => '',
    'content' => '',
    'type' => 'modal',
    'display_pages' => [],
    'user_groups' => [],
    'device_type' => 'all',
    'trigger_config' => ['type' => 'immediate', 'value' => 0],
    'begin_time' => 0,
    'end_time' => 0,
    'frequency' => 0,
    'priority' => 1,
    'status' => 1
];

if ($id > 0) {
    $row = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id)->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    $page_title = $lang_module['edit_popup'];
    $row['display_pages'] = !empty($row['display_pages']) ? json_decode($row['display_pages'], true) : [];
    $row['user_groups'] = !empty($row['user_groups']) ? json_decode($row['user_groups'], true) : [];
    $row['trigger_config'] = !empty($row['trigger_config']) ? json_decode($row['trigger_config'], true) : ['type' => 'immediate', 'value' => 0];
}

if ($nv_Request->isset_request('submit', 'post')) {
    $row['title'] = $nv_Request->get_string('title', 'post', '');
    $row['content'] = $nv_Request->get_string('content', 'post', '', false);
    $row['type'] = $nv_Request->get_string('type', 'post', 'modal');
    $row['display_pages'] = $nv_Request->get_array('display_pages', 'post', []);
    $row['user_groups'] = $nv_Request->get_array('user_groups', 'post', []);
    $row['device_type'] = $nv_Request->get_string('device_type', 'post', 'all');
    $row['frequency'] = $nv_Request->get_int('frequency', 'post', 0);
    $row['priority'] = $nv_Request->get_int('priority', 'post', 0);
    $row['status'] = isset($_POST['status']) ? 1 : 0;

    $trigger_type = $nv_Request->get_string('trigger_type', 'post', 'immediate');
    $trigger_value = $nv_Request->get_int('trigger_value', 'post', 0);
    $row['trigger_config'] = ['type' => $trigger_type, 'value' => $trigger_value];

    $begin_time = $nv_Request->get_string('begin_time', 'post', '');
    $end_time = $nv_Request->get_string('end_time', 'post', '');

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $begin_time, $m)) {
        $row['begin_time'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $row['begin_time'] = 0;
    }

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $end_time, $m)) {
        $row['end_time'] = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['end_time'] = 0;
    }

    if (empty($row['title'])) {
        $error = $lang_module['error_title'];
    } else {
        $stm_cols = "title, content, type, display_pages, user_groups, device_type, trigger_config, begin_time, end_time, frequency, priority, status";
        $stm_vals = ":title, :content, :type, :display_pages, :user_groups, :device_type, :trigger_config, :begin_time, :end_time, :frequency, :priority, :status";

        if ($id > 0) {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET
                title=:title, content=:content, type=:type, display_pages=:display_pages, user_groups=:user_groups,
                device_type=:device_type, trigger_config=:trigger_config, begin_time=:begin_time, end_time=:end_time,
                frequency=:frequency, priority=:priority, status=:status WHERE id=" . $id;
        } else {
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_rows ($stm_cols) VALUES ($stm_vals)";
        }

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':title', $row['title'], PDO::PARAM_STR);
        $stmt->bindValue(':content', $row['content'], PDO::PARAM_STR);
        $stmt->bindValue(':type', $row['type'], PDO::PARAM_STR);
        $stmt->bindValue(':display_pages', json_encode($row['display_pages']), PDO::PARAM_STR);
        $stmt->bindValue(':user_groups', json_encode($row['user_groups']), PDO::PARAM_STR);
        $stmt->bindValue(':device_type', $row['device_type'], PDO::PARAM_STR);
        $stmt->bindValue(':trigger_config', json_encode($row['trigger_config']), PDO::PARAM_STR);
        $stmt->bindValue(':begin_time', $row['begin_time'], PDO::PARAM_INT);
        $stmt->bindValue(':end_time', $row['end_time'], PDO::PARAM_INT);
        $stmt->bindValue(':frequency', $row['frequency'], PDO::PARAM_INT);
        $stmt->bindValue(':priority', $row['priority'], PDO::PARAM_INT);
        $stmt->bindValue(':status', $row['status'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        } else {
            $error = $lang_module['error_save'];
        }
    }
}

// Prepare View Data
$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// Editor
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['content'] = nv_aleditor('content', '100%', '300px', $row['content']);
} else {
    $row['content'] = '<textarea style="width:100%;height:300px" name="content">' . $row['content'] . '</textarea>';
}
$xtpl->assign('EDITOR', $row['content']);

// Types
$popup_types = nv_get_popup_types();
foreach ($popup_types as $key => $title) {
    $xtpl->assign('TYPE', [
        'key' => $key,
        'title' => $title,
        'selected' => ($key == $row['type']) ? 'selected' : ''
    ]);
    $xtpl->parse('main.type');
}

// Triggers
$trigger_types = nv_get_trigger_types();
foreach ($trigger_types as $key => $title) {
    $xtpl->assign('TRIGGER', [
        'key' => $key,
        'title' => $title,
        'selected' => ($key == $row['trigger_config']['type']) ? 'selected' : ''
    ]);
    $xtpl->parse('main.trigger');
}
$xtpl->assign('TRIGGER_VALUE', $row['trigger_config']['value']);

// Modules
foreach ($site_mods as $mod_name => $mod_info) {
    $xtpl->assign('MOD', [
        'value' => $mod_name,
        'title' => $mod_info['custom_title'],
        'checked' => in_array($mod_name, $row['display_pages']) ? 'checked' : ''
    ]);
    $xtpl->parse('main.module');
}

// Groups
$groups = nv_groups_list();
foreach ($groups as $gid => $title) {
    $xtpl->assign('GROUP', [
        'value' => $gid,
        'title' => $title,
        'checked' => in_array($gid, $row['user_groups']) ? 'checked' : ''
    ]);
    $xtpl->parse('main.group');
}

// Devices
$devices = ['all', 'mobile', 'desktop'];
foreach ($devices as $dev) {
    $xtpl->assign('DEVICE', [
        'value' => $dev,
        'title' => $lang_module['device_' . $dev],
        'selected' => ($dev == $row['device_type']) ? 'selected' : ''
    ]);
    $xtpl->parse('main.device');
}

// Dates
if ($row['begin_time'] > 0) $xtpl->assign('BEGIN_TIME', date('d/m/Y', $row['begin_time']));
if ($row['end_time'] > 0) $xtpl->assign('END_TIME', date('d/m/Y', $row['end_time']));

$xtpl->assign('STATUS_CHECKED', $row['status'] ? 'checked' : '');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
