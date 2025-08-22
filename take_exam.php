<?php
// take_exam.php
include 'db.php';
session_start();

// Ensure they’ve joined
if (!isset($_SESSION['user_id'], $_SESSION['exam_id'])) {
    header('Location: join_exam.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);
$exam_id = intval($_SESSION['exam_id']);

// Fetch exam info
$stmt = $conn->prepare("SELECT title, duration_minutes FROM exams WHERE id = ?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$exam = $stmt->get_result()->fetch_assoc();

if (!$exam) {
    die("Exam not found. <a href='index.php'>Back to home</a>");
}

// Fetch questions for this exam
$stmt = $conn->prepare("
    SELECT q.id, q.question, q.option_a, q.option_b, q.option_c, q.option_d
      FROM exam_questions eq
      JOIN questions q ON eq.question_id = q.id
     WHERE eq.exam_id = ?
     ORDER BY eq.id ASC
");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// If no questions assigned yet, show message and exit
if (empty($questions)) {
    include 'header.php';
    echo "<div class='container'><div class='card'>";
    echo "<h2>No Questions Available</h2>";
    echo "<p>The exam “" . htmlspecialchars($exam['title']) . "” has no questions assigned yet.</p>";
    echo "<p><a href='index.php'>← Back to Home</a></p>";
    echo "</div></div>";
    include 'footer.php';
    exit;
}

// Calculate remaining seconds
$remaining = intval($exam['duration_minutes']) * 60;

include 'header.php';
?>

<div class="container">
  <div class="card">
    <div class="exam-top">
      <h2><?= htmlspecialchars($exam['title']) ?></h2>
      <span class="timer" id="examTimer">00:00</span>
    </div>

    <form action="submit_exam.php" method="POST" id="examForm">
      <input type="hidden" name="exam_id" value="<?= $exam_id ?>">
      <?php foreach ($questions as $i => $q): ?>
        <div class="card" style="margin-bottom:12px">
          <p><strong>Q<?= $i + 1 ?>.</strong> <?= htmlspecialchars($q['question']) ?></p>
          <div style="display:flex;flex-direction:column;gap:6px">
            <label><input type="radio" name="answer[<?= $q['id'] ?>]" value="a"> <?= htmlspecialchars($q['option_a']) ?></label>
            <label><input type="radio" name="answer[<?= $q['id'] ?>]" value="b"> <?= htmlspecialchars($q['option_b']) ?></label>
            <label><input type="radio" name="answer[<?= $q['id'] ?>]" value="c"> <?= htmlspecialchars($q['option_c']) ?></label>
            <label><input type="radio" name="answer[<?= $q['id'] ?>]" value="d"> <?= htmlspecialchars($q['option_d']) ?></label>
          </div>
        </div>
      <?php endforeach; ?>

      <button class="primary" type="submit">Submit Exam</button>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
<script>
  // start timer
  const remaining = <?= $remaining ?>;
  startExamTimer(remaining, '#examTimer', () => {
    alert('Time is up! Submitting exam.');
    document.getElementById('examForm').submit();
  });
</script>
