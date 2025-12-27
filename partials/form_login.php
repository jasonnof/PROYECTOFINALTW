<section class="auth">
  <h2>Iniciar sesión</h2>

  <form class="auth-form" action="portal.php?action=autentificar" method="post" novalidate>
    <div class="field">
      <label for="username">Usuario</label>
      <input id="username" name="username" type="text" required autocomplete="username">
    </div>

    <div class="field">
      <label for="password">Contraseña</label>
      <input id="password" name="password" type="password" required autocomplete="current-password">
    </div>

    <button class="btn btn-primary" type="submit">Entrar</button>
  </form>
</section>
