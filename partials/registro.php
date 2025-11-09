<section class="registro-section">
  <h2>Registro de usuario</h2>
  <div class="registro-wrap">
  <form class="registro-form" action="/portal.php?action=registrarse" method="post">
    <label for="login">Login</label>
    <br>
    <input type="text" id="login" name="login" required maxlength="50"
           value="<?= $_POST['login'] ?? '' ?>"><br><br>

    <label for="passwd">Password</label>
    <br>
    <input type="password" id="passwd" name="passwd" required maxlength="100"><br><br>

    <button type="submit">Crear cuenta</button>
  </form>
  </div>
</section>
