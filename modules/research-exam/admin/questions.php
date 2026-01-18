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

$page_title = $lang_module['question_manager'];
$error = '';

// Check and Create Column if missing (Auto-fix for update)
$table_questions = NV_PREFIXLANG . "_" . $module_data . "_questions";
$sql_check_col = "SHOW COLUMNS FROM " . $table_questions . " LIKE 'topic_id'";
if (!$db->query($sql_check_col)->fetch()) {
    $db->query("ALTER TABLE " . $table_questions . " ADD topic_id mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER exam_id");
    $db->query("ALTER TABLE " . $table_questions . " ADD INDEX topic_id (topic_id)");
}

// Include Editor
if (defined('NV_EDITOR')) require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

// Get Exam List
$sql = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams ORDER BY id DESC";
$result = $db->query($sql);
$exams = array();
while ($row = $result->fetch()) {
    $exams[$row['id']] = $row['title'];
}

// Get Topics List
// Check table topics exists first, handled in topics.php mostly but we need it here.
// Re-using the check logic or suppressing error is better.
// Assuming topics.php was visited or I should check here too.
// Let's add the check here too for robustness.
$table_name = NV_PREFIXLANG . "_" . $module_data . "_topics";
$sql_check = "SHOW TABLES LIKE '" . $table_name . "'";
if ($db->query($sql_check)->fetchColumn() != $table_name) {
    $sql_create = "CREATE TABLE " . $table_name . " (
        id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        note text,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $db->query($sql_create);
}

$sql = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_topics ORDER BY id DESC";
$result = $db->query($sql);
$topics = array();
while ($row = $result->fetch()) {
    $topics[$row['id']] = $row['title'];
}

// Process Form Submit (Add/Edit Question)
if ($nv_Request->isset_request('save', 'post')) {
    $row = array();
    $row['id'] = $nv_Request->get_int('id', 'post', 0);
    $row['exam_id'] = $nv_Request->get_int('exam_id', 'post', 0);
    $row['topic_id'] = $nv_Request->get_int('topic_id', 'post', 0);
    $row['title'] = $nv_Request->get_editor('title', '', NV_ALLOWED_HTML_TAGS);
    $row['type'] = $nv_Request->get_int('type', 'post', 1);
    $row['score'] = $nv_Request->get_float('score', 'post', 1);
    $row['note'] = $nv_Request->get_string('note', 'post', '');

    // Answers data
    $answers = $nv_Request->get_array('answers', 'post', array());
    $correct = $nv_Request->get_array('correct', 'post', array()); // For checkbox/radio
    // For type 3 (Fill), answers are keywords. For type 4 (Essay), no answers needed.

    if (empty($row['title'])) {
        $error = $lang_module['error_title_empty'];
    } elseif (empty($row['exam_id'])) {
        $error = $lang_module['error_exam_empty'];
    } else {
        if ($row['id'] > 0) {
            $stmt = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_questions SET exam_id = :exam_id, topic_id = :topic_id, title = :title, type = :type, score = :score, note = :note WHERE id = :id");
            $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_questions (exam_id, topic_id, title, type, score, note) VALUES (:exam_id, :topic_id, :title, :type, :score, :note)");
        }
        $stmt->bindParam(':exam_id', $row['exam_id'], PDO::PARAM_INT);
        $stmt->bindParam(':topic_id', $row['topic_id'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $stmt->bindParam(':type', $row['type'], PDO::PARAM_INT);
        $stmt->bindParam(':score', $row['score']); // PDO might treat float as str, usually fine
        $stmt->bindParam(':note', $row['note'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $qid = ($row['id'] > 0) ? $row['id'] : $db->lastInsertId();

            // Handle Answers
            // First, delete old answers if edit
            $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_answers WHERE question_id = " . $qid);

            if ($row['type'] == 1 || $row['type'] == 2) {
                // Radio or Checkbox
                foreach ($answers as $key => $val) {
                    if (!empty($val)) {
                        $is_correct = 0;
                        if ($row['type'] == 1) {
                             // Radio: correct is a single value (index)
                             $radio_correct = $nv_Request->get_int('correct_radio', 'post', -1);
                             if ($radio_correct == $key) $is_correct = 1;
                        } else {
                             // Checkbox: correct is an array
                             if (isset($correct[$key])) $is_correct = 1;
                        }

                        $stmt_ans = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_answers (question_id, title, is_correct) VALUES (:qid, :title, :is_correct)");
                        $stmt_ans->bindParam(':qid', $qid, PDO::PARAM_INT);
                        $stmt_ans->bindParam(':title', $val, PDO::PARAM_STR);
                        $stmt_ans->bindParam(':is_correct', $is_correct, PDO::PARAM_INT);
                        $stmt_ans->execute();
                    }
                }
            } elseif ($row['type'] == 3) {
                // Fill in the blank
                // Answers are stored as correct keywords (is_correct = 1 by default for storage simplicity or just plain text)
                foreach ($answers as $val) {
                    if (!empty($val)) {
                        $stmt_ans = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_answers (question_id, title, is_correct) VALUES (:qid, :title, 1)");
                        $stmt_ans->bindParam(':qid', $qid, PDO::PARAM_INT);
                        $stmt_ans->bindParam(':title', $val, PDO::PARAM_STR);
                        $stmt_ans->execute();
                    }
                }
            }

            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=questions&examid=' . $row['exam_id']);
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
        // Delete answers first
        $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_answers WHERE question_id = " . $id);
        // Delete question
        $stmt = $db->prepare("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            die('OK');
        }
    }
    die('NO');
}

$xtpl = new XTemplate('questions.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'questions');

// Filter by Exam
$examid = $nv_Request->get_int('examid', 'get', 0);
foreach ($exams as $id => $title) {
    $xtpl->assign('EXAM', array('id' => $id, 'title' => $title, 'selected' => ($id == $examid) ? 'selected' : ''));
    $xtpl->parse('main.filter_exam');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// List Questions
if ($examid > 0) {
    $sql = "SELECT q.*, t.title as topic_title FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions q LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_topics t ON q.topic_id = t.id WHERE q.exam_id=" . $examid . " ORDER BY q.id DESC";
    $result = $db->query($sql);
    while ($item = $result->fetch()) {
        $item['type_text'] = $lang_module['question_type_' . $item['type']];
        $xtpl->assign('ROW', $item);
        $xtpl->parse('main.list.row');
    }
    $xtpl->parse('main.list');
}

// Add/Edit Form Logic
$id = $nv_Request->get_int('id', 'get', 0);
if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();

    // Fetch Answers
    $sql_ans = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_answers WHERE question_id=" . $id . " ORDER BY id ASC";
    $res_ans = $db->query($sql_ans);
    $i = 0;
    while ($ans = $res_ans->fetch()) {
        $ans['index'] = $i;
        $ans['checked'] = ($ans['is_correct'] == 1) ? 'checked' : '';
        $xtpl->assign('ANS', $ans);

        // Use different blocks for different types if needed, but for simplicity we might handle in JS or minimal blocks
        if ($row['type'] == 1) { // Radio
             $xtpl->parse('main.form.answers_radio.loop');
        } elseif ($row['type'] == 2) { // Checkbox
             $xtpl->parse('main.form.answers_checkbox.loop');
        } elseif ($row['type'] == 3) { // Fill
             $xtpl->parse('main.form.answers_fill.loop');
        }
        $i++;
    }

    // Parse container blocks
    if ($row['type'] == 1) $xtpl->parse('main.form.answers_radio');
    if ($row['type'] == 2) $xtpl->parse('main.form.answers_checkbox');
    if ($row['type'] == 3) $xtpl->parse('main.form.answers_fill');

} else {
    $row = array('id' => 0, 'exam_id' => $examid, 'topic_id' => 0, 'title' => '', 'type' => 1, 'score' => 1, 'note' => '');
    // Empty blocks for JS to populate or show default
    $xtpl->parse('main.form.answers_radio'); // Default show radio container
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['title'] = nv_aleditor('title', '100%', '200px', $row['title']);
} else {
    $row['title'] = '<textarea style="width:100%;height:200px" name="title">' . $row['title'] . '</textarea>';
}

// Parse Form Exams
foreach ($exams as $eid => $etitle) {
    $selected = ($eid == $row['exam_id']) ? 'selected' : '';
    $xtpl->assign('EXAM', array('id' => $eid, 'title' => $etitle, 'selected' => $selected));
    $xtpl->parse('main.form.form_exam');
}

// Parse Form Topics
foreach ($topics as $tid => $ttitle) {
    $selected = ($tid == $row['topic_id']) ? 'selected' : '';
    $xtpl->assign('TOPIC', array('id' => $tid, 'title' => $ttitle, 'selected' => $selected));
    $xtpl->parse('main.form.form_topic');
}

$xtpl->assign('DATA', $row);
// Select Type
for ($k = 1; $k <= 4; $k++) {
    $xtpl->assign('TYPE', array('id' => $k, 'title' => $lang_module['question_type_' . $k], 'selected' => ($k == $row['type']) ? 'selected' : ''));
    $xtpl->parse('main.form.type_loop');
}

$xtpl->parse('main.form');
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
