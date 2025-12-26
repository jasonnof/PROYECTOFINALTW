<?php


require_once __DIR__ . '/../lib/db.php';

$ok = false;

$id       = (int)($_POST['id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$role     = $_POST['role'] ?? 'gestor';
$pass     = $_POST['password'] ?? '';
$active   = isset($_POST['active']) ? 1 : 0;

if ($id <= 0) { setFlash('error', 'ID invalido.'); return; }

//Validaciones de cambios
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

try {
  //Comprobamos si existe por si acaso
  $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
  $stmt->execute([$id]);
  if (!$stmt->fetch()) {
    setFlash('error', 'Usuario no encontrado.');
    return;
  }

  // username único (excepto este id)
  $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id <> ? LIMIT 1");
  $stmt->execute([$username, $id]);
  if ($stmt->fetch()) {
    setFlash('error', 'Este username ya existe.');
    return;
  }

  if (trim($pass) !== '') {
    if (strlen($pass) < 6) { setFlash('error', 'Contraseña demasiado corta (mínimo 6).'); return; }
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
      UPDATE users
      SET username=?, email=?, role=?, active=?, password_hash=?
      WHERE id=?
    ");
    $stmt->execute([$username, $email !== '' ? $email : null, $role, $active, $hash, $id]);
  } else {
    $stmt = $pdo->prepare("
      UPDATE users
      SET username=?, email=?, role=?, active=?
      WHERE id=?
    ");
    $stmt->execute([$username, $email !== '' ? $email : null, $role, $active, $id]);
  }

  setFlash('ok', 'Trabajador actualizado.');
  $ok = true;

} catch (Throwable $e) {
  setFlash('error', 'Error actualizando trabajador.');
}
