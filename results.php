<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])) header('Location:index.php');
$score = $_SESSION['last_score'] ?? null;
include 'header.php';
?>
<div class="container">
  <div class="card">
    <h2 style="color:var(--accent)">Result</h2>
    <?php if($score === null) { echo "<p>No recent exam found.</p>"; } else { ?>
      <p>Your score: <strong><?=$score?>%</strong></p>
      <p><a href="index.php" class="button-ghost">Back to Home</a></p>
    <?php } ?>
  </div>
</div>
<?php include 'footer.php'; ?>
