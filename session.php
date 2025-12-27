<?php
session_start();


if (!isset($_SESSION["activo"])) {
    $_SESSION["activo"] = 1;
    $_SESSION["visitado"] = [];

    //Contador de visitas
    $_SESSION["visita"]=0;

    //Si no es la primera vez que entro
} else {
    
    //Aumento visitas
    $_SESSION["visita"]=1+$_SESSION["visita"];
    //Añado la url que visita
    $_SESSION['visitado'] [] = $_SERVER["HTTP_REFERER"];
}

   
?>