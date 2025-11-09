<!-- Home: Estructura semántica + renderizado inline/block + accesibilidad -->

<!-- HERO con mensaje principal -->
<section aria-labelledby="hero-title" class="hero">
  <div class="container container-image-home">
    <header class="hero__header">
      <h1 id="hero-title">Bienvenido al portal de turismo</h1>
      <p class="lead">Descubre actividades y experiencias.</p>
    </header>
  </div>
</section>

<!-- Sección informativa con <article>: contenido “block” y “inline” -->
<section aria-labelledby="info-title" class="seccion-informativa">
  <div class="container">
    <header>
      <h2 id="info-title">Cómo funciona</h2>
      <p class="muted">Ejemplo de elementos <em>inline</em> (em, strong, a, span) y <strong>block</strong> (p, article, ul).</p>
    </header>

    <div class="grid-3">
      <article class="card">
        <h3>1. Inspírate</h3>
        <p>Lee reseñas, <a href="?action=galeria">mira fotos</a> y guarda ideas con el icono <span aria-hidden="true">★</span>.</p>
      </article>
      <article class="card">
        <h3>2. Compara</h3>
        <p>Filtra por <strong>precio</strong>, <strong>ubicación</strong> y <strong>valoraciones</strong>. El contenido sigue entendible sin CSS.</p>
      </article>
      <article class="card">
        <h3>3. Reserva</h3>
        <p>Completa el <a href="?action=form_register">registro</a> y confirma. Etiquetas y <code>label</code> enlazadas a inputs.</p>
      </article>
    </div>
  </div>
</section>

<!-- Agencias principales (tu contenido), usando <figure>/<figcaption> -->
<section aria-labelledby="agencias-title" class="agencias seccion">
  <div class="container">
    <h2 id="agencias-title">Agencias principales</h2>
    <div class="grid-3">
      <figure class="agencia card">
        <img src="media/agencias/Expedia_Logo.jpg" alt="Logo de Expedia" loading="lazy" width="320" height="180">
        <figcaption>
          <h3>EXPEDIA</h3>
          <p>Viajes personalizados por toda Europa con experiencias únicas.</p>
        </figcaption>
      </figure>

      <figure class="agencia card">
        <img src="media/agencias/halcon_viajes.jpg" alt="Logo de Halcón Viajes" loading="lazy" width="320" height="180">
        <figcaption>
          <h3>HALCÓN VIAJES</h3>
          <p>Especialistas en turismo familiar y escapadas de fin de semana.</p>
        </figcaption>
      </figure>

      <figure class="agencia card">
        <img src="media/agencias/viajes_elcorteingles.jpg" alt="Logo de Viajes El Corte Inglés" loading="lazy" width="320" height="180">
        <figcaption>
          <h3>VIAJES EL CORTE INGLÉS</h3>
          <p>Aventuras exóticas y paquetes exclusivos a destinos tropicales.</p>
        </figcaption>
      </figure>
    </div>
  </div>
</section>

<!-- Aside dentro de main con recursos (tu bloque original, afinado) -->
<<aside class="recursos-seccion" aria-labelledby="recursos-title">
  <div class="container-lista">
    <h2 id="recursos-title">Recursos recomendados</h2>
    <ul class="recursos__lista">
      <li class="recursos__item"><a href="https://developer.mozilla.org/es/docs/Web/HTML/Element" rel="noopener">MDN: Elementos HTML</a></li>
      <li class="recursos__item"><a href="https://validator.w3.org/nu/" rel="noopener">Validador HTML</a></li>
      <li class="recursos__item"><a href="https://developer.mozilla.org/es/docs/Web/CSS/CSS_selectors" rel="noopener">Selectores CSS (MDN)</a></li>
    </ul>
  </div>
</aside>


<!-- Paleta de colores (tu bloque), con demo de overflow/ellipsis -->
<section class="paleta-seccion" aria-labelledby="paleta-title">
  <div class="container-paleta">
    <h2 id="paleta-title">Paleta de colores del portal</h2>

    <ul class="swatches">
      <li><span class="swatch swatch--primary-600" aria-hidden="true"></span> <code>#1b6ca8</code> (primary-600)</li>
      <li><span class="swatch swatch--primary-700" aria-hidden="true"></span> <code>#155784</code> (primary-700)</li>
      <li><span class="swatch swatch--accent-500" aria-hidden="true"></span> <code>#ffb703</code> (accent-500)</li>
      <li><span class="swatch swatch--surface" aria-hidden="true"></span> <code>#f6f8fb</code> (surface)</li>
      <li><span class="swatch swatch--text" aria-hidden="true"></span> <code>#1f2937</code> (text)</li>
    </ul>

    <!-- Mini demo de overflow + ellipsis del temario -->
    <div class="truncateoverflow" title="Texto completo accesible por tooltip">
      Este es un texto muy largo explicando la paleta de colores que demuestra white-space:nowrap, overflow:hidden y text-overflow:ellipsis en una sola línea…
    </div>
  </div>
</section>

