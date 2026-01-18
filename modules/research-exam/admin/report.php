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

$page_title = $lang_module['report_manager'];
$error = '';

// Get Active Exam for Filter
$exams = array();
$sql = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams ORDER BY id DESC";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $exams[$row['id']] = $row['title'];
}
$examid = $nv_Request->get_int('examid', 'get', key($exams)); // Default to latest

// Get Units
$units = nv_get_units();

// Calculate Total Questions for Prediction Logic (Used in both Export and Main View)
$total_questions = $db->query("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE exam_id=" . $examid)->fetchColumn();

// Export Excel Logic (Simple HTML/CSV)
if ($nv_Request->isset_request('export', 'post')) {
    $type = $nv_Request->get_string('export_type', 'post', 'individual');

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=report_" . $type . "_" . date('Ymd_His') . ".xls");
    echo "\xEF\xBB\xBF"; // UTF-8 BOM

    if ($type == 'individual') {
        echo '<table border="1">';
        echo '<tr><th>STT</th><th>' . $lang_module['fullname'] . '</th><th>' . $lang_module['phone'] . '</th><th>' . $lang_module['unit'] . '</th><th>' . $lang_module['correct_count'] . '</th><th>' . $lang_module['score'] . '</th><th>' . $lang_module['essay_score'] . '</th><th>' . $lang_module['total_score'] . '</th><th>Dự đoán</th><th>Thời gian nộp</th></tr>';

        // Correct sorting by Score -> Prediction Accuracy (Diff from Count of Perfect Scores) -> Time
        // We inject the subquery with the calculated total_questions variable
        // Added GROUP BY r.id to ensure no duplicates if joins cause issues (though 1:1 unit join shouldn't, safety first)
        $sql = "SELECT r.*, u.title as unit_title FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_result r LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_units u ON r.unit_id = u.id WHERE r.exam_id=" . $examid . " GROUP BY r.id ORDER BY r.total_score DESC, ABS(r.prediction - (SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_result WHERE exam_id=" . $examid . " AND correct_count = " . intval($total_questions) . ")) ASC, r.time_submit ASC";

        $res = $db->query($sql);
        $i = 1;
        while ($row = $res->fetch()) {
            echo '<tr>';
            echo '<td>' . $i++ . '</td>';
            echo '<td>' . $row['fullname'] . '</td>';
            echo '<td>' . $row['phone'] . '</td>';
            echo '<td>' . $row['unit_title'] . '</td>';
            echo '<td>' . $row['correct_count'] . '</td>';
            echo '<td>' . $row['score'] . '</td>';
            echo '<td>' . $row['essay_score'] . '</td>';
            echo '<td>' . $row['total_score'] . '</td>';
            echo '<td>' . $row['prediction'] . '</td>';
            echo '<td>' . date('d/m/Y H:i:s', $row['time_submit']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        // Collective
        echo '<table border="1">';
        echo '<tr><th>STT</th><th>' . $lang_module['unit_title'] . '</th><th>Số lượng người thi</th><th>Tổng điểm</th></tr>';
        $sql = "SELECT u.title, COUNT(r.id) as num_users, SUM(r.total_score) as total_points FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_result r LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_units u ON r.unit_id = u.id WHERE r.exam_id=" . $examid . " GROUP BY r.unit_id ORDER BY num_users DESC";
        $res = $db->query($sql);
        $i = 1;
        while ($row = $res->fetch()) {
             echo '<tr>';
             echo '<td>' . $i++ . '</td>';
             echo '<td>' . $row['title'] . '</td>';
             echo '<td>' . $row['num_users'] . '</td>';
             echo '<td>' . $row['total_points'] . '</td>';
             echo '</tr>';
        }
        echo '</table>';
    }
    die();
}

// Grading Action
if ($nv_Request->isset_request('save_grade', 'post')) {
    $rid = $nv_Request->get_int('result_id', 'post', 0);
    $escore = $nv_Request->get_float('essay_score', 'post', 0);

    // Get current score
    $curr = $db->query("SELECT score FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_result WHERE id=" . $rid)->fetch();
    if ($curr) {
        $new_total = $curr['score'] + $escore;
        $stmt = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_users_result SET essay_score = :es, total_score = :ts WHERE id = :id");
        $stmt->bindParam(':es', $escore);
        $stmt->bindParam(':ts', $new_total);
        $stmt->bindParam(':id', $rid, PDO::PARAM_INT);
        $stmt->execute();
    }
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=report&examid=' . $examid);
    die();
}


$xtpl = new XTemplate('report.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'report');

// Filter Form
foreach ($exams as $id => $title) {
    $xtpl->assign('EXAM', array('id' => $id, 'title' => $title, 'selected' => ($id == $examid) ? 'selected' : ''));
    $xtpl->parse('main.filter_exam');
}

// Tab 1: Individual List
$sql = "SELECT r.*, u.title as unit_title FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_result r LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_units u ON r.unit_id = u.id WHERE r.exam_id=" . $examid . " ORDER BY r.total_score DESC, r.id DESC LIMIT 50";
$res = $db->query($sql);
while ($row = $res->fetch()) {
    $row['time_submit'] = date('d/m/Y H:i', $row['time_submit']);

    $xtpl->assign('ROW', $row);

    // Logic to show/hide button or styling
    // We need to know if this row HAS essay data in the JSON we prepared below?
    // But we prepare JSON below. We should move the JSON prep UP or just parse row later?
    // Moving query up is cleaner.

    $xtpl->parse('main.individual.row');
}

// Fetch Essay Data first to check existence in loop if needed,
// OR simpler: Prepare array first.
$sql_essay = "SELECT d.result_id, d.user_answer, q.title as question_title, q.score as max_score FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_detail d JOIN " . NV_PREFIXLANG . "_" . $module_data . "_questions q ON d.question_id = q.id JOIN " . NV_PREFIXLANG . "_" . $module_data . "_users_result r ON d.result_id = r.id WHERE q.type = 4 AND r.exam_id = " . $examid;
$res_e = $db->query($sql_essay);
$essay_data = array();
while ($e = $res_e->fetch()) {
    $essay_data[$e['result_id']][] = $e;
}
$xtpl->assign('ESSAY_DATA_JSON', json_encode($essay_data));

// Now loop rows (reset pointer or just do it logic correctly? The code above loop was already executed in memory model?
// No, I need to replace the loop block.
// Let's restart the loop logic here in the replacement block.

// Clear previous loop (in concept, but I am replacing the block).
// Re-query or just re-iterate?
// I cannot easily re-iterate $res.
// So I will move the essay query BEFORE the loop in my replacement block.

$res->closeCursor(); // Close previous cursor if needed
// Re-run main query or fetch all to array?
// Fetch all to array is safer.
$sql = "SELECT r.*, u.title as unit_title FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_result r LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_units u ON r.unit_id = u.id WHERE r.exam_id=" . $examid . " GROUP BY r.id ORDER BY r.total_score DESC, ABS(r.prediction - (SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_result WHERE exam_id=" . $examid . " AND correct_count = " . intval($total_questions) . ")) ASC, r.time_submit ASC LIMIT 50";
$res_rows = $db->query($sql);

while ($row = $res_rows->fetch()) {
    $row['time_submit'] = date('d/m/Y H:i', $row['time_submit']);

    // Check if has essay
    if (isset($essay_data[$row['id']])) {
        $xtpl->assign('BTN_CLASS', 'btn-primary');
        $xtpl->assign('BTN_ICON', 'fa-pencil-square-o');
        $xtpl->assign('BTN_TEXT', $lang_module['grade_essay']);
        $xtpl->parse('main.individual.row.has_essay');
    } else {
         // Optional: Show disabled button or nothing?
         // If no essay, maybe just "View Detail" if we had it, but for now just hide or show generic
    }

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.individual.row');
}
$xtpl->parse('main.individual');


$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
