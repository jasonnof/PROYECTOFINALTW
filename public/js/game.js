(() => {
  const canvas = document.getElementById("tourist-canvas");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");

 
  

  //Cojo todos los elementos
  const ui = document.querySelector(".game-ui");
  const isAuth = ui?.dataset.auth === "1";
  const username = ui?.dataset.username || "";

  const scoreEl = document.getElementById("game-score");
  const timeEl = document.getElementById("game-time");
  const msgEl = document.getElementById("game-msg");
  const nickInput = document.getElementById("game-nick");

  const btnStart = document.getElementById("btn-start");
  const btnRestart = document.getElementById("btn-restart");

  const rankingRoot = document.getElementById("ranking-root");

  if (!btnStart || !btnRestart) return;


  //Logica juego

  //Ancho y alto del canvas
  let W = canvas.width;
  let H = canvas.height;


  //Posicion inicial y tamaño del jugador
  const player = {
    x: 60, y: 60,
    w: 28, h: 28,
    speed: 220 // velocidad 220 px/seg
  };

  //Dimesniones del objetivo, x y e radio del circulo
  const target = {
    x: 300, y: 200,
    r: 14
  };

  //Mis variables principales de accion
  let keys = { up:false, down:false, left:false, right:false };
  let running = false;
  let score = 0;

  //Timepo maximo de juego 30 segundos
  const GAME_SECONDS = 30;
  let timeLeft = GAME_SECONDS;

  let lastTs = 0;
  let startTs = 0;

  //Funcion para poner mensajes por pantalla
  function setMsg(text, type = "") {
    msgEl.textContent = text;
    msgEl.className = "game-msg " + type;
  }

  function clamp(v, min, max) { return Math.max(min, Math.min(max, v)); }

  //Posicion aleatoria del target
  function randomTarget() {
    // evita que salga pegado al borde con padding
    const pad = 24;
    target.x = pad + Math.random() * (W - pad*2);
    target.y = pad + Math.random() * (H - pad*2);
  }

  function rectCircleCollide(px, py, pw, ph, cx, cy, cr) {
    // punto más cercano del rectángulo al círculo
    const closestX = clamp(cx, px, px + pw);
    const closestY = clamp(cy, py, py + ph);
    const dx = cx - closestX;
    const dy = cy - closestY;
    return (dx*dx + dy*dy) <= cr*cr;
  }

  //Resetear la partida
  function resetGame() {
    player.x = 60; player.y = 60;
    score = 0;
    timeLeft = GAME_SECONDS;
    lastTs = 0;
    startTs = 0;
    randomTarget();

    scoreEl.textContent = "0";
    timeEl.textContent = String(GAME_SECONDS);
    setMsg("Preparado? Toca Start.", "");
  }

  //Empezar la partida
  function startGame() {
    // si invitado, valida nickname en cliente también (igual server valida)
    if (!isAuth) {
      const nick = (nickInput?.value || "").trim();
      if (nick.length < 3) {
        setMsg("Pon un nickname (mínimo 3).", "error");
        nickInput?.focus();
        return;
      }
    }

    running = true;
    //Desactivamos start y activamso restart importante
    btnStart.disabled = true;
    btnRestart.disabled = false;
    setMsg("Go!", "ok");

    //Actulizar frames
    startTs = performance.now();
    lastTs = startTs;
    requestAnimationFrame(loop);
  }

  //Finalizar la partida
  function endGame() {
    running = false;
    btnStart.disabled = false;
    btnRestart.disabled = false;

    setMsg(`Tiempo! Puntos finales: ${score}. Guardando...`, "info");

    submitScore().then(() => {
      loadRanking();
    });
  }

  //Actualizamos la posicion del rectangulo segun la tecla presionada
  function update(dt) {
    const vx = (keys.right ? 1 : 0) - (keys.left ? 1 : 0);
    const vy = (keys.down ? 1 : 0) - (keys.up ? 1 : 0);

    // normaliza diagonal
    let nx = vx, ny = vy;
    if (nx !== 0 && ny !== 0) {
      const inv = 1 / Math.sqrt(2);
      nx *= inv; ny *= inv;
    }

    player.x += nx * player.speed * dt;
    player.y += ny * player.speed * dt;

    // límites
    player.x = clamp(player.x, 0, W - player.w);
    player.y = clamp(player.y, 0, H - player.h);

    // colisión:sumamos 10 puntos por objetivo comido
    if (rectCircleCollide(player.x, player.y, player.w, player.h, target.x, target.y, target.r)) {
      score += 10;
      //Actualizamos score
      scoreEl.textContent = String(score);
      //Cambiamos la ubicacion del objetivo
      randomTarget();
    }
  }

  function draw() {
    // fondo
    ctx.clearRect(0, 0, W, H);
    ctx.fillStyle = "#f6fbff";
    ctx.fillRect(0, 0, W, H);

    // borde
    ctx.strokeStyle = "#cfd8e3";
    ctx.strokeRect(0.5, 0.5, W-1, H-1);

    // target (souvenir)
    ctx.beginPath();
    ctx.fillStyle = "#ffb84d";
    ctx.arc(target.x, target.y, target.r, 0, Math.PI * 2);
    ctx.fill();
    ctx.strokeStyle = "#d48a1a";
    ctx.stroke();

    // player (tourist)
    ctx.fillStyle = "#3b82f6";
    ctx.fillRect(player.x, player.y, player.w, player.h);

    // carita player
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(player.x + 7, player.y + 8, 4, 4);
    ctx.fillRect(player.x + 17, player.y + 8, 4, 4);
  }

  function loop(ts) {
    if (!running) return;

    const dt = Math.min(0.033, (ts - lastTs) / 1000); 
    lastTs = ts;

    // tiempo
    const elapsed = (ts - startTs) / 1000;
    const remain = Math.max(0, GAME_SECONDS - elapsed);
    timeLeft = remain;
    timeEl.textContent = String(Math.ceil(remain));

    update(dt);
    draw();

    //Si llegamos a 0 segundos se termina la partida
    if (remain <= 0) {
      endGame();
      return;
    }
    requestAnimationFrame(loop);
  }

  // Eventos DOM
  function onKey(e, isDown) {
    const k = e.key.toLowerCase();
    if (["arrowup","w"].includes(k)) keys.up = isDown;
    if (["arrowdown","s"].includes(k)) keys.down = isDown;
    if (["arrowleft","a"].includes(k)) keys.left = isDown;
    if (["arrowright","d"].includes(k)) keys.right = isDown;
  }
  //Listener keydown para cuando se presiona una tecla
  window.addEventListener("keydown", (e) => {
    if (["ArrowUp","ArrowDown","ArrowLeft","ArrowRight"].includes(e.key)) e.preventDefault();
    onKey(e, true);
  }, { passive:false });

  window.addEventListener("keyup", (e) => onKey(e, false));

  //Listenes para empezar y finalizar la partida
  btnStart.addEventListener("click", startGame);
  btnRestart.addEventListener("click", () => {
    resetGame();
    startGame();
  });

  // Logica API para mostrar el ranking
  async function loadRanking() {
    if (!rankingRoot) return;
    rankingRoot.innerHTML = "<p>Cargando ranking...</p>";

    try {
      const res = await fetch("portal.php?action=api_scores_top", { credentials: "same-origin" });
      const json = await res.json();
      if (!json.ok) throw new Error(json.error || "API error");

      const rows = json.data || [];
      if (rows.length === 0) {
        rankingRoot.innerHTML = "<p>Todavia no hay puntuaciones.</p>";
        return;
      }

      const table = document.createElement("table");
      table.className = "activities-table";
      table.innerHTML = `
        <thead>
          <tr><th>#</th><th>Nombre</th><th>Puntos</th><th>Fecha</th></tr>
        </thead>
      `;
      const tbody = document.createElement("tbody");

      rows.forEach((r, i) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${i+1}</td>
          <td>${(r.name ?? "")}</td>
          <td>${Number(r.score ?? 0)}</td>
          <td>${((r.created_at ?? "").slice(0,19).replace("T"," "))}</td>
        `;
        tbody.appendChild(tr);
      });

      table.appendChild(tbody);
      rankingRoot.innerHTML = "";
      rankingRoot.appendChild(table);
    } catch (err) {
      rankingRoot.innerHTML = `<p>Error cargando ranking: ${escapeHtml(err.message)}</p>`;
    }
  }

  //Logica para gaurdar el score
  async function submitScore() {
    try {
      const fd = new FormData();
      fd.append("score", String(score));
      fd.append("duration", String(GAME_SECONDS));

      if (!isAuth) {
        fd.append("nickname", (nickInput?.value || "").trim());
      }

      const res = await fetch("portal.php?action=api_score_submit", {
        method: "POST",
        body: fd,
        credentials: "same-origin",
      });

      const json = await res.json();
      if (!json.ok) throw new Error(json.error || "No se ha podido guardar");

      setMsg(`Guardado! Score: ${json.saved_score}`, "ok");
    } catch (e) {
      setMsg(`No se ha podido guardar: ${e.message}`, "error");
    }
  }




  function resizeTouristCanvas() {
  const canvas = document.getElementById("tourist-canvas");
  const wrap = canvas?.closest(".game-canvas-wrap");
  if (!canvas || !wrap) return;

  const dpr = window.devicePixelRatio || 1;

  // ancho disponible REAL
  const cssW = wrap.clientWidth;

  // altura proporcional (16:9). Cambia si quieres otro ratio.
  const cssH = Math.round(cssW * 9 / 16);

  // tamaño CSS (lo que ves)
  canvas.style.width = cssW + "px";
  canvas.style.height = cssH + "px";

  // tamaño real del buffer (lo que dibuja)
  canvas.width = Math.round(cssW * dpr);
  canvas.height = Math.round(cssH * dpr);

  const ctx = canvas.getContext("2d");
  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

  W = cssW;
  H = cssH;

  // Si ya estaba en marcha, evita que player/target se queden fuera
  player.x = clamp(player.x, 0, W - player.w);
  player.y = clamp(player.y, 0, H - player.h);
  target.x = clamp(target.x, target.r, W - target.r);
  target.y = clamp(target.y, target.r, H - target.r);

 
}

window.addEventListener("resize", resizeTouristCanvas);
document.addEventListener("DOMContentLoaded", resizeTouristCanvas);


  // init
resizeTouristCanvas();
resetGame();
loadRanking();

})();
