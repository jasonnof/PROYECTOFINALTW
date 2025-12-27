<?php
$flash = getFlash();
if (!$flash) return;

$type = $flash['type'] ?? 'info';
$msg  = $flash['msg'] ?? '';
?>

<div class="flash flash-<?= htmlspecialchars($type) ?>"
     role="<?= ($type === 'error') ? 'alert' : 'status' ?>"
     aria-live="polite">
  <?= htmlspecialchars($msg) ?>
</div>

