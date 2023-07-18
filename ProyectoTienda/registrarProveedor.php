<?php
//debemos iniciar la sesion
session_start();
//debemos ver que que si hayamos iniciado sesion en nuestra pagina anterior, revisando que $_SESSION este iniciado
if(!isset($_SESSION['nombre'])){
    //En caso contrario deberemos regresar de nuevo al index.php para que inicien sesión
    header('Location: ../index.php');
    exit;
} else {
    //En caso contrario primero debemos recuperar la hora actual
    $ahora= date("Y-n-j H:i:s");
    //Vamos a saber cuanto tiempo ha pasado, debemos restarle el tiempo que recuperamos ahora, al que se establecio cuando se inicio sesion
    $tiempotranscurrido = (strtotime($ahora)-strtotime($_SESSION["ultimoAcceso"]));
    //En este if, revisamos si el tiempo transcurrido es mayor a 900 segundos (15 minutos)
    if ($tiempotranscurrido > 900 ){
        //En caso de que uno de esos dos se cumpla mandamos a llamar a cerrarSesión.php
        header('Location: ../config/cerrarSesion.php');
        exit;
    }else{
        //En caso de no cumplirse solo actualizamos el la hora de la sesion
        $_SESSION["ultimoAcceso"]=$ahora;
    }


    if (isset($_POST['agregarProveedor'])){
        //Se llama la configuración del usuario de lectura
        include('./config/usuarioLectura.php');
        //ponemos en variables todos los datos que nos mandarón
        $nombre = $_POST['Nombre'];
        $email = $_POST['Email'];
        $telefono = $_POST['Celular'];
        $direccion = $_POST['Direccion'];
        //recuperamos la hora para ingresarlo en la bitacora y creamos la descripción
        $fechaactual = date("Y-n-j H:i:s");
        $descripcion = "Se ha agregado al proveedor: " . $nombre . " con la dirección: " . $direccion . " y las formas de contacto: " . $email . " / " . $telefono;

        //Consulta, con esta recuperamos resultados que sean similares al proveedor que ingresamos
        $consulta1 = "SELECT nombreProveedor FROM proveedor WHERE nombreProveedor='". $nombre ."'";
        $resultado1 = mysqli_query( $conexion, $consulta1 ) or die ( "Algo ha ido mal en la consulta a la base de datos");


        //si hay mas de 0 mandara un mensaje de error
        if(mysqli_num_rows($resultado1)>0){
            echo '<p class="error">Este proveedor ya esta registrado</p>';
        }else{
            //Se llama la configuración del usuario de escritura
            include('./config/usuarioEscritura.php');
            //Hacemos la insercion, con los datos recuperados para registrar el proveedor
            $sql1 = "INSERT INTO proveedor (nombreProveedor, email, telefono, direccion) VALUES ('". $nombre ."', '" . $email . "', '" . $telefono . "', '" . $direccion . "')"; 
            if (($result = mysqli_query($conn, $sql1)) === false) {
                die(mysqli_error($conn));
            }
            //hacemos la insercion con los datos para hacer la bitacora
            $sql2 = "INSERT INTO bitaagregapro (fecha, nombreUsuario, descripcion) VALUES ('". $fechaactual ."', '" . $_SESSION['nombre'] . "', '" . $descripcion . "')"; 
            if (($result = mysqli_query($conn, $sql2)) === false) {
                die(mysqli_error($conn));
            }
            mysqli_close($conn);
            //mandamos de nuevo al inicio para que puedan hacer otra cosa en caso de ser necesario
            header('Location: ../inicio.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <title>Registrar proveedor</title>
        <link rel="stylesheet" type="text/css" href=../css/estilosRegistrar.css>
        <script src="../js/funciones.js"></script>
    </head>
    <body>
        <nav>
            <ul class="menu">
                <li class="logo"><a href="../inicio.php">Inicio</a></li>
                <li class="item button secondary"><a href="../config/cerrarSesion.php">Cerrar sesión</a></li>
            </ul>
        </nav>

        <div id="regreso">
            <a id="regresarInicio" href="../inicio.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                </svg>
            </a>
        </div>
        <div id="contenedor">
            <div id="contenedorcentrado">
                <form id="registrar" method="POST">
                    <h1>Añadir nuevo proveedor</h1>
                    <hr>
                    <h4>Ingresa la información solicitada</h4>
                    <label for="Nombre" class="form-label">Nombre del proveedor: </label>
                    <input id="Nombre" type="text" name="Nombre" required onKeyPress="return SoloLetras(event);">
                    <label for="Direccion" class="form-label">Dirección del proveedor: </label>
                    <input id="Direccion" type="text" name="Direccion" required>
                    <label for="Celular" class="form-label">Telefono del proveedor: </label>
                    <input id="Celular" type="text" name="Celular" maxlength="10" required onKeyPress="return SoloNumeros(event);">
                    <label for="Email" class="form-label">Correo del usuario: </label>
                    <input id="Email" type="email" name="Email" required>
                    <hr>
                    <button type="submit" name="agregarProveedor" value="agregarProveedor">Agregar proveedor</button>
            </form>
            </div>
        </div>
    </body>
</html>

<?php
//Cierre del else principal
}
?>