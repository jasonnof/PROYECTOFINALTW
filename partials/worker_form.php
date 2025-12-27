<?php
require_once __DIR__ . '/../lib/db.php';


//Cojo el id de la url y compruebo que sea valido
$id = (int)($_GET['id'] ?? 0);
$isEdit = $id > 0;


if ($isEdit) {
  $stmt = $pdo->prepare("SELECT id, username, email, role, active FROM users WHERE id = ? LIMIT 1");
  $stmt->execute([$id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    setFlash('error', 'Usuario no encontrado');
    header("Location: portal.php?action=workers_list");
    exit;
  }
} else {
  // si venimos de worker_new o de error de create, puede venir $user ya preparado
  $user = $user ?? ['id'=>0,'username'=>'','email'=>'','role'=>'gestor','active'=>1];
}
?>

<section class="form-card">
  <h2><?= $isEdit ? "Editar trabajador" : "Nuevo trabajador" ?></h2>

  <form class="form-ui"
        action="portal.php?action=<?= $isEdit ? "worker_update" : "worker_create" ?>"
        method="post" novalidate>

    <!-- Si es para editar envio el id hidden en el form para la siguiente peticion -->
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
    <?php endif; ?>

    <div class="field">
      <label for="username">Usuario *</label>
      <input id="username" name="username" type="text" required maxlength="30"
             value="<?= htmlspecialchars($user['username'] ?? '') ?>">
    </div>

    <div class="field">
      <label for="email">Email (opcional)</label>
      <input id="email" name="email" type="text" maxlength="120"
             value="<?= htmlspecialchars($user['email'] ?? '') ?>">
    </div>

    <div class="field">
      <label for="role">Rol *</label>
      <select id="role" name="role" required>
        <?php
          $roles = ['admin' => 'admin', 'gestor' => 'gestor', 'user' => 'user'];
          $cur = $user['role'] ?? 'gestor';
        ?>
        <?php foreach ($roles as $val => $label): ?>
          <option value="<?= $val ?>" <?= ($cur === $val) ? 'selected' : '' ?>>
            <?= htmlspecialchars($label) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="field">
      <label for="password"><?= $isEdit ? "Nueva contraseña (opcional)" : "Contraseña *" ?></label>
      <input id="password" name="password" type="password" <?= $isEdit ? '' : 'required' ?> minlength="6">
      <?php if ($isEdit): ?>
        <small style="opacity:.8;">Dejalo en blanco para no cambiarla.</small>
      <?php endif; ?>
    </div>

    <div class="field checkbox">
      <label>
        <input type="checkbox" name="active" value="1" <?= ((int)($user['active'] ?? 1) === 1) ? 'checked' : '' ?>>
        Activo
      </label>
    </div>

    <button class="btn btn-primary" type="submit">Guardar</button>
  </form>
</section>
