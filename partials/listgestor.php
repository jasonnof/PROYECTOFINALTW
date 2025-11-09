<?php
ini_set('display_errors',1);

$jsonPath = dirname(__FILE__) . "/../recursos/activitats.json";
$actividades = [];

$json = file_get_contents($jsonPath);
$actividades = json_decode($json, true); // diccionario de actividades
?>

<h2 id="tabla-title">LISTADO DE ACTIVIDADES (GESTOR)</h2>

<?php if (empty($actividades)): ?>
  <p>No hay actividades registradas</p>
<?php else: ?>
  <table class="activities__table" aria-describedby="tabla-title">
    <caption class="visually-hidden">Tabla de actividades (gestor)</caption>
    <thead>
      <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Alumn max</th>
        <th>Plazas vacantes</th>
        <th>Precio</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($actividades as $nombre => $actividad): ?>
        <?php $id = urlencode($nombre); ?>
        <tr>
          <td><?= $actividad['codigo'] ?></td>
          <td><?= $nombre ?></td>
          <td><?= $actividad['descripcion'] ?></td>
          <td><?= $actividad['alumnos_max'] ?></td>
          <td><?= $actividad['vacantes'] ?></td>
          <td><?= $actividad['precio'] ?></td>
          <td>
            <a href="portal.php?action=modificar&acti=<?= $id ?>">Modificar</a>
             |
            <a href="portal.php?action=borrar&acti=<?= $id ?>">Borrar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
