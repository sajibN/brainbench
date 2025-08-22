<?php
// submit_exam.php
include 'db.php';
session_start();

// 1) Must be POST from the exam form
if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !isset($_SESSION['user_id'], $_SESSION['exam_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);
$exam_id = intval($_SESSION['exam_id']);
$answers = $_POST['answer'] ?? [];

// 2) Compute score
$total = 0;
$correct_count = 0;

if (count($answers)) {
    // fetch correct answers
    $ids = array_map('intval', array_keys($answers));
    $inClause = implode(',', $ids);
    $q = $conn->query("
        SELECT id, correct_option 
          FROM questions 
         WHERE id IN ($inClause)
    ");
    $correct_map = [];
    while ($r = $q->fetch_assoc()) {
        $correct_map[intval($r['id'])] = $r['correct_option'];
    }

    foreach ($answers as $qid => $sel) {
        $qid = intval($qid);
        $total++;
        if (isset($correct_map[$qid]) && $correct_map[$qid] === $sel) {
            $correct_count++;
        }
    }
}

$score = $total ? intval(($correct_count / $total) * 100) : 0;

// 3) (Re-)ensure participant record exists
$stmt = $conn->prepare("
    INSERT IGNORE INTO exam_participants (exam_id, user_id) 
    VALUES (?, ?)
");
$stmt->bind_param('ii', $exam_id, $user_id);
$stmt->execute();

// 4) Update score and taken_at
$upd = $conn->prepare("
    UPDATE exam_participants 
       SET score = ?, taken_at = NOW() 
     WHERE exam_id = ? AND user_id = ?
");
$upd->bind_param('iii', $score, $exam_id, $user_id);
$upd->execute();

// 5) Store and redirect
$_SESSION['last_score'] = $score;
header('Location: results.php');
exit;
