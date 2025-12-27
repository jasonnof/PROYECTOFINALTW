<?php
$auth = !empty($_SESSION['autentificado']);
$username = $_SESSION['username'] ?? '';
?>
<section class="game-card">
  <h2>Juego: Catch the Tourist</h2>

  <!-- Aturizado 1, no 0 -->
  <div class="game-ui"
       data-auth="<?= $auth ? '1' : '0' ?>"
       data-username="<?= htmlspecialchars($username) ?>">

    <div class="game-panel">
      <div class="game-stats">
        <div><strong>Puntos:</strong> <span id="game-score">0</span></div>
        <div><strong>Tiempos:</strong> <span id="game-time">30</span>s</div>
      </div>

      <?php if (!$auth): ?>
        <div class="game-field">
          <label for="game-nick">Nickname (invitado)</label>
          <input id="game-nick" type="text" maxlength="20" placeholder="tourist123">
        </div>
      <?php else: ?>
        <p class="game-welcome">Jugando como: <strong><?= htmlspecialchars($username) ?></strong></p>
      <?php endif; ?>

      <div class="game-actions">
        <button class="btn btn-primary" id="btn-start" type="button">Start</button>
        <button class="btn" id="btn-restart" type="button" disabled>Restart</button>
      </div>

      <p class="game-help">
        Controles: <strong>WASD</strong> o <strong>Flechas</strong>. Toca el souvenir para sumar puntos.
      </p>

      <div class="game-msg" id="game-msg" aria-live="polite"></div>
    </div>

    <div class="game-canvas-wrap">
      <canvas id="tourist-canvas" width="800" height="450" aria-label="Joc Canvas"></canvas>
    </div>
  </div>

  <h3>Ranking Top 10</h3>
  <div id="ranking-root">
    <p>Cargando ranking ...</p>
  </div>
</section>


