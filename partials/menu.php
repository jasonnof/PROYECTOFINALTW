<?php
$current = $action ?? 'home';
function isActive(string $name, string $current): string {
  return $name === $current ? ' is-active' : '';
}

$rol = $_SESSION['rol'] ?? 'visitant';
$aut = $_SESSION['autentificado'] ?? false;
$username = $_SESSION['username'] ?? '';
?>

<nav class="main-nav" aria-label="Navegación principal">
  <div class="nav-inner">

    <!-- Links principales -->
    <ul class="menu menu-left">
      <li><a class="menu-link<?= isActive('home',$current) ?>" href="?action=home">Home</a></li>
      <li><a class="menu-link<?= isActive('tablas',$current) ?>" href="?action=tablas">Actividades</a></li>
      <li><a class="menu-link<?= isActive('galeria',$current) ?>" href="?action=galeria">Galería</a></li>
      <li><a class="menu-link<?= isActive('game',$current) ?>" href="?action=game">Juego</a></li>
      <li><a class="menu-link<?= isActive('qui_som',$current) ?>" href="?action=qui_som">Quiénes somos</a></li>
      <li><a class="menu-link<?= isActive('lgpd',$current) ?>" href="?action=lgpd">Privacidad</a></li>
      <li><a class="menu-link<?= isActive('cookies',$current) ?>" href="?action=cookies">Cookies</a></li>

      <?php if ($rol === 'gestor'): ?>
        <li><a class="menu-link<?= isActive('form_activitat',$current) ?>" href="?action=form_activitat">Nueva actividad</a></li>
      <?php endif; ?>

      <?php if ($rol === 'admin'): ?>
        <li><a class="menu-link<?= isActive('workers_list',$current) ?>" href="?action=workers_list">Trabajadores</a></li>
      <?php endif; ?>
    </ul>

    <!-- Zona usuario -->
    <div class="menu-right">
      <?php if ($aut): ?>
        <span class="user-pill" aria-label="Usuario conectado">
          <?= htmlspecialchars($username) ?> · <?= htmlspecialchars($rol) ?>
        </span>
        <a class="menu-link btn-link<?= isActive('salida',$current) ?>" href="?action=salida">Salir</a>
      <?php else: ?>
        <a class="menu-link btn-link<?= isActive('form_login',$current) ?>" href="?action=form_login">Login</a>
      <?php endif; ?>
    </div>

  </div>
</nav>
