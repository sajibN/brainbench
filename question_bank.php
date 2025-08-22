<?php
include 'db.php';
include 'header.php';

$msg = '';

// handle assign (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_to_exam'])) {
    $exam_id = intval($_POST['exam_id']);
    $q_ids = $_POST['q_ids'] ?? [];
    if ($exam_id && count($q_ids)) {
        foreach ($q_ids as $qid) {
            $qid = intval($qid);
            $chk = $conn->prepare("SELECT id FROM exam_questions WHERE exam_id=? AND question_id=?");
            $chk->bind_param('ii', $exam_id, $qid);
            $chk->execute();
            $res = $chk->get_result();
            if (!$res->num_rows) {
                $ins = $conn->prepare("INSERT INTO exam_questions (exam_id, question_id) VALUES (?, ?)");
                $ins->bind_param('ii', $exam_id, $qid);
                $ins->execute();
            }
        }
        $msg = "Selected questions assigned to the exam.";
    } else {
        $msg = "Please select at least one question.";
    }
    // reload to clear POST and show message (preserve filters)
    $redirect = "question_bank.php";
    $qs = [];
    if(isset($_POST['source_category'])) $qs['category'] = intval($_POST['source_category']);
    if(isset($_POST['source_q'])) $qs['q'] = trim($_POST['source_q']);
    if(isset($_POST['exam_id'])) $qs['exam_id'] = intval($_POST['exam_id']);
    if($qs) $redirect .= '?'.http_build_query($qs);
    header("Location: $redirect");
    exit;
}

// handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $del = $conn->prepare("DELETE FROM questions WHERE id=?");
    $del->bind_param('i',$id);
    $del->execute();
    header("Location: question_bank.php");
    exit;
}

// GET filters
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$search_q = isset($_GET['q']) ? trim($_GET['q']) : '';
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

// fetch categories for filter dropdown
$catsRes = $conn->query("SELECT id,name FROM categories ORDER BY name ASC");
$categories = [];
while($r = $catsRes->fetch_assoc()) $categories[] = $r;

// build dynamic query with prepared statements
$sql = "SELECT q.*, c.name as category_name FROM questions q LEFT JOIN categories c ON q.category_id=c.id";
$conds = [];
$params = [];
$types = '';

if($category){
  $conds[] = "q.category_id = ?";
  $types .= 'i';
  $params[] = $category;
}
if($search_q !== ''){
  $conds[] = "(q.question LIKE ? OR q.option_a LIKE ? OR q.option_b LIKE ? OR q.option_c LIKE ? OR q.option_d LIKE ?)";
  $like = "%{$search_q}%";
  $types .= 'sssss';
  $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like;
}
if($conds) $sql .= " WHERE " . implode(' AND ', $conds);
$sql .= " ORDER BY q.id DESC";

// prepare & bind
$stmt = $conn->prepare($sql);
if($params){
  // bind dynamically
  $refs = [];
  $refs[] = &$types;
  foreach($params as $k => $v) $refs[] = &$params[$k];
  call_user_func_array([$stmt, 'bind_param'], $refs);
}
$stmt->execute();
$res = $stmt->get_result();
?>
<div class="container">
  <div class="card">
    <h3 style="color:var(--accent)">Question Bank</h3>
    <?php if ($msg) echo "<p>{$msg}</p>"; ?>

    <!-- filter form -->
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;align-items:center">
      <?php if($exam_id): ?><input type="hidden" name="exam_id" value="<?=$exam_id?>"><?php endif; ?>
      <select name="category">
        <option value="0">-- All Categories --</option>
        <?php foreach($categories as $cat): ?>
          <option value="<?=$cat['id']?>" <?=($category == $cat['id'])?'selected':''?>><?=htmlspecialchars($cat['name'])?></option>
        <?php endforeach; ?>
      </select>

      <input type="text" name="q" placeholder="Search question / options" value="<?=htmlspecialchars($search_q)?>">

      <button class="primary" type="submit">Filter</button>
      <a class="button-ghost" href="question_bank.php<?= $exam_id ? '?exam_id='.$exam_id : '' ?>">Reset</a>
    </form>

    <?php if ($exam_id): 
      // fetch exam title for display
      $e = $conn->prepare("SELECT title FROM exams WHERE id=?");
      $e->bind_param('i',$exam_id); $e->execute(); $er=$e->get_result(); $exam_title = $er->num_rows ? $er->fetch_assoc()['title'] : 'Selected Exam';
    ?>
      <div style="margin-bottom:8px;padding:10px;background:#f3f9ff;border-radius:8px">
        <strong>Assigning to:</strong> <?=htmlspecialchars($exam_title)?> — select questions below and click **Assign Selected**
      </div>
      <form method="POST">
        <input type="hidden" name="exam_id" value="<?=$exam_id?>">
        <input type="hidden" name="assign_to_exam" value="1">
        <!-- Preserve current filters for redirect feedback -->
        <input type="hidden" name="source_category" value="<?=$category?>">
        <input type="hidden" name="source_q" value="<?=htmlspecialchars($search_q)?>">
    <?php endif; ?>

    <div class="question-list">
      <?php while($r = $res->fetch_assoc()): ?>
        <label class="q-item">
          <?php if ($exam_id): ?><input type="checkbox" name="q_ids[]" value="<?=$r['id']?>"><?php endif; ?>
          <div style="flex:1">
            <strong>#<?=$r['id']?></strong> <?=htmlspecialchars($r['question'])?>
            <div style="font-size:13px;color:#666;">
              A) <?=htmlspecialchars($r['option_a'])?> &nbsp; B) <?=htmlspecialchars($r['option_b'])?>
              <?php if($r['category_name']): ?> <span style="margin-left:8px;color:#2b6ea3">[<?=htmlspecialchars($r['category_name'])?>]</span><?php endif; ?>
            </div>
          </div>
          <div>
            <a class="button-ghost" href="question_bank.php?delete=<?=$r['id']?><?= $exam_id ? '&exam_id='.$exam_id : '' ?>" onclick="return confirm('Delete question?')">Delete</a>
          </div>
        </label>
      <?php endwhile; ?>
    </div>

    <?php if ($exam_id): ?>
      <div style="margin-top:12px">
        <button class="primary" type="submit">Assign Selected to "<?=htmlspecialchars($exam_title)?>"</button>
        <a class="button-ghost" href="admin.php">Back to Admin</a>
      </div>
      </form>
    <?php endif; ?>

  </div>
</div>

<?php include 'footer.php'; ?>
