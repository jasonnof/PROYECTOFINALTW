<?php
ini_set('display_errors', 1); //mode depurador de programes
if (!empty($error_msg)) : ?>
  <div class="error">
    <strong>Error:</strong> <?= $error_msg ?>
  </div>
<?php endif; ?>
