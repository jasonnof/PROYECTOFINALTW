<?php
/**
 * * Descripción: Controlador principal
 * *
 * * Descripción extensa: Iremos añadiendo cosas complejas en PHP.
 * *
 * * @author  Lola <dllido@uji.es> <fulanito@example.com>
 * * @copyright 2018 Lola
 * * @license http://www.fsf.org/licensing/licenses/gpl.txt GPL 2 or later
 * * @version 2
 * * Si la URL tiene este esquema http://xxxx/portal0?action=fregistro
 * * mostrara el formulario de registro. Si no hay nada la página principal.
 **/



#COMPROBAMOS SI HTTP REFERER TIENE LA DIRECCION DEL PORTAL

//Muestro la session
require_once __DIR__ . '/session.php';

<<<<<<< HEAD
// Muestra el panel de cookies si no se ha aceptado o rechazado aún
if (empty($_SESSION['cookies_policy'])) {
    require_once __DIR__ . '/partials/cookies.php';
}

=======
//Funcion para acceso a gestion de trabajdores
function requireAdmin(): void {
  if (($_SESSION['rol'] ?? '') !== 'admin') {
    setFlash('error', 'Acces no autorizado');
    header("Location: portal.php?action=home");
    exit;
  }
}


>>>>>>> 932218fea453d27dc119bdcef2019b77ca48f69b
ini_set('display_errors', 1); #Mostrar errores


//Control d'accés per Referer (, SOLO PERMITIMOS ACCESO DESDE DENTRO, NO DIRECTO, CON LAS ACCIONES

$action = (array_key_exists('action', $_REQUEST)) ? $_REQUEST["action"] : "home";
$central = 'home.php';                // vista por defecto

$allow_action = true;

//Hacemos el control solo cuando se pide una accion
#Si existe la accion y no viene de home.No tenemos en cuenta APIS
if (  isset($_REQUEST["action"]) && $action !== 'home') {
    #Si referer esta vacio o no existe, eapcio en balnco
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    // Si el referer esta vacio, es porque viene de fuera, bloqueo
    if ($referer === '') {
            $allow_action = false;
    }
    else{
        $ref = parse_url($referer); #Parseo la ruta para limpiar espacios
        $selfHost =  ($_SERVER['SERVER_NAME'] );
        $hostOk   = isset($ref['host']) && (strcasecmp($ref['host'], $selfHost) === 0);  #Si el referer tiene un host y es igual al nuestro, etnocnes saldra true y permitimso acceso
        #Asi restringimos a cualquiera de fuera del host
        $allow_action = $hostOk;

    }
        
} 


// Si no esta permitido,mostramos error y estabelcemos central en home.php
if (!$allow_action) {
    $error_msg = "Acceso directo no permitido";
    $_error_message = $error_msg; 
    $central = '/partials/home.php';
<<<<<<< HEAD
} else{
    switch ($action) {
=======
}


else{


    switch ($action) {


            //Endpoint para lista actividades
        case "api_actividades":
            require_once __DIR__ . "/lib/db.php";
            header('Content-Type: application/json; charset=utf-8');

            $rol = $_SESSION['rol'] ?? 'visitant';
            $canManage = ($rol === 'gestor'); 

            try { //El gestor ve todas las actividades, publicadas o no
                if ($canManage) {
                    $stmt = $pdo->query("
                        SELECT id, title, description, location, start_at, end_at, image_path, published, created_at
                        FROM activities
                        ORDER BY published DESC, id DESC
                    ");
                } else {
                    //Si soy visitante, solo veo las que estan publicadas
                    $stmt = $pdo->query("
                        SELECT id, title, description, location, start_at, end_at, image_path, published, created_at
                        FROM activities
                        WHERE published = 1
                        ORDER BY id DESC
                    ");
                }

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode([
                    "ok" => true,
                    "canManage" => $canManage,
                    "data" => $rows
                ], JSON_UNESCAPED_UNICODE);

            } catch (Throwable $e) {
                echo json_encode(["ok" => false, "error" => "Error leyendo actividades"]);
            }
            exit;

            //Endpoint para borrar acitividades
        case "api_actividades_borrar":
            require_once __DIR__ . "/lib/db.php";
            header('Content-Type: application/json; charset=utf-8');

            if (($_SESSION['rol'] ?? '') !== 'gestor') {
                echo json_encode(["ok" => false, "error" => "No autorizado"]);
                exit;
            }

            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(["ok" => false, "error" => "ID inválido"]);
                exit;
            }

            try {
                $stmt = $pdo->prepare("UPDATE activities SET published = 0 WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(["ok" => true]);
            } catch (Throwable $e) {
                echo json_encode(["ok" => false, "error" => "Error despublicando"]);
            }
            exit;

            //Endpoint para publicar actividades
        case "api_actividades_publicar":
            require_once __DIR__ . "/lib/db.php";
            header('Content-Type: application/json; charset=utf-8');

            if(($_SESSION['rol'] ?? '') !== 'gestor' ){
                echo json_encode(["ok" => false, "error" => "No autorizado"]);
                exit;
            }

            $id = (int) ($_POST['id'] ?? 0);
            if( $id <= 0){
                echo json_encode(["ok" => false, "error" => "ID invalido"]);
                exit;
            }

            try{
                $stmt = $pdo->prepare("UPDATE activities SET published = 1 WHERE id = ?");
                $stmt->execute(array($id));
                echo json_encode(["ok" => true]);

            }catch(Throwable $e){
                 echo json_encode(["ok" => false, "error" => "Error publicando"]);
            }
            exit;

            //Endpoint para la api de la galeria
        case "api_gallery_random":
            require_once __DIR__ . "/lib/db.php";
            header('Content-Type: application/json; charset=utf-8');

            try{
                 $stmt = $pdo->query("
            SELECT id, title, file_path
            FROM gallery_images
            WHERE active = 1
            ORDER BY RAND()
            LIMIT 1
            ");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                "ok" => true,
                "item" => $row ?: null
            ], JSON_UNESCAPED_UNICODE);

            } catch (Throwable $e) {
                echo json_encode(["ok" => false, "error" => "Error carregant galeria"]);
            }
        
            exit;
            

        //APIS PARA ASIDE, TIEMPO Y NOTICIAS
        case "api_weather":
            header('Content-Type: application/json; charset=utf-8');


            //Coordenadas por defecto.Castellon
            $lat = 39.986;   // Castelló aprox (cámbialo si quieres)
            $lon = -0.051;
            $tz  = "Europe/Madrid";

            $url = "https://api.open-meteo.com/v1/forecast"   //api gratuita del tiempo
         . "?latitude=" . urlencode((string)$lat)
         . "&longitude=" . urlencode((string)$lon)
         . "&current=" . urlencode("temperature_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m")
         . "&timezone=" . urlencode($tz);

             // fetch con timeout (file_get_contents)
            

            //Me bajo lo de la web
            $raw = file_get_contents($url, false);

            if ($raw === false) {
                echo json_encode(["ok" => false, "error" => "No se puede consultar el tiempo ahora mismo"], JSON_UNESCAPED_UNICODE);
                exit;
            }

            //Lo normalizo a arrays
            $data = json_decode($raw, true);
            if (!is_array($data) || empty($data['current'])) {
                echo json_encode(["ok" => false, "error" => "Respuesta de tiempo invalida"], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $out = [
                "ok" => true,
                "place" => "Castellón",
                "updated_at" => $data['current']['time'] ?? date("Y-m-d H:i:s"),
                "current" => [
                    "temp" => $data['current']['temperature_2m'] ?? null,
                    "feels" => $data['current']['apparent_temperature'] ?? null,
                    "wind" => $data['current']['wind_speed_10m'] ?? null,
                    "precip" => $data['current']['precipitation'] ?? null,
                    "code" => $data['current']['weather_code'] ?? null,
                ]
            ];

            //Subo el json al endpoint
            echo json_encode($out, JSON_UNESCAPED_UNICODE);
            exit;


        case "api_news":
             header('Content-Type: application/json; charset=utf-8');

            $rssUrl = "https://elpais.com/rss/elpais/portada.xml";

           

            $xmlStr = file_get_contents($rssUrl, false);
            if ($xmlStr === false) {
                echo json_encode(["ok" => false, "error" => "No se han podido cargar noticias ahora mismo"], JSON_UNESCAPED_UNICODE);
                exit;
            }


            libxml_use_internal_errors(true);
            //Me pasa a xml
            $xml = simplexml_load_string($xmlStr);
            if ($xml === false || empty($xml->channel->item)) {
                echo json_encode(["ok" => false, "error" => "RSS invalido"], JSON_UNESCAPED_UNICODE);
                exit;
            }

            //LOGICA PARA SACAR LAS 6 TOP NOTICIAS
            $items = [];
            $i = 0;
            foreach ($xml->channel->item as $item) {
                $items[] = [
                    "title" => (string)$item->title, //tiutlo noticia
                    "link"  => (string)$item->link,  //link noticia
                    "date"  => (string)$item->pubDate,  //Fecha publicacion
                ];
                $i++;
                if ($i >= 6) break; //Nos quedamos con las top 6 noticias
            }

            $out = ["ok" => true, "source" => "EL PAÍS (RSS)", "items" => $items];

            echo json_encode($out, JSON_UNESCAPED_UNICODE);
            exit;

>>>>>>> 932218fea453d27dc119bdcef2019b77ca48f69b
        case "registro":
            $central = "/partials/registro.php";
            break;

        case "registrarse":
            require_once __DIR__ ."/actions/registrarse.php";
            if (!empty($ok)) {
                // Vuelve al formulario o redirige a donde quieras
                $central = "/partials/registro.php";
            } else {
                $central = "/partials/registro.php"; // re-mostrar form con error
            }
            break;
        case "autentificar":
            require_once __DIR__ ."/actions/autentificar.php";
            //Si es correcto el login, lo mando a home
            if($ok === true){
                $central = "/partials/home.php";
            }
            else{
                $central = "/partials/form_login.php";
            }
            break;

        case "form_login":
            $central = "/partials/form_login.php";
            break;
        
        case "salida":
            require_once __DIR__ . '/actions/salida.php';
            header("Location: portal.php?action=home");
            exit; // con exit ya sobra break


        //RUTAS TRABAJADORES
        case "workers_list":
            requireAdmin();
            $central = "/partials/workers_list.php";
            break;
<<<<<<< HEAD
=======

        case "worker_new":
            requireAdmin();
            //Creo un user vacio para rellenar por defecto
            $user = ['id'=>0,'username'=>'','email'=>'','role'=>'gestor','active'=>1];
            $central = "/partials/worker_form.php";
            break;

        case "worker_create":
            requireAdmin();
            require_once __DIR__ . "/actions/worker_create.php";
            if(!empty($ok)){
                header("Location: portal.php?action=workers_list");
            }

            //Si falla, le vuelvo a mostrar el form
            $central = "/partials/worker_form.php";
            break;

        case "worker_edit":
            requireAdmin();
            $central = "/partials/worker_form.php";
            break;

        case "worker_update":
            requireAdmin();
           require_once __DIR__ . "/actions/worker_update.php";
            if (!empty($ok)) {
                header("Location: portal.php?action=workers_list");
                exit;
            }

            //Si falla, reenvio a edit pero pasandole el id en la url
            $id = (int)($_POST['id'] ?? 0);
            header("Location: portal.php?action=worker_edit&id=" . $id);
            exit;
                    
        case "worker_toggle":
            requireAdmin();
            require_once __DIR__ . "/actions/worker_toggle.php";
            header("Location: portal.php?action=workers_list");
            exit;
    

        //JUEGO CANVAS: PUNTUACION + APIS
        case "game":
            $central = "/partials/game.php";
            break;

        //Obtenemos los 10 mejores scores
        case "api_scores_top":
            require_once __DIR__ . "/lib/db.php";
            header('Content-Type: application/json; charset=utf-8');

            //Me toca hacer un join para saber el username que ha hecho el score
            try {
                $stmt = $pdo->query("
                    SELECT 
                    gs.id, gs.score, gs.nickname, gs.created_at,
                    u.username
                    FROM game_scores gs
                    LEFT JOIN users u ON u.id = gs.user_id
                    ORDER BY gs.score DESC, gs.created_at DESC
                    LIMIT 10
                ");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // normaliza nombre mostrado
                //Si no esta en la bd es visitante
                //Uso map para ejecutar la funcion sobre cada elemento del array
                $data = array_map(function($r){
                    $name = $r['username'] ?: ($r['nickname'] ?: 'Invitado');
                    return [
                        'name' => $name,
                        'score' => (int)$r['score'],
                        'created_at' => $r['created_at'],
                    ];
                }, $rows);

                echo json_encode(["ok"=>true, "data"=>$data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(["ok"=>false, "error"=>"Error llegint ranking"], JSON_UNESCAPED_UNICODE);
            }
            exit;

        case "api_score_submit":
            require_once __DIR__ . "/lib/db.php";
            header('Content-Type: application/json; charset=utf-8');

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(["ok"=>false, "error"=>"Mètode no permès"], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // "detalle pro": validación server-side para evitar trampas
            $score = (int)($_POST['score'] ?? -1);
            $duration = (int)($_POST['duration'] ?? 0); // opcional

            //Score
            if ($score < 0) $score = 0;
            if ($score > 3000) $score = 3000;

            //La duracion no puede ser mas de 60 segundos
            if ($duration < 0) $duration = 0;
            if ($duration > 60) $duration = 60;

            $userId = $_SESSION['user_id'] ?? null;
            $auth   = !empty($_SESSION['autentificado']);

            $nickname = trim($_POST['nickname'] ?? '');

            // si NO está logueado, nickname obligatorio
            if (!$auth) {
                if ($nickname === '') {
                    echo json_encode(["ok"=>false, "error"=>"Nickname obligatorio"], JSON_UNESCAPED_UNICODE);
                    exit;
                }
                // limpia nickname: letras/números/espacios/guion (3..20)
                $nickname = preg_replace('/[^a-zA-Z0-9 _-]/u', '', $nickname);
                $nickname = trim($nickname);
                if (mb_strlen($nickname) < 3 || mb_strlen($nickname) > 20) {
                    echo json_encode(["ok"=>false, "error"=>"Nickname 3-20 caracteres"], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                // si está logueado, usa su username como nombre 
                $nickname = null;
                $userId = (int)$userId;
            }

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO game_scores (user_id, nickname, score)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$auth ? $userId : null, $nickname, $score]);

                echo json_encode(["ok"=>true, "saved_score"=>$score], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(["ok"=>false, "error"=>"Error guardando score"], JSON_UNESCAPED_UNICODE);
            }
            exit;


        

>>>>>>> 932218fea453d27dc119bdcef2019b77ca48f69b
        case "home":
            $central = "/partials/home.php";
            break;
        case "form_register":
            $central = "/partials/registro.php";
            break;
        case "lgpd":
            $central = "/partials/lgpd.php";
            break;
        case "qui_som":
            $central = "/partials/qui_som.php";
            break;
        case "galeria":
            $central = "/api/galeria.php";
            break;
        case "cookies":
            $central = "/partials/cookies.php";
            break;
        case "cookies_reset":
        // Borra cookies (expíramos con tiempo negativo)
        setcookie("cookie_consent", "", time() - 3600, "/");
        setcookie("cookie_prefs", "", time() - 3600, "/");
        header("Location: portal.php?action=cookies");
        exit;


        //Me muestra las actividades con js 
        case "tablas":
            $central = "/partials/tablas.php";
            break;

            //Registrar una actividad
        case "form_activitat":
            if (($_SESSION['rol'] ?? '') !== 'gestor'){
                $_error_message = "Acceso no autorizado";
                $central = "/partials/home.php";
                break;
            }

            $central = "/partials/form_activitat.php";
            break;


        
        case "reg_acti":
              if (($_SESSION['rol'] ?? '') !== 'gestor'){
                $_error_message = "Acceso no autorizado";
                $central = "/partials/home.php";
                break;
              }
              require_once __DIR__ . "/actions/reg_acti.php";
              if (!empty($ok)) {
                    setFlash('ok',"Actividad registrada correctamente");
                    header("Location: portal.php?action=tablas");
                    exit;
              } else {
                $central = "/partials/form_activitat.php";
              }
            break;
        
        //Modificamos las actividades de lla db
        case "modificar":
            if (($_SESSION['rol'] ?? '') !== 'gestor') {
                $_error_message = "Acceso no autorizado";
                $central = "/partials/home.php";
                break;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once __DIR__ . "/actions/modificar_guardar.php";
                if (!empty($ok)) {
                    // si tienes flash, aquí sería perfecto
                    setFlash('ok', "Activitat modificada correctament."); //Seteo mensaje de exito para no perderlo
                    header("Location: portal.php?action=tablas");
                    exit;
                } else {
                    // re-mostrar form (necesita id en GET, lo pasamos)
                    $id = (int)($_POST['id'] ?? 0);
                    header("Location: portal.php?action=modificar&id=" . $id);
                    setFlash('error', $_error_message);  //Seteo el error con el que habia para no perderlo al cambiar el request

                    exit;
                }
            } else {
                $central = "/partials/form_modificar.php";
            }
            break;


        case "borrar":
            if(($_SESSION['rol'] ?? '') !== 'gestor'){
                $_error_message = "Acceso no autorizado";
                $central = "/actions/tablas.php";
                break;
            }

            require_once __DIR__ . "/actions/borrar.php";
            if(!empty($ok)){
                $_ok_message = "Actividad borrada";
            }
            $central = "/partials/tablas.php";
            break;



        default:
            #Ahora en el caso por defecto,es decir cuando no acceda a ninguna pagina, asigno el error(un String)
            #Fichero central es home
            $error_msg = "Acción no permitida";
            $central ="/partials/home.php";
            break;
    }
}


//Estructura pagina
require_once(dirname(__FILE__)."/partials/header.php");
require_once(dirname(__FILE__)."/partials/menu.php");

// Layout a 2 columnas: contenido + aside
echo '<div class="page-layout">';

// Columna principal
echo '<div id="page-content">';

// Flash (OK/ERROR)
require_once __DIR__ . "/partials/flash.php";

// Normaliza el nombre de la variable de error
if (!isset($error_msg) && isset($_error_message)) {
    $error_msg = $_error_message;
}

// Mostrar el bloque de error si hay mensaje
if (!empty($error_msg) || !empty($_error_message)) {
    require_once(dirname(__FILE__)."/partials/error.php");
}

// Contenido central
require_once(dirname(__FILE__).$central);

echo '</div>'; // /#page-content

// Columna derecha (aside)
require_once(dirname(__FILE__)."/partials/aside.php");

echo '</div>'; // /.page-layout

require_once(dirname(__FILE__)."/partials/footer.php");



?>