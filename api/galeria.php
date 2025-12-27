<?php 

require_once __DIR__ . "/../lib/db.php";

//Saco las 12 fotos para ponerlas en una galerai fija
$stmt = $pdo->prepare("
SELECT title, file_path
FROM gallery_images
WHERE active = 1
ORDER BY id DESC
");

$stmt->execute();

$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="gallery-page">
<h2>Imagen destacada</h2>
<!--IAMGEN QUE CAMBIA DESTACADA ASINCRONA -->
<div class="gallery-feature">
  <img id="gallery-feature-img" class="gallery-feature-img" src=""
  alt="Imagen destacada de la galeria">
   <p id="gallery-feature-title" class="gallery-feature-title" aria-live="polite">
      Cargando imagen destacada...
    </p>
</div>


<!-- GRID IMAGENES FIJO -->
 <h2>Descubre todos los lugares</h2>
 <div class="gallery-grid">
  <?php foreach($images as $img) : ?>
    <figure class="gallery-item">
      <img 
      src="<?= htmlspecialchars($img['file_path']) ?>"
      alt="<?= htmlspecialchars($img['title']) ?>"
      loading="lazy"
      >

      <!--El titulo lo pongo coo figcaption -->
      <?php if (!empty($img['title'])) : ?>
        <figcaption>
          <?= htmlspecialchars($img['title']) ?>
        </figcaption>
      <?php endif; ?>
    </figure>
    <?php endforeach; ?>
 </div>
 </section>




















