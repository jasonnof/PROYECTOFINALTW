<?php
ini_set('display_errors', 1);
$ok = false;

try {
    //COjo el nombre de la actividad de la querystring
  $id = urldecode($_GET['acti'] ?? '');
  if ($id === '') throw new RuntimeException("Falta identificador.");

  $jsonPath = dirname(__FILE__) . "/../recursos/activitats.json";
  $actividades = [];
  if (is_file($jsonPath)) {
    $raw = file_get_contents($jsonPath);
    $actividades = json_decode($raw, true);
    if (!is_array($actividades)) $actividades = [];
  }

  //Compruebo primero si esa actividad existe
  if (!isset($actividades[$id])) {
    throw new RuntimeException("La actividad no existe.");
  }

  unset($actividades[$id]);

  $json = json_encode($actividades, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  if ($json === false) throw new RuntimeException("Error al codificar JSON.");
  if (file_put_contents($jsonPath, $json, LOCK_EX) === false) {
    throw new RuntimeException("No se pudo escribir el fichero de datos.");
  }

  $ok = true;

} catch (Throwable $e) {
  $_error_message = $e->getMessage();
  $ok = false;
}
