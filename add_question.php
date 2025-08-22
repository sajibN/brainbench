<?php
include 'db.php';
include 'header.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $q = trim($_POST['question'] ?? '');
  $a = trim($_POST['a'] ?? '');
  $b = trim($_POST['b'] ?? '');
  $c = trim($_POST['c'] ?? '');
  $d = trim($_POST['d'] ?? '');
  $correct = $_POST['correct'] ?? '';
  $category_id = intval($_POST['category_id'] ?? 0);

  if($q && $a && $b && $c && $d && in_array($correct,['a','b','c','d'])){
    $stmt = $conn->prepare("INSERT INTO questions (question,option_a,option_b,option_c,option_d,correct_option,category_id) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssssi',$q,$a,$b,$c,$d,$correct,$category_id);
    if($stmt->execute()) $msg = "Question added.";
    else $msg = "Error: " . $stmt->error;
  } else {
    $msg = "Please fill all fields.";
  }
}

// fetch categories for dropdown
$catsRes = $conn->query("SELECT id,name FROM categories ORDER BY name ASC");
$categories = [];
while($r = $catsRes->fetch_assoc()) $categories[] = $r;
?>
<div class="container">
  <div class="card">
    <h3 style="color:var(--accent)">Add Question to Bank</h3>
    <?php if($msg) echo "<p>".$msg."</p>"; ?>
    <form method="POST">
      <div><textarea name="question" placeholder="Question text" required style="width:100%;min-height:80px;padding:10px;border-radius:8px"></textarea></div>

      <div class="form-row"><input type="text" name="a" placeholder="Option A" required></div>
      <div class="form-row"><input type="text" name="b" placeholder="Option B" required></div>
      <div class="form-row"><input type="text" name="c" placeholder="Option C" required></div>
      <div class="form-row"><input type="text" name="d" placeholder="Option D" required></div>

      <div class="form-row">
        <select name="correct" required>
          <option value="">-- Correct option --</option>
          <option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option>
        </select>

        <select name="category_id" required>
          <option value="">-- Select Category --</option>
          <?php foreach($categories as $cat): ?>
            <option value="<?=$cat['id']?>"><?=htmlspecialchars($cat['name'])?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div><button class="primary" type="submit">Add Question</button></div>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>
