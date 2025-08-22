<?php
include 'db.php';
include 'header.php';
?>
<div class="container">
  <div class="card join-wrap">
    <div class="join-card card">
      <h2 style="color:var(--accent)">Join an Exam</h2>
      <form action="join_exam.php" method="POST" class="join-form">
        <div class="form-row"><input type="text" name="username" placeholder="Your name" required></div>
        <div class="form-row">
          <select name="exam_id" required>
            <option value="">-- Select Exam --</option>
            <?php
              $q = $conn->prepare("SELECT id,title,start_time,end_time FROM exams ORDER BY start_time DESC");
              $q->execute(); $res = $q->get_result();
              while($r = $res->fetch_assoc()){
                $label = htmlspecialchars($r['title']) . " (" . date('d M H:i', strtotime($r['start_time'])) . ")";
                echo "<option value='{$r['id']}'>{$label}</option>";
              }
            ?>
          </select>
        </div>
        <div style="display:flex;gap:10px;">
          <button class="primary" type="submit">Join Exam</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
