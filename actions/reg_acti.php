<?php
require_once __DIR__ . "/../lib/db.php";

$ok = false;
$_error_message = '';

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$location = trim($_POST['location'] ?? '');
$start_at = trim($_POST['start_at'] ?? '');
$end_at = trim($_POST['end_at'] ?? '');
$published = isset($_POST['published']) ? 1 : 0;

if ($title === '' || $description === '') {
    $_error_message = "Titulo, descripcion, localizacion y fechas son obligatorios.";
    return;
}

$start_at_db = ($start_at !== '') ? str_replace('T', ' ', $start_at) . ':00' : null;
$end_at_db   = ($end_at !== '') ? str_replace('T', ' ', $end_at) . ':00' : null;

if($end_at < $start_at){
    $_error_message = "La fecha de fin no puede ser anterior a la fecha de inicio.";
    $ok = false;
    return;
}

$location_db = ($location !== '') ? $location : null;

/* ---- Subida de imagen (opcional) ---- */
$image_path_db = null;

if (!empty($_FILES['foto_cliente']) && ($_FILES['foto_cliente']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {

    $img = $_FILES['foto_cliente'];

    if ($img['error'] !== UPLOAD_ERR_OK) {
        $_error_message = "Error subiendo actividad.";
        return;
    }

    // Validar que sea imagen
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $img['tmp_name']);
    finfo_close($finfo);

    $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
    if (!in_array($mime, $allowed, true)) {
        $_error_message = "Formato de imagen no permitido (jpg, png, webp, gif).";
        return;
    }

    // Carpeta destino (FS) y URL base (web)
    $dirDestino = __DIR__ . "/../media/fotos";
    $urlBase    = "/media/fotos";

    if (!is_dir($dirDestino)) {
        mkdir($dirDestino, 0775, true);
    }

    // Nombre final: name_foto si viene, si no, usar el original
    $nameFoto = trim($_POST['name_foto'] ?? '');

    // Sacar extensión desde el nombre original (o mime)
    $original = $img['name'];
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if ($ext === '') {
        // fallback por mime
        $map = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
        $ext = $map[$mime] ?? 'jpg';
    }

    // “Sanitizar” nombre (solo letras, números, guion y underscore)
    if ($nameFoto !== '') {
        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nameFoto);
    } else {
        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($original, PATHINFO_FILENAME));
    }

    // Evitar colisiones: añadimos timestamp
    $finalName = $base . "_" . time() . "." . $ext;

    $destinoFS   = $dirDestino . "/" . $finalName;
    $urlRelativa = $urlBase . "/" . $finalName;

    if (!move_uploaded_file($img['tmp_name'], $destinoFS)) {
        $_error_message = "No se ha podido guardar la imagen en el servidor.";
        return;
    }

    $image_path_db = $urlRelativa;
}

/* ---- Insert en BD ---- */
try {
    $stmt = $pdo->prepare("
        INSERT INTO activities (title, description, location, start_at, end_at, image_path, created_by, published)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $description,
        $location_db,
        $start_at_db,
        $end_at_db,
        $image_path_db,
        (int)($_SESSION['user_id'] ?? 0),
        $published
    ]);

    $ok = true;
    


} catch (Throwable $e) {
    $_error_message = "Error guardando la actividad.";
}
