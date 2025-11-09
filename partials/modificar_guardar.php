<?php
ini_set('display_errors', 1);
$ok = false;

try {
    // -------- 1) Entradas --------
    $original = trim($_POST['original_nombre'] ?? '');
    $codigo   = trim($_POST['codigo']          ?? '');
    $desc     = trim($_POST['descripcion']     ?? '');
    $max      = $_POST['alumnos_max'] ?? null;
    $vacs     = $_POST['vacantes']    ?? null;
    $precio   = $_POST['precio']      ?? null;

    if ($original === '' || $codigo === '' || $desc === '' ||
        $max === null || $vacs === null || $precio === null) {
        throw new RuntimeException("Faltan datos obligatorios.");
    }

    // Normalización / Reglas
    $max    = (int)$max;
    $vacs   = (int)$vacs;
    $precio = (float)$precio;

    if ($max < 1 || $vacs < 0 || $vacs > $max || $precio < 0) {
        throw new RuntimeException("Valores fuera de rango.");
    }

    // -------- 2) Cargar JSON --------
    $jsonPath = dirname(__FILE__) . "/../recursos/activitats.json";
    $actividades = [];
    if (is_file($jsonPath)) {
        $raw = file_get_contents($jsonPath);
        $actividades = json_decode($raw, true);
        if (!is_array($actividades)) { $actividades = []; }
    }

    if (!isset($actividades[$original])) {
        throw new RuntimeException("La actividad original no existe.");
    }

    // Evitar duplicado de CÓDIGO (excluyendo a sí misma)
    foreach ($actividades as $k => $actividad) {
        if ($k === $original) continue;
        if (!empty($actividad['codigo']) && $actividad['codigo'] === $codigo) {
            throw new RuntimeException("El código de actividad ya existe.");
        }
    }

    // -------- 3) Construir nuevo registro --------
    $nuevo = [
        "codigo"       => $codigo,
        "nombre"       => $original,         // No se cambia el ID (clave del diccionario)
        "descripcion"  => $desc,
        "alumnos_max"  => $max,
        "vacantes"     => $vacs,
        "precio"       => (float)number_format($precio, 2, '.', ''),
    ];

    // Imagen (opcional): si llega una nueva, la guardamos; si no, conservamos la anterior
    if (!empty($_FILES['foto_actividad']) && $_FILES['foto_actividad']['error'] === UPLOAD_ERR_OK) {
        $imagen    = $_FILES['foto_actividad'];
        $dirDestino = dirname(__FILE__) . "/../media/fotos";
        $urlBase    = "/media/fotos";

        if (!is_dir($dirDestino)) { mkdir($dirDestino, 0775, true); }

        $fichero     = $imagen['name'];                 // sencillo (tu formato)
        $destinoFS   = $dirDestino . "/" . $fichero;
        $urlRelativa = $urlBase   . "/" . $fichero;

        if (!move_uploaded_file($imagen['tmp_name'], $destinoFS)) {
            throw new RuntimeException("No se pudo guardar la imagen en el servidor.");
        }

        $nuevo['img_URL']   = $urlRelativa;
        $nuevo['name_foto'] = (string)$fichero;
    } else {
        // Mantener si ya existían
        if (!empty($actividades[$original]['img_URL'])) {
            $nuevo['img_URL'] = $actividades[$original]['img_URL'];
        }
        if (!empty($actividades[$original]['name_foto'])) {
            $nuevo['name_foto'] = $actividades[$original]['name_foto'];
        }
    }

    // -------- 4) Aplicar cambio en memoria --------
    $actividades[$original] = $nuevo;

    // -------- 5) Comprobaciones de ruta/permisos --------
    $dir = dirname($jsonPath);
    if (!is_dir($dir)) {
        throw new RuntimeException("No existe el directorio de datos: " . $dir);
    }
    if (!is_writable($dir)) {
        throw new RuntimeException("Sin permiso de escritura en el directorio: " . $dir);
    }
    if (file_exists($jsonPath) && !is_writable($jsonPath)) {
        throw new RuntimeException("El fichero no es escribible: " . $jsonPath);
    }

    // -------- 6) Guardar JSON --------
    $json = json_encode($actividades, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        throw new RuntimeException("Error al codificar JSON: " . json_last_error_msg());
    }

    $bytes = @file_put_contents($jsonPath, $json, LOCK_EX);
    if ($bytes === false) {
        $last = error_get_last();
        $msg  = $last['message'] ?? 'desconocido';
        throw new RuntimeException("No se pudo escribir en '$jsonPath'. Último error: $msg");
    }

    $ok = true;

} catch (Throwable $e) {
    $_error_message = $e->getMessage();
    $ok = false;
}
