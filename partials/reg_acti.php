<?php  


#Depuracion de errores y variable para saber como ha salido
ini_set('display_errors', 1);
$ok = false;

try{

#Cojo los parametros del cuerpo de la peticion ya que es un post

    $codigo       = trim($_POST['codigo']       ?? '');
    $nombre       = trim($_POST['nombre']       ?? '');
    $descripcion  = trim($_POST['descripcion']  ?? '');
    $alumnos_max  = $_POST['alumnos_max'] ?? null;
    $vacantes     = $_POST['vacantes']    ?? null;
    $precio       = $_POST['precio']      ?? null;


    if(!isset($_FILES["foto_actividad"])){
        throw new RuntimeException("No se recibio imagen");
    }

    // --- Imagen (asegurar extensión y nombre visible) ---
$imagen = $_FILES['foto_actividad'];

// Carpeta destino (FS) y URL base (web)
$dirDestino = dirname(__FILE__) . "/../media/fotos";
$urlBase    = "/media/fotos";

//Creo directorio destino si no que existe
if (!is_dir($dirDestino)) { mkdir($dirDestino, 0775, true); }



$fichero = $imagen['name'];
$destino     = $dirDestino . "/" . $fichero;   
$urlRelativa = $urlBase    . "/" . $fichero;   // URL para el navegador

//SUBO LA IMAGEN
if (!move_uploaded_file($imagen['tmp_name'], $destino)) {
    throw new RuntimeException("No se pudo guardar la imagen en el servidor.");
}



#Vuelvo a validar que los datos sean correctos
 if ($codigo === '' || $nombre === '' || $descripcion === '' ||
        $alumnos_max === null || $vacantes === null || $precio === null) {
        throw new RuntimeException("Faltan datos obligatorios.");
    }


#Ruta del fichero
$jsonPath = dirname(__FILE__) . "/../recursos/activitats.json";


#Me traigo el diccionario
$dicc_actividades = json_decode(file_get_contents( $jsonPath ), true);

#Las claves son los npmbres de actividades , no pueden aver repetidos
 if (array_key_exists($nombre, $dicc_actividades)) {
        throw new RuntimeException("El nombre de curso ya existe.");
    }




    #Compruebo que ninguna actividad tenga el mismo codigo tmbn
    foreach($dicc_actividades as $k => $actividad){
        if ( (isset($actividad['codigo'])) && ($actividad['codigo'] === $codigo)){
            throw new RuntimeException("El codigo de actividad ya existe");
        }
    }

    $dicc_actividades[$nombre] = [
        "codigo"       => $codigo,
        "nombre"       => $nombre,
        "descripcion"  => $descripcion,
        "alumnos_max"  => (int)$alumnos_max,
        "vacantes"     => (int)$vacantes,
        "precio"       => (float) number_format($precio, 2, '.', ''), 
        "img_URL"   => $urlRelativa,               // ← ahora incluye .jpg/.png/etc.
        "name_foto" => (string)($fichero), // ← nombre original subido
  
    ];

    #Vuelvo a codificar el diccionario
   $json = json_encode($dicc_actividades, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    file_put_contents($jsonPath, $json);

    $ok = true;


    #Si salta alguna excepcion, ok sera falso entonces mostrare el error desde el portal 
} catch (Throwable $e) {
    $_error_message = $e->getMessage();  #Actualizo el tipd de error
    $ok = false;


}


?>