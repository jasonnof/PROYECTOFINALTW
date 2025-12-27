<?php
require_once __DIR__ . '/../lib/db.php';

$ok = false;
$_error_message = '';

$id = (int)($_POST['id'] ?? 0);

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$location = trim($_POST['location'] ?? '');
$start_at = trim($_POST['start_at'] ?? '');
$end_at = trim($_POST['end_at'] ?? '');
$published = isset($_POST['published']) ? 1 : 0;

if ($id <= 0) {
  $_error_message = "ID invalido.";
  return;
}

if ($title === '' || $description === '' || $location === '' || $start_at === '' || $end_at === '') {
  $_error_message = "Titulo, descripcion, localizacion y fechas son obligatorios.";
  return;
}

// Cargar actividad actual (para conservar image_path si no subes nueva)
$stmt = $pdo->prepare("SELECT image_path FROM activities WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$current) {
  $_error_message = "Actividad no encontrada.";
  return;
}

$start_at_db = ($start_at !== '') ? str_replace('T', ' ', $start_at) . ':00' : null;
$end_at_db   = ($end_at !== '') ? str_replace('T', ' ', $end_at) . ':00' : null;

$location_db = ($location !== '') ? $location : null;

// Por defecto mantenemos la imagen actual
$image_path_db = $current['image_path'] ?? null;

/* ---- Foto nueva (opcional) y nombre de foto nueva() ---- */
if (!empty($_FILES['foto_cliente']) && ($_FILES['foto_cliente']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {

  $img = $_FILES['foto_cliente'];

  if ($img['error'] !== UPLOAD_ERR_OK) {
    $_error_message = "Error subiendo la imagen.";
    return;
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime  = finfo_file($finfo, $img['tmp_name']);
  finfo_close($finfo);

  $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
  if (!in_array($mime, $allowed, true)) {
    $_error_message = "Formato de imagen no permitido (jpg, png, webp, gif).";
    return;
  }

  $dirDestino = __DIR__ . "/../media/fotos";
  $urlBase    = "/media/fotos";

  if (!is_dir($dirDestino)) { mkdir($dirDestino, 0775, true); }

  $original = $img['name'];
  $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
  if ($ext === '') {
    $map = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
    $ext = $map[$mime] ?? 'jpg';
  }

  // nombre seguro basado en id
  $finalName = "act_" . $id . "_" . time() . "." . $ext;

  $destinoFS   = $dirDestino . "/" . $finalName;
  $urlRelativa = $urlBase . "/" . $finalName;

  if (!move_uploaded_file($img['tmp_name'], $destinoFS)) {
    $_error_message = "No se ha podido guardar la imagen.";
    return;
  }

  $image_path_db = $urlRelativa;
}

/* ---- UPDATE ---- */
try {
  $upd = $pdo->prepare("
    UPDATE activities
    SET title = ?, description = ?, location = ?, start_at = ?, end_at = ?, image_path = ?, published = ?
    WHERE id = ?
  ");

  $upd->execute([
    $title,
    $description,
    $location_db,
    $start_at_db,
    $end_at_db,
    $image_path_db,
    $published,
    $id
  ]);

  $ok = true;

} catch (Throwable $e) {
  $_error_message = "Error guardando cambios.";
}
