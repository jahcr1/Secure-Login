<?php 

function isNull($nombre, $usuario, $password, $con_password, $email,$telefono) {

    if(strlen(trim($nombre)) < 1 || strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1 || strlen(trim($con_password)) < 1 || strlen(trim($email)) < 1 || strlen(trim($telefono)) < 1 ) 
    {
        return true;
    } else {
        return false;
    }
}

function isEmail($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function validaPassword ($var1, $var2){
    if(strcmp($var1, $var2) !== 0){
        return false;
    } else {
        return true;
    }
}

function usuarioExiste ($usuario) {
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
    
    if ($stmt === false) {
        die("Error en la preparación: " . $mysqli->error);
    }

    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();
    $num = $stmt->num_rows;
    $stmt->close();

    if($num > 0){
        return true;
    } else {
        return false;
    }

}

function emailExiste ($email) {
    global $mysqli;
    
    // esto es una SENTENCIA PREPARADA, leer bien!

    $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $num = $stmt->num_rows;
    $stmt->close();

    if($num > 0){
        return true;
    } else {
        return false;
    }
}

/* funciones para generar token y password cifrada */

/* aca creamos una variable gen que almacene datos cifrados a md5 que creamos con la funcion uniqid, donde le pasamos otra funcion mt_rand q crea un identificador random en base al tiempo actual y un false q no se que hace */
function generateToken() {
    $gen = md5(uniqid(mt_rand(), false));
    return $gen;
}


/* aca creamos la funcion hashPassword para cifrar la pw que le pasamos por argumento, luego creamos una variable $hash que almacene lo q devuelve la funcion password_hash */
/* metodo password_hash() */
function hashPassword ($password) {
$hash = password_hash($password, PASSWORD_DEFAULT);
return $hash;
}



function registraUsuario($usuario, $pass_hash, $nombre, $email, $telefono, $activo, $token, $tipo_usuario) 
{
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, telefono, activacion, token, id_tipo) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssiisi', $usuario, $pass_hash, $nombre, $email, $telefono, $activo, $token, $tipo_usuario);
    
    if($stmt->execute()) {
        return $mysqli->insert_id;
    } else {
        return 0;
    }
}


/* funcion de enviarCorreo de activacion con PHPMailer */

function enviarEmail($email, $nombre, $asunto, $cuerpo) {
    require_once 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure ='tipo de seguridad';
    $mail->Host ='smtp.hosting.com';
    $mail->Port ='puerto';

    $mail->Username = 'miemail@dominio.com';
    $mail->Password = 'password';

    $mail->setFrom('miemail@dominio.com', 'Sistema de Usuarios');
    $mail->addAdress($email, $nombre);

    $mail->Subject = $asunto;
    $mail->Body = $cuerpo;
    $mail->IsHTML(true);

    if($mail->send())
    return true;
    else
    return false;

}


?>