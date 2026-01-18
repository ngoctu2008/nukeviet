<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_IS_MOD_RESEARCH_EXAM')) {
    die('Stop!!!');
}

$exam_id = $nv_Request->get_int('id', 'get', 0);
// Use standard PHP Session
$user_session = isset($_SESSION[$module_data . '_user']) ? $_SESSION[$module_data . '_user'] : array();

if (empty($user_session) || $user_session['exam_id'] != $exam_id) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=detail&id=' . $exam_id);
    die();
}

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams WHERE id=" . $exam_id;
$exam = $db->query($sql)->fetch();

if (empty($exam)) {
    die('Exam not found');
}

$page_title = $exam['title'];

// Get Questions based on Structure or Legacy
$questions = array();

// Check for structure
$sql_struct = "SELECT topic_id, quantity FROM " . NV_PREFIXLANG . "_" . $module_data . "_exam_structure WHERE exam_id=" . $exam_id;
$res_struct = $db->query($sql_struct);
$structure = array();
while ($s = $res_struct->fetch()) {
    $structure[] = $s;
}

if (!empty($structure)) {
    // Random selection based on structure
    foreach ($structure as $s) {
        $qty = $s['quantity'];
        if ($qty > 0) {
            // Select random questions from topic AND exam
            $sql_random = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE exam_id=" . $exam_id . " AND topic_id=" . $s['topic_id'] . " ORDER BY RAND() LIMIT " . $qty;
            $res_random = $db->query($sql_random);
            while ($row = $res_random->fetch()) {
                $questions[] = $row;
            }
        }
    }
    // Shuffle the final combined list (Optional: user asked for "trộn câu hỏi" - mix all parts? or keep parts?
    // "trong mỗi đề thì cần có các phần" implies parts. But "trộn câu hỏi" usually means shuffle.
    // If we keep parts, we just iterate. If we shuffle, we shuffle.
    // User request: "trong mỗi đề thì cần có các phần... Chuyên đề 1... Chuyên đề 4".
    // This implies order might matter (Section 1 then Section 2).
    // BUT "Thêm chức năng trộn câu hỏi" suggests mixing.
    // Let's assume shuffling is preferred for anti-cheating unless "Parts" are clearly labeled sections in UI.
    // Given the UI is a simple list, shuffling is safer.
    shuffle($questions);

} else {
    // Legacy / Fallback
    $limit = ($exam['num_questions'] > 0) ? "LIMIT " . $exam['num_questions'] : "";
    $sql_q = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE exam_id=" . $exam_id . " ORDER BY weight ASC, id ASC " . $limit;
    if ($exam['num_questions'] > 0) {
        $sql_q = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE exam_id=" . $exam_id . " ORDER BY RAND() " . $limit;
    }
    $result = $db->query($sql_q);
    while ($row = $result->fetch()) {
        $questions[] = $row;
    }
}

$xtpl = new XTemplate('test.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'test');
$xtpl->assign('EXAM', $exam);
$xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

// Calculate end time
$elapsed = NV_CURRENTTIME - $user_session['start_time'];
$duration_seconds = $exam['duration'] * 60;
$remaining = $duration_seconds - $elapsed;
if ($remaining < 0) $remaining = 0;

$xtpl->assign('REMAINING_SECONDS', $remaining);

// Grid loop
foreach ($questions as $index => $q) {
    $xtpl->assign('Q_GRID', array('id' => $q['id'], 'index' => $index + 1));
    $xtpl->parse('main.question_grid');
}

foreach ($questions as $index => $q) {
    $q['index'] = $index + 1;
    $xtpl->assign('Q', $q);

    // Get Answers
    $sql_a = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_answers WHERE question_id=" . $q['id'] . " ORDER BY id ASC";
    $res_a = $db->query($sql_a);

    if ($q['type'] == 1) { // Radio
        $xtpl->parse('main.loop.default_title');
        while ($ans = $res_a->fetch()) {
            $xtpl->assign('ANS', $ans);
            $xtpl->parse('main.loop.type_1.loop');
        }
        $xtpl->parse('main.loop.type_1');
    } elseif ($q['type'] == 2) { // Checkbox
        $xtpl->parse('main.loop.default_title');
        while ($ans = $res_a->fetch()) {
            $xtpl->assign('ANS', $ans);
            $xtpl->parse('main.loop.type_2.loop');
        }
        $xtpl->parse('main.loop.type_2');
    } elseif ($q['type'] == 3) { // Fill
        $content = $q['title'];
        $count = 0;
        $content = preg_replace_callback('/\[\[input\]\]/', function($matches) use (&$count, $q, $lang_module) {
            $html = '<input type="text" class="form-control d-inline-block w-auto" name="answer['.$q['id'].']['.$count.']" placeholder="'.$lang_module['fill_answer'].'">';
            $count++;
            return $html;
        }, $content);
        $q['content_parsed'] = $content;
        $xtpl->assign('Q_PARSED', $q);
        $xtpl->parse('main.loop.type_3');
    } elseif ($q['type'] == 4) { // Essay
        $xtpl->parse('main.loop.default_title');
        $xtpl->parse('main.loop.type_4');
    }

    $xtpl->parse('main.loop');
}

// Prediction is always last as per template structure
if ($exam['has_prediction'] == 1) {
    $xtpl->parse('main.prediction');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
