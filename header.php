<?php
// header.php
if (session_status() === PHP_SESSION_NONE) session_start();
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>BrainBench</title>
  <link rel="stylesheet" href="<?php echo $base; ?>/style.css?v=<?php echo file_exists(__DIR__.'/style.css') ? filemtime(__DIR__.'/style.css') : time(); ?>">
</head>
<body>
<header>
  <nav class="topnav">
    <div class="brand">BrainBench</div>

    <!-- nav links -->
    <ul class="nav-links" role="menu">
      <li><a href="<?php echo $base; ?>/index.php">Join Exam</a></li>
      <li><a href="<?php echo $base; ?>/admin.php">Set Exam</a></li>
      <li><a href="<?php echo $base; ?>/add_question.php">Add Question</a></li>
      <li><a href="<?php echo $base; ?>/question_bank.php">Question Bank</a></li>
    </ul>

    <!-- right side controls -->
    <div class="nav-right">
      <button id="modeToggle" aria-label="Toggle dark mode">🌓</button>
      <button class="burger" id="burgerBtn" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>
</header>
