<?php

//Llamo base de datos
require_once __DIR__ . '/../lib/db.php';

$ok = false;

//Cojo datos del form
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$role     = $_POST['role'] ?? 'gestor';
$pass     = $_POST['password'] ?? '';
$active   = isset($_POST['active']) ? 1 : 0;

$user = ['id'=>0,'username'=>$username,'email'=>$email,'role'=>$role,'active'=>$active];

//reglas de validacion
if ($username === '' || strlen($username) < 3) {
  setFlash('error', 'Username demasiado corto (mínim 3).');
  return;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  setFlash('error', 'Email invalido.');
  return;
}

$allowed = ['admin','gestor','user'];
if (!in_array($role, $allowed, true)) {
  setFlash('error', 'Rol invalido.');
  return;
}

if (strlen($pass) < 6) {
  setFlash('error', 'Contrasenya demasiado corta (mínim 6).');
  return;
}

try {
  // username debe ser único
  $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
  $stmt->execute([$username]);
  if ($stmt->fetch()) {
    setFlash('error', 'Este username ya existe.');
    return;
  }
  //hasheo contraseña y guardo en base de datos
  $hash = password_hash($pass, PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("
    INSERT INTO users (username, email, password_hash, role, active)
    VALUES (?, ?, ?, ?, ?)
  ");
  $stmt->execute([$username, $email !== '' ? $email : null, $hash, $role, $active]);

  setFlash('ok', 'Trabajador creado correctamente.');
  $ok = true;

} catch (Throwable $e) {
  setFlash('error', 'Error creando trabajador.');
}
