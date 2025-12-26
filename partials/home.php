<!-- Home: Estructura semántica + renderizado inline/block + accesibilidad -->

<!-- HERO con mensaje principal -->
<section aria-labelledby="hero-title" class="hero">
  <div class="container-image-home">
    <div class="texto-container-home">
      <h1 id="hero-title">Portal de Turismo Internacional</h1>
      <p class="lead">Experiencias, rutas y actividades culturales por todo el mundo.</p>

      <p style="margin-top:14px;">
        <a class="enlaceBoton" href="portal.php?action=tablas">Ver actividades</a>
        <a class="enlaceBoton" style="margin-left:10px;" href="portal.php?action=galeria">Galería</a>
      </p>

      <p class="muted" style="margin-top:12px;">
        Destinos como Colombia, Budapest, México y muchos más · PHP + MySQL + Fetch + Canvas · Acceso por roles
      </p>
    </div>
  </div>
</section>


<section aria-labelledby="info-title" class="seccion-informativa">
  <div class="container">
    <header>
      <h2 id="info-title">¿Qué puedes hacer aquí?</h2>
      <p class="muted">Portal para promocionar actividades turísticas internacionales y gestionar trabajadores (roles admin/gestor/visitante).</p>
    </header>

    <div class="grid-3">
      <article class="card">
        <h3>Actividades</h3>
        <p>Consulta actividades publicadas con lugar, fechas, imágenes y descripción de cada experiencia.</p>
      </article>

      <article class="card">
        <h3>Galería</h3>
        <p>Explora fotografías de distintos países y ciudades. También tenemos una imagen destacada que rota de forma asíncrona.</p>
      </article>

      <article class="card">
        <h3>Juego Canvas</h3>
        <p>Juega a “Catch the Tourist”, consigue puntos y guarda tu puntuación en el ranking.</p>
      </article>
    </div>
  </div>
</section>

<!-- Agencias principales (tu contenido), usando <figure>/<figcaption> -->
<section aria-labelledby="agencias-title" class="agencias seccion">
  <div class="container">
    <h2 id="agencias-title">Agencias colaboradoras</h2>
    <div class="grid-3">
      <figure class="agencia card">
        <img src="media/agencias/expedia.png" alt="Logo de Expedia" loading="lazy" width="320" height="180">
        <figcaption>
          <h3>EXPEDIA</h3>
          <p>Escapadas internacionales y viajes a medida para cualquier destino.</p>
        </figcaption>
      </figure>

      <figure class="agencia card">
        <img src="media/agencias/halcon_viajes.jpg" alt="Logo de Halcón Viajes" loading="lazy" width="320" height="180">
        <figcaption>
          <h3>HALCÓN VIAJES</h3>
          <p>Especialistas en viajes familiares, circuitos y rutas por Europa y América.</p>
        </figcaption>
      </figure>

      <figure class="agencia card">
        <img src="media/agencias/viajes_elcorteingles.jpg" alt="Logo de Viajes El Corte Inglés" loading="lazy" width="320" height="180">
        <figcaption>
          <h3>VIAJES EL CORTE INGLÉS</h3>
          <p>Destinos exóticos, experiencias premium y viajes culturales por todo el mundo.</p>
        </figcaption>
      </figure>
    </div>
  </div>
</section>



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
      Portal orientado a turismo internacional: países, ciudades y experiencias. Ejemplo de ellipsis con white-space:nowrap, overflow:hidden y text-overflow:ellipsis…
    </div>
  </div>
</section>
