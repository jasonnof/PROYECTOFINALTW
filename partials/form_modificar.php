<?php
ini_set('display_errors', 1);

//Cojo el nombre de la actividad de la query string
$acti = urldecode($_GET['acti'] ?? '');
$jsonPath = dirname(__FILE__) . "/../recursos/activitats.json";

$actividades = [];
if (is_file($jsonPath)) {
  $raw = file_get_contents($jsonPath);
  $actividades = json_decode($raw, true);
}

//Compruebo si es una actividad valida y existe
if ($acti === '' || !isset($actividades[$acti])) {
  $_error_message = "Actividad no encontrada.";
  $actividad = [
    "codigo" => "", "nombre" => "", "descripcion" => "",
    "alumnos_max" => "", "vacantes" => "", "precio" => ""
  ];
} else {
    //Si existe, lo cojo del diccionario
  $actividad = $actividades[$acti];
}
?>

<section class="form-section">
  <h1>Modificar actividad</h1>

  <?php if (!empty($_error_message)): ?>
    <div class="error"><?= $_error_message ?></div>
  <?php endif; ?>

  <form class="activities__form" action="portal.php?action=modificar&acti=<?= urlencode($acti) ?>" method="post" enctype="multipart/form-data">
    <!-- Mantener el nombre original (clave del diccionario) -->
    <input type="hidden" name="original_nombre" value="<?= $acti ?>">

    <label for="codigo">Código</label>
    <input type="text" id="codigo" name="codigo" required maxlength="15" value="<?= $actividad['codigo'] ?? '' ?>">

    <label for="descripcion">Descripción</label>
    <textarea id="descripcion" name="descripcion" rows="3" required><?= $actividad['descripcion'] ?? '' ?></textarea>

    <label for="alumnos_max">Alumnos máx</label>
    <input type="number" id="alumnos_max" name="alumnos_max" min="1" max="1000" required value="<?= $actividad['alumnos_max'] ?? '' ?>">

    <label for="vacantes">Plazas vacantes</label>
    <input type="number" id="vacantes" name="vacantes" min="0" max="1000" required value="<?= $actividad['vacantes'] ?? '' ?>">

    <label for="precio">Precio (€)</label>
    <input type="number" id="precio" name="precio" min="0" step="0.01" required value="<?= $actividad['precio'] ?? '' ?>">

    <label>Foto Actual:</label>
    <img class="current-photo" src="<?php echo $actividad['img_URL'] ?>">
    <br> </br>
    <label for="foto_actividad">Foto Actividad:</label>
    <br>
    <input type="file" id="foto_actividad" name="foto_actividad" accept="image/*" required>
    <br></br>

    <div class="form-actions">
      <button type="submit">Guardar cambios</button>
      <a class="btn" href="portal.php?action=list">Cancelar</a>
    </div>
  </form>
</section>
