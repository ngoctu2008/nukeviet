<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['exam_manager'];
$error = '';

// Include DatePicker
if (defined('NV_EDITOR')) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

// Process Form Submit
if ($nv_Request->isset_request('save', 'post')) {
    $row = array();
    $row['id'] = $nv_Request->get_int('id', 'post', 0);
    $row['title'] = $nv_Request->get_string('title', 'post', '');
    $row['alias'] = change_alias($row['title']);
    $row['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);
    $row['time_start'] = $nv_Request->get_string('time_start', 'post', '');
    $row['time_end'] = $nv_Request->get_string('time_end', 'post', '');
    $row['duration'] = $nv_Request->get_int('duration', 'post', 0);
    $row['num_questions'] = $nv_Request->get_int('num_questions', 'post', 0);
    $row['status'] = $nv_Request->get_int('status', 'post', 1);
    $row['has_prediction'] = $nv_Request->get_int('has_prediction', 'post', 0);

    // Structure Data
    $structure = $nv_Request->get_array('structure', 'post', array()); // Array [topic_id => quantity]

    // Convert date to timestamp
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $row['time_start'], $m)) {
        $row['time_start'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $row['time_start'] = 0;
    }
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $row['time_end'], $m)) {
        $row['time_end'] = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['time_end'] = 0;
    }

    if (empty($row['title'])) {
        $error = $lang_module['error_title_empty'];
    } else {
        if ($row['id'] > 0) {
            $stmt = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_exams SET title = :title, alias = :alias, description = :description, time_start = :time_start, time_end = :time_end, duration = :duration, num_questions = :num_questions, status = :status, has_prediction = :has_prediction WHERE id = :id");
            $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_exams (title, alias, description, time_start, time_end, duration, num_questions, status, has_prediction) VALUES (:title, :alias, :description, :time_start, :time_end, :duration, :num_questions, :status, :has_prediction)");
        }
        $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $stmt->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR);
        $stmt->bindParam(':time_start', $row['time_start'], PDO::PARAM_INT);
        $stmt->bindParam(':time_end', $row['time_end'], PDO::PARAM_INT);
        $stmt->bindParam(':duration', $row['duration'], PDO::PARAM_INT);
        $stmt->bindParam(':num_questions', $row['num_questions'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
        $stmt->bindParam(':has_prediction', $row['has_prediction'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $exam_id = ($row['id'] > 0) ? $row['id'] : $db->lastInsertId();

            // Save Structure
            $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_exam_structure WHERE exam_id=" . $exam_id);
            if (!empty($structure)) {
                $stmt_struct = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_exam_structure (exam_id, topic_id, quantity) VALUES (:exam_id, :topic_id, :quantity)");
                foreach ($structure as $topic_id => $qty) {
                    if ($qty > 0) {
                        $stmt_struct->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
                        $stmt_struct->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
                        $stmt_struct->bindParam(':quantity', $qty, PDO::PARAM_INT);
                        $stmt_struct->execute();
                    }
                }
            }

            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=exam');
            die();
        } else {
            $error = $lang_global['error_save'];
        }
    }
}

// Process Delete
if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('delete', 'post', 0);
    if ($id > 0) {
        $stmt = $db->prepare("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            // Delete structure
            $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_exam_structure WHERE exam_id=" . $id);
            die('OK');
        }
    }
    die('NO');
}

$xtpl = new XTemplate('exam.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'exam');

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// List Exams
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams ORDER BY id DESC";
$result = $db->query($sql);

while ($item = $result->fetch()) {
    $item['time_start'] = ($item['time_start'] > 0) ? date('d/m/Y', $item['time_start']) : '';
    $item['time_end'] = ($item['time_end'] > 0) ? date('d/m/Y', $item['time_end']) : '';
    $xtpl->assign('ROW', $item);
    $xtpl->parse('main.list.row');
}
$xtpl->parse('main.list');

// Add/Edit Form
$id = $nv_Request->get_int('id', 'get', 0);
$structure_data = array();

if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $row['time_start'] = ($row['time_start'] > 0) ? date('d/m/Y', $row['time_start']) : '';
    $row['time_end'] = ($row['time_end'] > 0) ? date('d/m/Y', $row['time_end']) : '';

    // Get Structure
    $res_struct = $db->query("SELECT topic_id, quantity FROM " . NV_PREFIXLANG . "_" . $module_data . "_exam_structure WHERE exam_id=" . $id);
    while ($s = $res_struct->fetch()) {
        $structure_data[$s['topic_id']] = $s['quantity'];
    }
} else {
    $row = array(
        'id' => 0, 'title' => '', 'description' => '',
        'time_start' => date('d/m/Y'),
        'time_end' => date('d/m/Y', strtotime('+7 days')),
        'duration' => 30, 'num_questions' => 20,
        'status' => 1, 'has_prediction' => 0
    );
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['description'] = nv_aleditor('description', '100%', '300px', $row['description']);
} else {
    $row['description'] = '<textarea style="width:100%;height:300px" name="description">' . $row['description'] . '</textarea>';
}

// Load Topics for Structure Config
$sql_topics = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topics ORDER BY id DESC";
$res_topics = $db->query($sql_topics);
while ($topic = $res_topics->fetch()) {
    $topic['quantity'] = isset($structure_data[$topic['id']]) ? $structure_data[$topic['id']] : 0;
    $xtpl->assign('TOPIC', $topic);
    $xtpl->parse('main.form.topics.loop');
}
$xtpl->parse('main.form.topics');

$xtpl->assign('DATA', $row);
$xtpl->assign('STATUS_CHECKED', $row['status'] == 1 ? 'checked' : '');
$xtpl->assign('PREDICTION_CHECKED', $row['has_prediction'] == 1 ? 'checked' : '');

$xtpl->parse('main.form');
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
