<?php
session_start();


if (!isset($_SESSION["activo"])) {
    $_SESSION["activo"] = 1;
    $_SESSION["visitado"] = [];
    $_SESSION["visita"] = 0;
} else {
    $_SESSION["visita"] = $_SESSION["visita"] + 1;

    // Evita warnings si no existe HTTP_REFERER
    if (!empty($_SERVER["HTTP_REFERER"])) {
        $_SESSION["visitado"][] = $_SERVER["HTTP_REFERER"];
    }
}


if (!isset($_SESSION["autentificado"])) {
    $_SESSION["autentificado"] = false;
}
if (!isset($_SESSION["rol"])) {
    $_SESSION["rol"] = "visitant";
}


$timeout = 20 * 60; // 20 min
$now = time();

//Si esta 20 min sin hacer nada, expiramos session
if (isset($_SESSION["last_action"]) && ($now - $_SESSION["last_action"]) > $timeout) {
    session_unset();
    session_destroy();
    session_start();

    // Reinicio básico tras expirar sesión
    $_SESSION["activo"] = 1;
    $_SESSION["visitado"] = [];
    $_SESSION["visita"] = 0;

    $_SESSION["autentificado"] = false;
    $_SESSION["rol"] = "visitant";
}

$_SESSION["last_action"] = $now;


//Helper que mostrara feedback, errores o mensajes de exito
function setFlash(string $type, string $msg): void {
  $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash(): ?array {
  if (empty($_SESSION['flash'])) return null;
  $f = $_SESSION['flash'];
  unset($_SESSION['flash']);
  return $f;
}

?>



