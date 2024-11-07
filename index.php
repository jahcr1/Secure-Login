<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=0, initial-scale=1.0">
    <title>TGM Inc.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/estilos.css">
</head>

<body id="body-index">

    <div class="container">
        <div>
            <form class="position-absolute top-50 start-50 translate-middle" action="lobby.php" method="POST">
            <div class="form-group mb-2 row">
                    <label for="usuario" class="w-75 pb-1 sombra-blanca">Ingresá tu usuario:</label>
                    <div class="container">
                        <input type="text" class="form-control input-texto" name="usuario" placeholder="Usuario" value="<?php if(isset($usuario)) echo $usuario; ?>" required>
                    </div>
                </div>
                <div class="form-group mb-3 row">
                    <label for="pass" class="w-75 pb-1 sombra-blanca">Ingresá tu password:</label>
                    <div class="container">
                    <input type="password" class="form-control input-texto" id="pass" name="password"  required>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="check1" name="recordando">
                    <label class="form-check-label" for="check1">Recordarme</label>
                </div>
                <button type="submit" class="btn btn-secondary boton_pred w-50">Ingresar a TGM</button>
            </form>
        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>