<?php
$current = isset($action) ? $action : 'home';
function isActive($name, $current){ return $name === $current ? ' class="is-active"' : ''; }
$rol = $_SESSION['rol'] ?? 'visitant';
?>
<nav class="main-nav" aria-label="Navegació principal">
<div class="container">
<ul class="menu">
<li><a<?= isActive('home',$current) ?> href="?action=home">Home</a></li>
<li><a<?= isActive('form_register',$current) ?> href="?action=form_register">Registro</a></li>
<li><a<?= isActive('lgpd',$current) ?> href="?action=lgpd">Política de Privacitat</a></li>
<li><a<?= isActive('qui_som',$current) ?> href="?action=qui_som">Qui som</a></li>
<li><a<?= isActive('galeria',$current) ?> href="?action=galeria">Galeria</a></li>
<li><a<?= isActive('tablas',$current) ?> href="?action=tablas">Activitats</a></li>
<?php if ($rol === 'gestor'): ?>
<li><a<?= isActive('form_activitat',$current) ?> href="?action=form_activitat">Afegir activitat</a></li>
<li><a<?= isActive('list',$current) ?> href="?action=list">Llistat Gestor</a></li>
<?php endif; ?>
<li><a<?= isActive('form_login',$current) ?> href="?action=form_login">Login</a></li>
</ul>
</div>
</nav>
