<?php


//Dseativo y destruyo la sesion
session_unset();
session_destroy();

//Le inicio una nueva con rol de visitante
session_start();
session_regenerate_id(true);
$_SESSION['activo']   = 1;
$_SESSION['rol']      = 'visitant';
$_SESSION['visita']   = 0;      // contador vuelve a 0 como pide el enunciado
$_SESSION['visitado'] = [];


?>