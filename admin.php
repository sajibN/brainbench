<?php
// admin.php
include 'db.php';
include 'header.php';   // header.php includes session_start()

$msg = '';

// — Handle Create —
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_exam'])) {
    $title    = trim($_POST['title'] ?? '');
    $duration = intval($_POST['duration'] ?? 0);

    if ($title !== '') {
        $ins = $conn->prepare(
            "INSERT INTO exams (title, start_time, end_time, duration_minutes)
             VALUES (?, NULL, NULL, ?)"
        );
        $ins->bind_param('si', $title, $duration);
        if ($ins->execute()) {
            $msg = "Exam “" . htmlspecialchars($title) . "” created.";
        } else {
            $msg = "Error creating exam: " . $ins->error;
        }
    } else {
        $msg = "Please provide an exam title.";
    }
}

// — Handle Delete —
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $conn->begin_transaction();
    // Delete participants
    $del_part = $conn->prepare("DELETE FROM exam_participants WHERE exam_id = ?");
    $del_part->bind_param('i', $del_id);
    $del_part->execute();
    // Delete question assignments
    $del_eq = $conn->prepare("DELETE FROM exam_questions WHERE exam_id = ?");
    $del_eq->bind_param('i', $del_id);
    $del_eq->execute();
    // Delete exam
    $del_exam = $conn->prepare("DELETE FROM exams WHERE id = ?");
    $del_exam->bind_param('i', $del_id);
    if ($del_exam->execute()) {
        $conn->commit();
        $msg = "Exam #{$del_id} deleted.";
    } else {
        $conn->rollback();
        $msg = "Error deleting exam: " . $del_exam->error;
    }
}

// — Fetch All Exams —
$res = $conn->query(
    "SELECT id, title,
            DATE_FORMAT(start_time, '%Y-%m-%d %H:%i') AS start_time,
            DATE_FORMAT(end_time,   '%Y-%m-%d %H:%i') AS end_time,
            duration_minutes
       FROM exams
      ORDER BY id DESC"
);
?>

<style>
/* Responsive adjustments for admin page */
.form-row { display: flex; flex-wrap: wrap; gap: 8px; }
@media (max-width: 600px) {
  .form-row { flex-direction: column; }
  .table-responsive table { font-size: 14px; }
}
.table-responsive { width: 100%; overflow-x: auto; margin-top: 12px; }
.table-responsive table { width: 100%; border-collapse: collapse; }
.table-responsive th, .table-responsive td { padding: 8px; border-bottom: 1px solid #ddd; text-align: left; }
.table-responsive th { background: var(--bg); }
.table-actions a { margin-right: 8px; margin-bottom: 4px; display: inline-block; }
</style>

<div class="container">
  <div class="card" style="margin-bottom:24px">
    <h3 style="color:var(--accent)">Create New Exam</h3>
    <?php if ($msg): ?>
      <p><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="form-row">
        <input type="text" name="title" placeholder="Exam Title" required style="flex:1; min-width:120px;">
        <input type="number" name="duration" placeholder="Duration (minutes)" min="0" value="0" style="width:120px;">
      </div>
      <button class="primary" name="create_exam" type="submit">Create Exam</button>
    </form>
  </div>

  <div class="card">
    <h3 style="color:var(--accent)">Existing Exams</h3>
    <?php if ($res->num_rows === 0): ?>
      <p>No exams created yet.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>ID</th><th>Title</th><th>Start Time</th>
              <th>End Time</th><th>Duration</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($exam = $res->fetch_assoc()): ?>
            <tr>
              <td><?= $exam['id'] ?></td>
              <td><?= htmlspecialchars($exam['title']) ?></td>
              <td><?= $exam['start_time'] ?: '—' ?></td>
              <td><?= $exam['end_time']   ?: '—' ?></td>
              <td><?= intval($exam['duration_minutes']) ?> min</td>
              <td class="table-actions">
                <a class="button-ghost" href="question_bank.php?exam_id=<?= $exam['id'] ?>">Assign Questions</a>
                <a class="button-ghost" href="update_exam.php?exam_id=<?= $exam['id'] ?>">Edit</a>
                <a class="button-ghost" href="admin.php?delete=<?= $exam['id'] ?>" onclick="return confirm('Delete exam #<?= $exam['id'] ?>?')">Delete</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
