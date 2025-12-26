<?php

ini_set('display_errors',1);

$ok = false;

try {
    // Validamos entradas
    $login  = trim($_POST['login']  ?? '');
    $passwd = trim($_POST['passwd'] ?? '');

    if ($login === '' || $passwd === '') {
        throw new RuntimeException("Login y constraseña son obligatorios");
    }

    // Conecto con la base de datos
    require_once __DIR__ . '/db.php';

    // Consulto si ya existe el login
    $stmt = $pdo->prepare("SELECT 1 FROM usuarios WHERE login = ?");
    $stmt->execute(array($login));
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si devuelve algo, ese usuario ya está registrado
    if ($row) {
        throw new RuntimeException("El login ya existe");
    }

    $insert = $pdo->prepare("INSERT INTO usuarios (login, passwd, rol) VALUES (?, ?, 'user')");
    $ok = $insert->execute(array($login, $passwd));

    if (!$ok) {
        throw new RuntimeException("No se pudo registrar al usuario");
    }

    $_ok_message = "Usuario registrado correctamente";
} catch (Throwable $e) {
    $_error_message = $e->getMessage();
    $ok = false;
}

?>
