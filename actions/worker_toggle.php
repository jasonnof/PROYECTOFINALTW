<?php

require_once __DIR__ . '/../lib/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { setFlash('error', 'ID invalido'); return; }

// evita desactivarte a ti mismo
if ($id === (int)($_SESSION['user_id'] ?? 0)) {
  setFlash('error', 'No puedes desactivar tu propio usuario.');
  return;
}

try {
  $stmt = $pdo->prepare("UPDATE users SET active = 1 - active WHERE id = ?");
  $stmt->execute([$id]);
  setFlash('ok', 'Estado de usuario actualizado.');
} catch (Throwable $e) {
  setFlash('error', 'Error cambiando estado.');
}
