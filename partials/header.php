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
</head>
<body>

<header class="site-header">
  <h1>Oficina de Turismo</h1>
  <p class="site-counter">Has visitado esta web <?= $_SESSION['visita'] ?> veces en esta sessió.</p>

  <div class="site-auth">
    <?php if (empty($_SESSION['autentificado'])): ?>
      <a href="portal.php?action=form_login" class="btn">AUTENTÍFICATE</a>
    <?php else: ?>
      <a href="portal.php?action=salida" class="btn">CERRAR SESIÓN</a>
    <?php endif; ?>
  </div>
</header>

<main>


