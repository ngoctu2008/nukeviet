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

$exam_id = $nv_Request->get_int('exam_id', 'post', 0);
$checkss = $nv_Request->get_string('checkss', 'post', '');
// Use standard PHP Session
$user_session = isset($_SESSION[$module_data . '_user']) ? $_SESSION[$module_data . '_user'] : array();

// Security Check
if ($checkss != NV_CHECK_SESSION || empty($user_session) || $user_session['exam_id'] != $exam_id) {
    die('Security Violation or Session Expired');
}

// Get Exam
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams WHERE id=" . $exam_id;
$exam = $db->query($sql)->fetch();
if (empty($exam)) die('Exam not found');

$user_id = defined('NV_IS_USER') ? $user_info['userid'] : 0;

// Prevent Duplicate Submission
if ($user_id > 0) {
    $check_stmt = $db->prepare("SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_users_result WHERE exam_id = :exam_id AND user_id = :user_id");
    $check_stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
    $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $check_stmt->execute();
    if ($check_stmt->fetch()) {
        die($lang_module['exam_already_taken']);
    }
}

// Get User Answers
$answers = $nv_Request->get_array('answer', 'post', array());
$prediction = $nv_Request->get_int('prediction', 'post', 0);

// Scoring Logic
$total_score = 0;
$essay_score = 0;
$score = 0;
$correct_count = 0;

// Need to loop through all questions of this exam to verify answers
// NOTE: We should loop through QUESTIONS to catch unanswered ones too, but for scoring strictly what was sent is okay if we assume unanswered = 0 points.
// Better: Fetch all questions of exam to look up correct answers.
$sql_q = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE exam_id=" . $exam_id;
$result_q = $db->query($sql_q);

$questions_map = array();
while ($row = $result_q->fetch()) {
    $questions_map[$row['id']] = $row;
}

// Initialize Result ID
$stmt_res = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_users_result
    (exam_id, unit_id, user_id, fullname, phone, address, score, essay_score, total_score, correct_count, prediction, time_submit, duration_used)
    VALUES (:exam_id, :unit_id, :user_id, :fullname, :phone, :address, 0, 0, 0, 0, :prediction, :time_submit, :duration_used)");

$duration_used = NV_CURRENTTIME - $user_session['start_time'];
// $user_id already defined above

$stmt_res->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
$stmt_res->bindParam(':unit_id', $user_session['unit_id'], PDO::PARAM_INT);
$stmt_res->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_res->bindParam(':fullname', $user_session['fullname'], PDO::PARAM_STR);
$stmt_res->bindParam(':phone', $user_session['phone'], PDO::PARAM_STR);
$stmt_res->bindParam(':address', $user_session['address'], PDO::PARAM_STR);
$time_submit = NV_CURRENTTIME;
$stmt_res->bindParam(':prediction', $prediction, PDO::PARAM_INT);
$stmt_res->bindParam(':time_submit', $time_submit, PDO::PARAM_INT);
$stmt_res->bindParam(':duration_used', $duration_used, PDO::PARAM_INT);
$stmt_res->execute();
$result_id = $db->lastInsertId();

// Process Answers
foreach ($questions_map as $qid => $q_data) {
    $user_ans = isset($answers[$qid]) ? $answers[$qid] : null;
    $is_correct_q = false;
    $q_points = 0;

    // Save Detail
    // user_answer storage depends on type
    $stored_answer = '';

    if ($q_data['type'] == 1) { // Radio
        if ($user_ans) {
            $stored_answer = $user_ans; // ID
            // Check correctness
            $sql_check = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_answers WHERE id=" . intval($user_ans) . " AND is_correct=1";
            if ($db->query($sql_check)->fetch()) {
                $is_correct_q = true;
                $q_points = $q_data['score'];
            }
        }
    } elseif ($q_data['type'] == 2) { // Checkbox
        if (is_array($user_ans)) {
            $stored_answer = implode(',', $user_ans); // IDs
            // Check correctness: Must select ALL correct answers and NO wrong answers?
            // Or partial? Usually strict.
            // Get all correct answers for this Q
            $sql_c = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_answers WHERE question_id=" . $qid . " AND is_correct=1";
            $correct_ids = array();
            $res_c = $db->query($sql_c);
            while ($c = $res_c->fetch()) {
                $correct_ids[] = $c['id'];
            }

            // Compare arrays
            // Check if user_ans has all correct_ids and no extra
            $diff1 = array_diff($correct_ids, $user_ans); // items in correct but not in user
            $diff2 = array_diff($user_ans, $correct_ids); // items in user but not in correct
            if (empty($diff1) && empty($diff2)) {
                $is_correct_q = true;
                $q_points = $q_data['score'];
            }
        }
    } elseif ($q_data['type'] == 3) { // Fill
        if (is_array($user_ans)) {
             $stored_answer = implode('||', $user_ans); // Text
             // Grading: Compare each input with corresponding answer order
             // Fetch correct answers ordered by ID (assuming insertion order matches input order)
             $sql_f = "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . "_answers WHERE question_id=" . $qid . " ORDER BY id ASC";
             $res_f = $db->query($sql_f);
             $correct_keywords = array();
             while ($f = $res_f->fetch()) {
                 $correct_keywords[] = $f['title'];
             }

             // Check
             // If number of inputs matches
             $all_parts_correct = true;
             if (count($user_ans) != count($correct_keywords)) {
                 $all_parts_correct = false;
             } else {
                 foreach ($user_ans as $idx => $val) {
                     if (!isset($correct_keywords[$idx])) {
                         $all_parts_correct = false; break;
                     }
                     // Normalize
                     $val_norm = mb_strtolower(trim($val));
                     $key_norm = mb_strtolower(trim($correct_keywords[$idx]));
                     if ($val_norm != $key_norm) {
                         $all_parts_correct = false; break;
                     }
                 }
             }

             if ($all_parts_correct) {
                 $is_correct_q = true;
                 $q_points = $q_data['score'];
             }
        }
    } elseif ($q_data['type'] == 4) { // Essay
        $stored_answer = $user_ans; // Text
        // No auto grading
    }

    // Update Accumulators
    if ($is_correct_q) {
        $score += $q_points;
        $correct_count++;
    }

    // Save to _users_detail
    // answer_id logic: for radio/checkbox, we might want to store relation.
    // But table structure has 'user_answer' text and 'answer_id'.
    // For Multi-choice, answer_id could be 0 and user_answer stores IDs list, OR strictly normalize.
    // Spec says: "answer_id [nếu trắc nghiệm], text_answer [nếu tự luận]"
    // If Checkbox, multiple rows? Or one row? The prompt schema allows One row per question in detail if we use text for storing multiple IDs, OR multiple rows per result-question.
    // Spec says: "_users_answer: ID, result_id, question_id, answer_id..."
    // My created schema: `_users_detail` with `result_id`, `question_id`, `answer_id`, `user_answer`.
    // I will insert one row per selected answer ID for Type 1 & 2.
    // For Type 3 & 4, I use `answer_id=0` and `user_answer` text.

    if ($q_data['type'] == 1) {
        if ($user_ans) {
            $db->query("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_users_detail (result_id, question_id, answer_id, user_answer) VALUES ($result_id, $qid, " . intval($user_ans) . ", '')");
        }
    } elseif ($q_data['type'] == 2) {
        if (is_array($user_ans)) {
            foreach ($user_ans as $aid) {
                $db->query("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_users_detail (result_id, question_id, answer_id, user_answer) VALUES ($result_id, $qid, " . intval($aid) . ", '')");
            }
        }
    } else {
        // Fill or Essay
        $val = ($stored_answer !== null) ? $stored_answer : '';
        $stmt_d = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_users_detail (result_id, question_id, answer_id, user_answer) VALUES (:rid, :qid, 0, :uans)");
        $stmt_d->bindParam(':rid', $result_id, PDO::PARAM_INT);
        $stmt_d->bindParam(':qid', $qid, PDO::PARAM_INT);
        $stmt_d->bindParam(':uans', $val, PDO::PARAM_STR);
        $stmt_d->execute();
    }
}

// Final Update Result
$total_score = $score; // Essay is 0 initially
$stmt_up = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_users_result SET score = :score, total_score = :total_score, correct_count = :correct_count WHERE id = :id");
$stmt_up->bindParam(':score', $score);
$stmt_up->bindParam(':total_score', $total_score);
$stmt_up->bindParam(':correct_count', $correct_count, PDO::PARAM_INT);
$stmt_up->bindParam(':id', $result_id, PDO::PARAM_INT);
$stmt_up->execute();

// Clear Session
if (isset($_SESSION[$module_data . '_user'])) {
    unset($_SESSION[$module_data . '_user']);
}

// Render Result Page (or Redirect)
// We will simply display the result here.
$page_title = $lang_module['result'];
$xtpl = new XTemplate('result.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('SCORE', $score);
$xtpl->assign('CORRECT_COUNT', $correct_count);
$xtpl->assign('TOTAL_QUESTIONS', count($questions_map));

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
