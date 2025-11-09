 <?php
    ini_set('display_errors',1);
    $ok = false;

    try{

    //Saco los valores de la peticion
    $login=trim($_POST['login'] ?? '');
    $password = trim($_POST['passwd'] ?? '');


    // Conecto con la base de datos
    require_once __DIR__ . '/db.php';


    //Compruebo si tiene cuenta, y si es asi que coincida la constraseña
    $stmt = $pdo->prepare("SELECT login, passwd, rol FROM usuarios WHERE login = ?");
    $stmt->execute(array($login));
    //Utilizo fetch solo porque solo quiero una fila
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si devuelve algo, ese usuario ya está registrado
    //Compurebo la constraseña entonces
    if ($row) {
        if($password === $row['passwd']){
            //Si es correcto, guardo en session su autenrificacion y su rol
                $_SESSION["autentificado"] = $row['login'];
                $_SESSION["rol"] = $row['rol'];
                $ok = true;
        }
        else{
            throw new RuntimeException("Contraseña incorrecta");
        }

    }
    else{
        throw new RuntimeException("Login invalido");
    }
}
catch( Throwable $e){
    $_error_message = $e->getMessage();
    $ok = false;
}



    ?>