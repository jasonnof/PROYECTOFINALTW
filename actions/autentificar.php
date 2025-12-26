<?php


require_once __DIR__ . '/../lib/db.php'; // aquí está $pdo

$ok = false;
$_error_message = '';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
  $_error_message = "Rellena usuario y contraseña.";
  return;
}

try {
  $stmt = $pdo->prepare("SELECT id, username, password_hash, role, active FROM users WHERE username = ? LIMIT 1");
  $stmt->execute([$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    $_error_message = "Usuario o contraseña incorrectos.";
    return;
  }

  if ((int)$user['active'] !== 1) {
    $_error_message = "Cuenta desactivada.";
    return;
  }

  if (!password_verify($password, $user['password_hash'])) {
    $_error_message = "Usuario o contraseña incorrectos.";
    return;
  }

  // Login OK
  session_regenerate_id(true);

  $_SESSION['autentificado'] = true;
  $_SESSION['user_id'] = (int)$user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['rol'] = $user['role']; // admin | gestor | user

  $ok = true;

} catch (Throwable $e) {
  $_error_message = "Error de servidor en login.";
}
