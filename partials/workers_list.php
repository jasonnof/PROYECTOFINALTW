<?php 

require_once __DIR__ . "/../lib/db.php";

//Obtenemos todos los usuarios por roles


$stmt = $pdo->prepare("
    SELECT id, username, email, role, active, created_at
    FROM users
    ORDER BY role ASC, username ASC");

$stmt-> execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<section class="form-card">
  <h2>Trabajadores</h2>

  <p style="text-align:center;margin:10px 0 18px;">
    <a class="btn btn-primary" href="portal.php?action=worker_new">+ Nuevo trabajador</a>
  </p>

  <div style="overflow-x:auto;">
    <table class="activities-table">
      <thead>
        <tr>
          <th>Usuario</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Creado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
          <td><?= htmlspecialchars($u['role']) ?></td>

          <td>
            <?php if ((int)$u['active'] === 1): ?>
              <span class="status status-on">Activo</span>
            <?php else: ?>
              <span class="status status-off">Inactivo</span>
            <?php endif; ?>
          </td>

          <td><?= htmlspecialchars($u['created_at'] ?? '') ?></td>

          <td>
            <a class="btn btn-edit" href="portal.php?action=worker_edit&id=<?= (int)$u['id'] ?>">Editar</a>

            <?php if ((int)$u['id'] !== (int)($_SESSION['user_id'] ?? 0)): ?>
              <?php if ((int)$u['active'] === 1): ?>
                <a class="btn btn-danger"
                   href="portal.php?action=worker_toggle&id=<?= (int)$u['id'] ?>"
                   onclick="return confirm('Desactivar este usuario?');">
                  Desactivar
                </a>
              <?php else: ?>
                <a class="btn btn-success"
                   href="portal.php?action=worker_toggle&id=<?= (int)$u['id'] ?>"
                   onclick="return confirm('Activar este usuario?');">
                  Activar
                </a>
              <?php endif; ?>
            <?php else: ?>
              <span style="opacity:.7;">(tú)</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

