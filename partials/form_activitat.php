<section>
  <div class="form-container">
    <form action="/portal.php?action=reg_acti" method="post" enctype="multipart/form-data">
      <label for="codigo">Código:</label><br>
      <br>
      <input type="text" id="codigo" name="codigo" required maxlength="5" placeholder="Ej. A006"><br>
      <br>
      <label for="nombre">Nombre curso:</label><br>
      <br>
      <input type="text" id="nombre" name="nombre" maxlength="50" required><br>
      <br>
      <label for="descripcion">Descripción:</label><br>
      <br>
      <textarea id="descripcion" name="descripcion" rows="3" cols="40" placeholder="Ej. Excursión al castillo" required></textarea><br>
      <br>
      <label for="alumnos_max">Alumnos máx:</label><br>
      <br>
      <input type="number" id="alumnos_max" name="alumnos_max" min="1" max="1000" required><br>
      <br>
      <label for="vacantes">Plazas vacantes:</label><br>
      <br>
      <input type="number" id="vacantes" name="vacantes" min="0" max="1000" required><br>
      <br>
      <label for="precio">Precio (€):</label><br>
      <br>
      <input type="number" id="precio" name="precio" min="0" step="0.01" required placeholder="Ej. 12.00"><br><br>
      <br>
      <label for="foto_actividad">Foto Actividad:</label>
      <br>
      <input type="file" id="foto_actividad" name="foto_actividad" accept="image/*" required>

      <button type="submit">Registrar actividad</button>
    </form>
  </div>
</section>
