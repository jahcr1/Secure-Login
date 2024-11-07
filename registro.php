<?php 
include 'componentes/conexion.php';
include 'componentes/funcs.php';

$errors = array();

if (!empty($_POST)) {

    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $usuario = $mysqli->real_escape_string($_POST['usuario']);
    $password = $mysqli->real_escape_string($_POST['pass']);
    $con_password = $mysqli->real_escape_string($_POST['con_password']);
    $email = $mysqli->real_escape_string($_POST['correo']);
    $telefono = $mysqli->real_escape_string($_POST['tel']);
    //$captcha = $mysqli->real_escape_string($_POST['g-recaptcha-response']);

    $activo = 0;
    $tipo_usuario = 2;
    $secret = '';

    // if(!$captcha) {
    //     $errors[] = "Por favor verifica el captcha";

    // }

    if(isNull($nombre, $usuario, $password, $con_password, $email, $telefono)) {
        $errors[] = "Debe llenar todos los campos.";
    }

    if(!isEmail($email)) {
        $errors[] = "Dirección de correo invalido.";
    }

    if(!validaPassword($password, $con_password)) {
        $errors[] = "Las contraseñas no coinciden.";
    }
    if(usuarioExiste($usuario)) {
        $errors[] = "El nombre del usuario $usuario ya existe.";
    }
    if(emailExiste($email)) {
        $errors[] = "El correo electronico $email ya existe.";
    }

    if(count($errors) == 0) {

        /* Aca crea una variable de respuesta o sea un json del captcha
        usa la variable secret creada la variable a definir q es response
        */

        /*
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&$response=$captcha");


        /* creamos una variable donde almacenamos el json de google y probamos si los datos que devolvió son correctos y coinciden con el captcha, si es asi empieza a cifrar la password x seguridad y empieza el registro en la BD */

        /*
        $arr = json_decode($response, TRUE);
        */

        if($arr['success']) {
            
            /* guardamos en una variable pass_hash la pw encriptada y el token generado*/

            
            $pass_hash = hashPassword($password);
            $token = generateToken();

            $registro = registraUsuario($usuario, $pass_hash, $nombre, $email, $telefono, $activo, $token, $tipo_usuario); 

            if($registro > 0) {
                
                $url = 'https://'.$_SERVER["SERVER_NAME"].'login/activar.php?id='.$registro.'&val='.$token;

                $Asunto = 'Activar cuenta - Sistema de Usuarios';
                $cuerpo = "Estimado $nombre: <br /><br />Para ACTIVAR LA CUENTA en TGM, hacele click al enlace y no la cuelgues <a href='$url'>Activar Cuenta</a>";

            if(enviarEmail($email, $nombre, $asunto, $cuerpo)) {
                echo "Para terminar con el proceso registro, seguí las instrucciones que te dejo en el correo: $email";

                echo "<br><a href='index.php'>Iniciar Sesion</a>";
                exit;

            } else{
                $errors[] = "Error al enviar el Email";
            }



            } else {
                $errors[] = "Error al registrar";
            }
        } else {
            $errors[] = "Error al comprobar el captcha";
        }


    }

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=0, initial-scale=1.0">
    <title>Registro TGM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/estilos.css">
</head>

<body class="reg">

    <div class="container">
        <div class="mainbox col-xl-6 col-md-8 col-md-offset-3 col-sm-8 col-sm-offset-2 mx-auto border-info rounded" id="signupbox" style="margin-top: 50px; background: white; ">
        <div class="panel panel-info">
            <div class="panel-encabezado px-3 pt-2">
                <div class="panel-titulo fs-5">Registrate</div>
                <div style="float:right; position:relative; top:-10px; "><a href="index.php" id="signinlink">Iniciar Sesión</a></div>
            </div>
            
            <div class="panel-body">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" class="form-horizontal" id="signupform" autocomplete="off">
                <!-- aca dsp va un div con un alert en caso de error--> 
                
                <div class="form-group">
                    <label for="nombre" class="col-md-3 control-label">Nombre:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control input-texto" name="nombre" placeholder="Nombre" value="<?php if(isset($nombre)) echo $nombre; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="usuario" class="col-md-3 control-label">Usuario:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control input-texto" name="usuario" placeholder="Usuario" value="<?php if(isset($usuario)) echo $usuario; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pass" class="col-md-3 control-label">Password:</label>
                    <div class="col-md-9">
                        <input type="password" class="form-control input-texto" name="pass" placeholder="Ingrese un password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="con_password" class="col-md-3 control-label">Ingrese nuevamente su contraseña:</label>
                    <div class="col-md-9">
                        <input type="password" class="form-control input-texto" name="con_password" placeholder="Ingrese de nuevo su password"  required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="correo" class="col-md-3 control-label">Correo electrónico:</label>
                    <div class="col-md-9">
                        <input type="email" class="form-control input-texto" name="correo" placeholder="chimichangas@hotmail.com" value="<?php if(isset($email)) echo $email; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tel" class="col-md-3 control-label">Teléfono:</label>
                    <div class="col-md-9">
                        <input type="tel" class="form-control" name="tel" placeholder="Ingresá tu celular" value="<?php if(isset($telefono)) echo $telefono; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-3 col-md-9">
                        <button id="btn-signup" type="submit" class="btn btn-secondary btn-lg  mt-3 p-2" style="width: 150px;"><i class="icon-hand-right"></i>Registrar</button>
                    </div>
                </div>

                </form>
            </div>
        </div>
        

        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>