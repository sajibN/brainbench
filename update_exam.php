<?php
// update_exam.php
include 'db.php';
include 'header.php';
session_start();

$msg = '';
$exam_id = intval($_GET['exam_id'] ?? 0);

// Fetch existing exam
$stmt = $conn->prepare("SELECT title, start_time, end_time, duration_minutes FROM exams WHERE id = ?");
$stmt->bind_param('i', $exam_id);
$stmt->execute();
$exam = $stmt->get_result()->fetch_assoc();

if (!$exam) {
    die("Exam not found. <a href='admin.php'>Back</a>");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_exam'])) {
    $title    = trim($_POST['title'] ?? '');
    $start    = $_POST['start_time'] ?: NULL;
    $end      = $_POST['end_time']   ?: NULL;
    $duration = intval($_POST['duration'] ?? 0);

    if ($title === '') {
        $msg = "Title cannot be empty.";
    } else {
        $up = $conn->prepare("
            UPDATE exams
               SET title = ?,
                   start_time = ?,
                   end_time   = ?,
                   duration_minutes = ?
             WHERE id = ?
        ");
        $up->bind_param('sssii', $title, $start, $end, $duration, $exam_id);
        if ($up->execute()) {
            $msg = "Exam updated successfully.";
            // reload fresh data
            $exam['title'] = $title;
            $exam['start_time'] = $start;
            $exam['end_time']   = $end;
            $exam['duration_minutes'] = $duration;
        } else {
            $msg = "Error: " . $up->error;
        }
    }
}
?>

<div class="container">
  <div class="card">
    <h3 style="color:var(--accent)">Edit Exam #<?= $exam_id ?></h3>
    <?php if ($msg): ?>
      <p><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="form-row">
        <input type="text" name="title" placeholder="Exam Title" required
               value="<?= htmlspecialchars($exam['title']) ?>">
      </div>
      <div class="form-row">
        <label>
          Start Time:
          <input type="datetime-local" name="start_time"
                 value="<?= $exam['start_time']
                     ? date('Y-m-d\TH:i', strtotime($exam['start_time']))
                     : '' ?>">
        </label>
      </div>
      <div class="form-row">
        <label>
          End Time:
          <input type="datetime-local" name="end_time"
                 value="<?= $exam['end_time']
                     ? date('Y-m-d\TH:i', strtotime($exam['end_time']))
                     : '' ?>">
        </label>
      </div>
      <div class="form-row">
        <input type="number" name="duration" min="0" placeholder="Duration (minutes)"
               value="<?= intval($exam['duration_minutes']) ?>">
      </div>
      <button class="primary" name="update_exam" type="submit">Save Changes</button>
      <a class="button-ghost" href="admin.php">← Back to List</a>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
