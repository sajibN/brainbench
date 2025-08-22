<?php
// footer.php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<footer style="background:var(--card); padding:16px 0; text-align:center; font-size:14px; color:var(--text);">
  <div class="container">
    <p>&copy; <?= date('Y') ?> BrainBench. All rights reserved.</p>
    <p>Developed by <strong>Sajib Kumar Paul</strong></p>
  </div>
</footer>

<script src="<?= $base ?>/script.js?v=<?= file_exists(__DIR__.'/script.js') ? filemtime(__DIR__.'/script.js') : time(); ?>"></script>
</body>
</html>
