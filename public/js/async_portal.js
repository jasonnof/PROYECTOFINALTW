//Variables globales
let galleryTimer = null;
let galleryRunning = false;

//FUNCION PARA CARGAR ACTIVIDADES DESDE EL ENDPOINT API
//Crearemos una tabla con js de todas las actividades de la base de datos
async function loadActivities(targetEl) {
  targetEl.innerHTML = "<p>Cargango...</p>";

  try {
    const res = await fetch("portal.php?action=api_actividades", { credentials: "same-origin" });
    if (!res.ok) throw new Error("HTTP " + res.status);

    const text = await res.text();
    const json = JSON.parse(text);

    if (!json.ok) throw new Error(json.error || "API error");

    const data = json.data || [];

    const table = document.createElement("table");
    table.classList.add("activities-table"); //Estilos en css
    const thead = document.createElement("thead");
    const trh = document.createElement("tr");

   const headers = ["Titulo", "Lugar", "Inicio", "Fin", "Imagen", "Descripcion"];
    if (json.canManage) headers.push("Estado", "Accions");

    headers.forEach(h => {
    const th = document.createElement("th");
    th.textContent = h;
    trh.appendChild(th);
});


    thead.appendChild(trh);
    table.appendChild(thead);

    const tbody = document.createElement("tbody");

    data.forEach(row => {
      const tr = document.createElement("tr");

      const tdTitle = document.createElement("td");
      tdTitle.textContent = row.title ?? "";
      tr.appendChild(tdTitle);

      const tdLoc = document.createElement("td");
      tdLoc.textContent = row.location ?? "";
      tr.appendChild(tdLoc);

      const tdStart = document.createElement("td");
      tdStart.textContent = row.start_at ?? "";
      tr.appendChild(tdStart);

      const tdEnd = document.createElement("td");
      tdEnd.textContent = row.end_at ?? "";
      tr.appendChild(tdEnd);

      const tdImg = document.createElement("td");
      if (row.image_path) {
        const img = document.createElement("img");
        img.classList.add("activity-img"); //css
        img.src = row.image_path;
        img.alt = row.title ? `Imagen ${row.title}` : "Imagen actividad";
        img.style.maxWidth = "120px";
        tdImg.appendChild(img);
      } else {
        tdImg.textContent = "-";
      }
      tr.appendChild(tdImg);

      const tdDesc = document.createElement("td");
      const d = row.description ?? "";
      tdDesc.textContent = d.length > 80 ? d.slice(0, 80) + "…" : d;
      tr.appendChild(tdDesc);

      tbody.appendChild(tr);

      //Funciones para el administrador
      if (json.canManage) {
  // Estado
  const tdState = document.createElement("td");
  tdState.classList.add("status");
  tdState.classList.toggle("status-on", row.published == 1);
  tdState.classList.toggle("status-off", row.published != 1);

  tdState.textContent = (row.published == 1) ? "Publicada" : "No publicada";
  tr.appendChild(tdState);

  // Acciones
  const tdActions = document.createElement("td");

  const btnEdit = document.createElement("button");
  btnEdit.classList.add("btn", "btn-edit"); //Estilos css
  btnEdit.type = "button";
  btnEdit.textContent = "Editar";
  btnEdit.addEventListener("click", () => {
  window.location.href = `portal.php?action=modificar&id=${row.id}`;
  });

  const btnUnpub = document.createElement("button");
  btnUnpub.type = "button";

  //Logica para despublicar/publicar
  //Si esta publicada
  if(row.published == 1){
    btnUnpub.textContent = "Despublicar";
    btnUnpub.classList.add("btn", "btn-danger");
    btnUnpub.addEventListener("click", async () => {
    if (!confirm("Despublicar esta actividad?")) return;

    const fd = new FormData();
    fd.append("id", row.id);

    const res = await fetch("portal.php?action=api_actividades_borrar", {
         method:"POST", 
         body: fd, 
         credentials:"same-origin" })

    
    const j = await res.json();
    if (!j.ok) {
      alert(j.error || "Error");
      return;
    }

    // Recargar tabla sin recargar página
    loadActivities(targetEl);

  
  });

}else{
  //Si no esta publicada, opcion para publicar
  btnUnpub.textContent = "Publicar";
  btnUnpub.classList.add("btn", "btn-success");
  btnUnpub.addEventListener("click", async () => {
    const fd = new FormData(); //Esto es para pasarselo a fetch
    fd.append("id", row.id);

    const res = await fetch("portal.php?action=api_actividades_publicar",
      {method:"POST",
       body:fd,
       credentials: "same-origin"
      }
    );

    const j = await res.json();
    if(!j.ok) {alert(j.error || "Error"); return;}

    //Recarga tabla sin recargar pagina
    loadActivities(targetEl);

  });


}

  tdActions.appendChild(btnEdit);
  tdActions.appendChild(document.createTextNode(" "));
  tdActions.appendChild(btnUnpub);

  tr.appendChild(tdActions);
}

    });

    table.appendChild(tbody);

    targetEl.innerHTML = "";
    targetEl.appendChild(table);

  } catch (err) {
    targetEl.innerHTML = `<p>Error cargando actividades: ${err.message}</p>`;
  }
}

//LOGICA PRINCIPAL
document.addEventListener("DOMContentLoaded", () => {
  const page = document.getElementById("page-content");

  //Cargue la tabla de actividades
  const root = document.getElementById("activities-root");
  if (root) loadActivities(root);

  

  //Inicializamos cookies y demas segun preferencias
  initCookieBannerAndExtras();

  //Previwe foto crear actividad
  InicializarFoto();


});







//FUNCIONES IMAGENES

//Tiempo random
function randMs(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

//Funcion que llama a la API
async function fetchRandomGalleryItem() {
  const res = await fetch("portal.php?action=api_gallery_random", { credentials: "same-origin" });
  if (!res.ok) throw new Error("HTTP " + res.status);
  return await res.json();
}

//Funcion principal
function startGalleryFeaturedRotation() {
  const img = document.getElementById("gallery-feature-img");
  const titleEl = document.getElementById("gallery-feature-title");
  if (!img || !titleEl) return;

  // Evita duplicados: si ya estaba corriendo, lo reinicias limpio
  stopGalleryRotation();

  //Rotacion en marcha
  galleryRunning = true;

  const loadOne = async () => {
    if (!galleryRunning) return;

    try {
      const json = await fetchRandomGalleryItem();
      if (!json.ok) throw new Error(json.error || "API error");

      if (!json.item) {
        titleEl.textContent = "No hay imagenes activas.";
      } else {
        const { title, file_path } = json.item;

        img.classList.add("is-fading");
        
        //Transicion de cambio
        setTimeout(() => {
          if (!galleryRunning) return; // por si se paró durante el fade

          img.src = file_path;
          img.alt = title ? `Imagen: ${title}` : "Imagen destacada de la galeria";
          titleEl.textContent = title || "";
          img.classList.remove("is-fading");
        }, 350);
      }

    } catch (err) {
      titleEl.textContent = "Error cargando la galeria.";
      console.error(err);
    } finally {

      //Si la galeria esta corriendo
      //Programamos la siguiente rotacion, durara entre 4 y 6 segundos
      if (galleryRunning) {
        galleryTimer = setTimeout(loadOne, randMs(4000, 6000));
      }
    }
  };

  loadOne();
}

//Parar la rotacion
function stopGalleryRotation() {
  galleryRunning = false;
  if (galleryTimer) clearTimeout(galleryTimer);
  galleryTimer = null;
}



//FUNCIONES API TIEMPO Y NOTICIAS

//Funcion api tiempo
async function loadWeather() {
  const box = document.getElementById("weather-box");
  if (!box) return;

  box.textContent = "Cargando...";

  try {
    const res = await fetch("portal.php?action=api_weather", { credentials: "same-origin" });
    const json = await res.json();

    if (!json.ok) throw new Error(json.error || "API error");

    const c = json.current || {};
    const place = json.place ?? "";
    const updated = json.updated_at ?? "";

    const temp = (c.temp ?? "—");
    const feels = (c.feels ?? "—");
    const wind = (c.wind ?? "—");
    const precip = (c.precip ?? "—");

    box.innerHTML = `
      <div class="weather-grid">
        <div class="weather-place">${place}</div>

        <div class="weather-line"><span>🌡️ Temperatura</span><b>${temp}°C</b></div>
        <div class="weather-line"><span>🤔 Sensación</span><b>${feels}°C</b></div>
        <div class="weather-line"><span>💨 Viento</span><b>${wind} km/h</b></div>
        <div class="weather-line"><span>🌧️ Lluvia</span><b>${precip} mm</b></div>

        <div class="weather-updated">Actualizado: ${updated}</div>
      </div>
    `;
  } catch (e) {
    box.textContent = "Error cargando el tiempo";
    console.error(e);
  }
}



//Funcion api noticias
async function loadNews() {
  const box = document.getElementById("news-box");
  if (!box) return;

  box.textContent = "Cargando...";

  try {
    const res = await fetch("portal.php?action=api_news", { credentials: "same-origin" });
    const json = await res.json();
    if (!json.ok) throw new Error(json.error || "API error");

    const items = json.items || [];

    if (items.length === 0) {
      box.textContent = "No hay noticias ahora mismo.";
      return;
    }

    box.innerHTML = `
      <ul class="news-list">
        ${items.map(n => `
          <li class="news-item">
            <a href="${(n.link)}" target="_blank" rel="noopener">
              ${(n.title)}
            </a>
            ${n.date ? `<div class="news-meta">${(n.date)}</div>` : ""}
          </li>
        `).join("")}
      </ul>
    `;

  } catch (e) {
    box.textContent = "Error cargando noticias";
    console.error(e);
  }
}

//LOGICA BANNER DE COOKIES 
//Funcion para crear cookies a partir de nombre,valor y segundos
function setCookie(name, value, maxAgeSeconds) {
  document.cookie = name + "=" + encodeURIComponent(value)
    + "; Max-Age=" + maxAgeSeconds
    + "; Path=/; SameSite=Lax";
}


//Obtener cookies por nombre
function getCookie(name) { 
  //Recorro todas y saco las que me interesa
  const all = document.cookie; // "a=1; b=2; ..."
  if (!all) return "";

  const parts = all.split(";"); // ["a=1", " b=2", ...]
  for (let i = 0; i < parts.length; i++) {
    const p = parts[i].trim();     // "b=2"
    const eq = p.indexOf("=");     // posición del '='
    if (eq === -1) continue;

    const key = p.substring(0, eq).trim();
    const val = p.substring(eq + 1);

    if (key === name) return decodeURIComponent(val);
  }
  return "";
}


//Obtengo las preferencias del usuario y muestro en funcion de eso
function getCookiePrefs() {
  const consent = getCookie("cookie_consent");
  const prefsRaw = getCookie("cookie_prefs");

  // accepted = todo permitido
  if (consent === "accepted") {
    return { aside: true, gallery: true };
  }

  // rejected = nada extra
  if (consent === "rejected") {
    return { aside: false, gallery: false };
  }

  // custom = depende de cookie_prefs
  if (consent === "custom") {
    const parts = (prefsRaw || "").split(",").map(s => s.trim()).filter(Boolean);
    return {
      aside: parts.includes("aside"), //true o false
      gallery: parts.includes("gallery")
    };
  }

  // sin decidir aún, no muestro nada
  return { aside: false, gallery: false };
}

//Inicializo el banner
function initCookieBannerAndExtras() {
  //Cojemos todos los botones
  const banner = document.getElementById("cookie-banner");
  const btnAccept = document.getElementById("cookie-accept");
  const btnReject = document.getElementById("cookie-reject");
  const btnSettings = document.getElementById("cookie-settings");
  const panel = document.getElementById("cookie-settings-panel");

  const prefAside = document.getElementById("pref-aside");
  const prefGallery = document.getElementById("pref-gallery");
  const btnSave = document.getElementById("cookie-save");
  const btnCancel = document.getElementById("cookie-cancel");

  // Si no hay banner, ya existe cookie -> arrancar extras según preferencias
  if (!banner) {
    const prefs = getCookiePrefs();
    if (prefs.aside) {  startAsideAsync(); }
    if (prefs.gallery) { startGalleryRotation(); }
    return;
  }

  function closeBanner() {
    banner.remove();
  }

  btnAccept.addEventListener("click", () => {
    setCookie("cookie_consent", "accepted", 60 * 60 * 24 * 365);
    setCookie("cookie_prefs", "aside,gallery", 60 * 60 * 24 * 365);
    closeBanner();
    // arrancar extras
    startAsideAsync();
    startGalleryRotation();
  });

  btnReject.addEventListener("click", () => {
    setCookie("cookie_consent", "rejected", 60 * 60 * 24 * 365);
    setCookie("cookie_prefs", "", 60 * 60 * 24 * 365);
    closeBanner();
    stopAsideAsync();
    stopGalleryRotation();
    // No arrancamos extras
  });

  btnSettings.addEventListener("click", () => {
    panel.hidden = false;
  });

  btnCancel.addEventListener("click", () => {
    panel.hidden = true;
  });

  btnSave.addEventListener("click", () => {
    //Compruebo cual estan marcados
    const allowAside = prefAside.checked;
    const allowGallery = prefGallery.checked;

    const prefs = [
      allowAside ? "aside" : null,
      allowGallery ? "gallery" : null
    ].filter(Boolean).join(",");
    //"aside,gallery" o //"null,null"

    setCookie("cookie_consent", "custom", 60 * 60 * 24 * 365);
    setCookie("cookie_prefs", prefs, 60 * 60 * 24 * 365);

    panel.hidden = true;
    closeBanner();

    //Inicializamos unas cosas u otras segun preferencias
   if (allowAside) startAsideAsync(); else stopAsideAsync();
   if (allowGallery) startGalleryRotation(); else stopGalleryRotation();

  });
}

let weatherTimer = null;
let newsTimer = null;

function startAsideAsync() {
  // primera carga
  loadWeather();
  loadNews();

  // refrescos
  if (weatherTimer) clearInterval(weatherTimer);
  if (newsTimer) clearInterval(newsTimer);

  weatherTimer = setInterval(loadWeather, 5 * 60 * 1000); // 5 minutos
  newsTimer = setInterval(loadNews, 10 * 60 * 1000); // 10 minutos
}

function stopAsideAsync() {
  if (weatherTimer) clearInterval(weatherTimer);
  if (newsTimer) clearInterval(newsTimer);
  weatherTimer = null;
  newsTimer = null;
}

function startGalleryRotation() {
  startGalleryFeaturedRotation();
}


//MOSTRAR IMAGEN PREVIEW SUBIDA AL CREAR ACTIVIDAD
function mostrarImagen(nodo, imagen) {
    imagen.style.display = "block";
    imagen.classList.add("preview-img");
     var reader = new FileReader();
     reader.addEventListener("load", function () {
          imagen.src = reader.result;
     });
     reader.readAsDataURL(nodo.files[0]);
}


//Funcion para el preview de la foto
function InicializarFoto(){
  
  var fichero = document.querySelector("#upload");
  if(!fichero){return;}
  var child = document.createElement("img");
  
     child.setAttribute("width", "220px");
     child.setAttribute("height", "220px");
     child.setAttribute("id", "imagen");
     child.style.display = "none";
  let imagen = fichero.parentNode.appendChild(child);

  fichero.addEventListener("change", function (event) {
          mostrarImagen(this, imagen);
     });


}