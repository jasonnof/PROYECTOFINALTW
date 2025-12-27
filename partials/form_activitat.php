<section class="form-card">
  <h2>Añadir actividad</h2>

  <form class="form-ui" action="portal.php?action=reg_acti" method="post" enctype="multipart/form-data" novalidate>

    <div class="field">
      <label for="title">Título *</label>
      <input id="title" name="title" type="text" required maxlength="150">
    </div>

    <div class="field">
      <label for="description">Descripción *</label>
      <textarea id="description" name="description" required rows="5"></textarea>
    </div>

    <div class="field">
      <label for="location">Localización</label>
      <input id="location" name="location" type="text" maxlength="120">
    </div>

    <div class="grid-2">
      <div class="field">
        <label for="start_at">Fecha inicio</label>
        <input id="start_at" name="start_at" type="datetime-local">
      </div>

      <div class="field">
        <label for="end_at">Fecha fin</label>
        <input id="end_at" name="end_at" type="datetime-local">
      </div>
    </div>

    <hr>

    <div class="grid-2">
      <div class="field">
        <label for="upload">Foto (opcional)</label>
        <input type="file" accept="image/*" name="foto_cliente" id="upload">
      </div>
    </div>

    <div class="field checkbox">
      <label>
        <input type="checkbox" name="published" value="1" checked>
        Publicada
      </label>
    </div>

    <button class="btn btn-primary" type="submit">Guardar</button>
  </form>
</section>
