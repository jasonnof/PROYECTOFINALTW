<!doctype html>
<html lang="es">

<!-- En el head solo pongo metadatos o informacion, nada visual-->
<head>
  <meta charset="utf-8">
  <title>Oficina de Turismo - Portal</title>
  <meta name="author" content="Alumno426259"> <!-- pongo el nombre del autor de la pagina -->
  <!-- muy importante para hacerla responsiva, adapto el ancho a la pantalla del dispositivo y zoom inicial=1 -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- COnecto con la hoja de estilos del portal -->
  <link rel="stylesheet" href="./css/estilo.css" type="text/css">

  <!--Cargo el js de tablas,galeria y aside -->
  <script src="public/js/async_portal.js" defer></script>
  <!--Cargo el js del juego-->
  <script src="public/js/game.js" defer></script>

</head>
<body>

<header class="site-header">
  <div class="header-inner">
    <div class="brand">
      <h1 class="brand-title">Portal de Turismo Internacional</h1>
      <p class="brand-subtitle">Actividades, experiencias y cultura en destinos de todo el mundo</p>

      <?php if (!empty($_SESSION['autentificado'])): ?>
        <div class="welcome welcome--under">
          <span class="welcome-label">Bienvenido,</span>
          <strong class="welcome-user"><?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?></strong>
          <span class="welcome-role">(<?= htmlspecialchars($_SESSION['rol'] ?? '') ?>)</span>
        </div>
      <?php else: ?>
        <a href="portal.php?action=form_login" class="btn btn-primary">Acceder</a>
      <?php endif; ?>
    </div>

  </div>
</header>



<!--Logica par mostrar el banner de cookies -->
<?php
$consent = $_COOKIE['cookie_consent'] ?? '';
$showCookieBanner = ($consent === '');
?>

<?php if ($showCookieBanner): ?>
  <div class="cookie-banner" id="cookie-banner" role="region" aria-label="Consentiment de cookies">
    <div class="cookie-banner__content">
      <p>
        Usamos cookies tecnicas para el funcionamento y cookies opcionales para funcionalidades extras (tiempo/noticias | rotacion images).
        Puedes aceptar, rechazar o configurar.
      </p>

      <div class="cookie-banner__actions">
        <button class="btn btn-primary" type="button" id="cookie-accept">Acceptar</button>
        <button class="btn btn-danger" type="button" id="cookie-reject">Rechazar</button>
        <button class="btn" type="button" id="cookie-settings">Configurar</button>
      </div>
    </div>

    <div class="cookie-settings" id="cookie-settings-panel" hidden role="dialog" aria-modal="true" aria-label="Configuració de cookies">
      <h4>Configurar cookies opcionales</h4>

      <label class="cookie-opt">
        <input type="checkbox" id="pref-aside" checked>
        Permitir tiempo y noticias (aside asíncrono)
      </label>

      <label class="cookie-opt">
        <input type="checkbox" id="pref-gallery" checked>
        Permitir rotacion de images (galeria/hero)
      </label>

      <div class="cookie-banner__actions">
        <button class="btn btn-primary" type="button" id="cookie-save">Guardar</button>
        <button class="btn" type="button" id="cookie-cancel">Cancelar</button>
      </div>
    </div>
  </div>
<?php endif; ?>


<main id="main-content">


