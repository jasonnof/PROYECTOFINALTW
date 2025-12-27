<?php
ini_set('display_errors', 1);

$ok = false;

try {
    // Mostrar dumps básicos (opcional)
    echo '<details><summary>$_FILES / $_REQUEST</summary><pre>';
    print_r($_FILES);
    print_r($_REQUEST);
    echo '</pre></details>';

    $f = $_FILES['foto_cliente'] ?? null;

    // Comprobación mínima
    if (empty($f) || !is_uploaded_file($f['tmp_name'])) {
        throw new RuntimeException("No se ha recibido ningún fichero");
    }

    // Destino (carpeta + nombre de archivo)
    $destinoDir = dirname(__FILE__) . '/media/fotos';
    if (!is_dir($destinoDir)) { mkdir($destinoDir, 0775, true); }

    $nombre = basename($f['name']);
    $destino = $destinoDir . '/' . $nombre;

    if (!move_uploaded_file($f['tmp_name'], $destino)) {
        throw new RuntimeException("No se pudo mover el fichero subido");
    }

    $ok = true;

} catch (Throwable $e) {
    $error_msg = $e->getMessage();
    $ok = false;
}
?>
