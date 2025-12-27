<?php
require_once __DIR__ . '/../lib/db.php';

$_error_message = $_error_message ?? '';
$id = (int)($_GET['id'] ?? 0);
$activity = null;

function toDatetimeLocal(?string $dt): string {
  if (!$dt) return '';
  return str_replace(' ', 'T', substr($dt, 0, 16));
}

if ($id <= 0) {
  $_error_message = "ID de actividad inválido.";
} else {
  //Cojo los datos de la actividad de la base de datos
  $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ? LIMIT 1");
  $stmt->execute([$id]);
  $activity = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$activity) {
    $_error_message = "Activitat no encontrada.";
  }
}
?>

<section class="form-card">
  <h2>Modificar actividad</h2>

  <?php if (!$activity): ?>
    <p>No se puede mostrar el formulario.</p>
  <?php else: ?>

  <form class="form-ui" action="portal.php?action=modificar" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="id" value="<?= (int)$activity['id'] ?>">

    <div class="field">
      <label for="title">Título *</label>
      <input id="title" name="title" type="text" required maxlength="150"
             value="<?= htmlspecialchars($activity['title'] ?? '') ?>">
    </div>

    <div class="field">
      <label for="description">Descripción *</label>
      <textarea id="description" name="description" required rows="6"><?= htmlspecialchars($activity['description'] ?? '') ?></textarea>
    </div>

    <div class="field">
      <label for="location">Localización</label>
      <input id="location" name="location" type="text" maxlength="120"
             value="<?= htmlspecialchars($activity['location'] ?? '') ?> " required>
    </div>

    <div class="grid-2">
      <div class="field">
        <label for="start_at">Fecha inicio</label>
        <input id="start_at" name="start_at" type="datetime-local"
               value="<?= htmlspecialchars(toDatetimeLocal($activity['start_at'] ?? null)) ?>" required>
      </div>

      <div class="field">
        <label for="end_at">Fecha fin</label>
        <input id="end_at" name="end_at" type="datetime-local"
               value="<?= htmlspecialchars(toDatetimeLocal($activity['end_at'] ?? null)) ?>" required>
      </div>
    </div>

    <?php if (!empty($activity['image_path'])): ?>
      <p><strong>Imagen actual:</strong></p>
      <img class="preview-img" src="<?= htmlspecialchars($activity['image_path']) ?>" alt="Imatge actual">
    <?php endif; ?>

    <div class="field">
      <label for="upload">Cambiar foto (opcional)</label>
      <input type="file" accept="image/*" name="foto_cliente" id="upload">
    </div>
    <div class="field checkbox">
      <label>
        <input type="checkbox" name="published" value="1" <?= ((int)($activity['published'] ?? 1) === 1) ? 'checked' : '' ?>>
        Publicada
      </label>
    </div>

    <button class="btn btn-primary" type="submit">Guardar canvios</button>
  </form>

  <?php endif; ?>
</section>
