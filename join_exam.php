<?php
// join_exam.php
include 'db.php';
session_start();

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$exam_id  = intval($_POST['exam_id'] ?? 0);

if ($username === '' || $exam_id === 0) {
    die("Invalid input. <a href='index.php'>Go back</a>");
}

// 1) Find or create user
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows) {
    $user_id = $res->fetch_assoc()['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO users (username) VALUES (?)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_id = $stmt->insert_id;
}

// 2) Register as participant (no duplicates)
$stmt = $conn->prepare("
    INSERT IGNORE INTO exam_participants (exam_id, user_id)
    VALUES (?, ?)
");
$stmt->bind_param("ii", $exam_id, $user_id);
$stmt->execute();

// 3) Store in session and redirect to take_exam.php
$_SESSION['user_id'] = $user_id;
$_SESSION['exam_id'] = $exam_id;

header("Location: take_exam.php");
exit;
