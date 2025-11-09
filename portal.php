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

ini_set('display_errors', 1); #Mostrar errores


//Control d'accés per Referer (, SOLO PERMITIMOS ACCESO DESDE DENTRO, NO DIRECTO, CON LAS ACCIONES

$action = (array_key_exists('action', $_REQUEST)) ? $_REQUEST["action"] : "home";
$central = 'home.php';                // vista por defecto

$allow_action = true;

//Hacemos el control solo cuando se pide una accion
#Si existe la accion y no viene de home
if (isset($_REQUEST["action"]) && $action !== 'home') {
    #Si referer esta vacio o no existe, eapcio en balnco
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    // Si el referer esta vacio, es porque viene de fuera, bloqueo
    if ($referer === '') {
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
}


else{


    


    switch ($action) {

        case "registro":
            $central = "/partials/registro.php";
            break;

        case "registrarse":
            require_once __DIR__ ."/partials/registrarse.php";
            if (!empty($ok)) {
                // Vuelve al formulario o redirige a donde quieras
                $central = "/partials/registro.php";
            } else {
                $central = "/partials/registro.php"; // re-mostrar form con error
            }
            break;
        case "autentificar":
            require_once __DIR__ ."/partials/autentificar.php";
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
            require_once __DIR__ . '/partials/salida.php';
            $central = '/partials/home.php'; 
            break;

        

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
            $central = "/partials/galeria.php";
            break;
        case "tablas":
            $central = "/partials/tablas.php";
            break;
        case "form_activitat":
            $central = "/partials/form_activitat.php";
            break;

        //Si es gestor, le redirigo a un listar especial
        case "list":
            if($_SESSION['rol'] == 'gestor'){
                $central = "/partials/listgestor.php";
            }
            else{
            $central = "/partials/llistat.php";
            }
            break;

        case "modificar":
            //Compruebo por si acaso que sea gestor
            if(empty($_SESSION['rol']) or $_SESSION['rol'] !== 'gestor'){
                $_error_message = "Acceso no autorizado";
                $central = "/partials/llistat.php";
                break;
            }
             if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Llamo al fichero con la logica para guardar
                require_once __DIR__ . "/partials/modificar_guardar.php";
                //SI ok queda vacio,todo bien sin errores
                if(!empty($ok)){
                    $_ok_message = "Actividad modificada correctamente";
                    $central = "/partials/listgestor.php";
                    break;
                }else{
                    //Si hau error le remeustro el form
                    $central = "/partials/form_modificar.php";
                    
                }
        } else{
            //Si la peticion es get, es decir solomostrar el form, lo muestro y ya
                $central = "/partials/form_modificar.php";
        }
            break;

        case "borrar":
            if(empty($_SESSION['rol']) or $_SESSION['rol'] !== 'gestor'){
                $_error_message = "Acceso no autorizado";
                $central = "/partials/llistat.php";
                break;
            }

            require_once __DIR__ . "/partials/borrar.php";
            if(!empty($ok)){
                $_ok_message = "Actividad borrada";
            }
            $central = "/partials/listgestor.php";
            break;


        case "reg_acti":
            require_once(dirname(__FILE__) ."/partials/reg_acti.php");
            if (isset($ok) && $ok=== true) {
                $central = "/partials/llistat.php";  //vacio con titulo
            }
            else{
                #Si hay error, redirecciono al formulario para que lo vuelva a rellenar
                if(!isset($_error_message)) {
                    $_error_message = "Error no especificado"; 
                 }  
                $central = "/partials/form_activitat.php";
                }
            break;
        case "form_foto":
            $central = "/partials/form_foto.php";
            break;

        case "foto_upload":
            //Procesamos subida
            require_once(dirname(__FILE__)."/partials/foto_upload.php");
            
            //Vuelvo a mostrar el formulario y muestro errores si los hay
            $central = "/partials/form_foto.php";
            break;

        case "listar_acti":
            $central = "/partials/llistat.php";
            break;

        default:
            #Ahora en el caso por defecto,es decir cuando no acceda a ninguna pagina, asigno el error(un String)
            #Fichero central es home
            $error_msg = "Acción no permitida";
            $central ="/partials/home.php";
            break;
    }
}


require_once(dirname(__FILE__)."/partials/header.php");
require_once(dirname(__FILE__)."/partials/menu.php");

// Normaliza el nombre de la variable de error
if (!isset($error_msg) && isset($_error_message)) {
    $error_msg = $_error_message;
}

// Mostrar el bloque de error si hay mensaje (use el que use)
if (!empty($error_msg) || !empty($_error_message)) {
    require_once(dirname(__FILE__)."/partials/error.php");
}

require_once(dirname(__FILE__).$central);

// NUEVO: aside visible en todas las páginas
require_once(dirname(__FILE__)."/partials/aside.php");
require_once(dirname(__FILE__)."/partials/footer.php");





?>