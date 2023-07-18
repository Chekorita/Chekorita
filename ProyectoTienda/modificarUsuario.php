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
    //este if detecta si se manda como un post del boton de modificar
    if (isset($_POST['modifi'])) {
        //llamamos la configuracion de escritura
        include('./config/usuarioEscritura.php');
        //recuperamos los datos que se pueden modificar y el nombre y apellido para que podamos saber a cual se cambiara
        $nombre = $_POST['Nombre'];
        $apellido = $_POST['Apellido'];
        $direccion = $_POST['Direccion'];
        $contra = $_POST['Password'];
        $email = $_POST['Email'];
        $telefono = $_POST['Celular'];
        $tipo = $_POST['tipo'];
        //hacemos la consulta para actualizar, pero dependera de si se ha cambiado la contraseña o no
        if($contra == ""){
            $sql = "UPDATE usuario SET direccion='". $direccion ."', email='". $email ."', telefono='". $telefono . "', tipo='" . $tipo . "' WHERE nombreUsuario='". $nombre ."' and apellidosUsuario='". $apellido ."'";
        }else{
            $contrasena = password_hash($contra,PASSWORD_DEFAULT,['cost' => 4]);
            $sql = "UPDATE usuario SET direccion='". $direccion ."', email='". $email ."', telefono='". $telefono ."', contrasena='". $contrasena . "', tipo='" . $tipo ."' WHERE nombreUsuario='". $nombre ."' and apellidosUsuario='". $apellido ."'";
        }
        
        if ($conn->query($sql) === false) {
            die(mysqli_error($conn));
        }else{
            //en caso de concretarse lo mandamos de nuevo a la lista
            mysqli_close($conn);
            header('Location: ../listaUsuarios.php');
            exit;
        }
    }

    //este if detecta si se manda como un post del boton de eliminar
    if (isset($_POST['elimi'])){
        //llamamos la configuracion de escritura
        include('./config/usuarioEscritura.php');
        //recuperamos el nombre y apellido para que sepamos cual es el que eliminaremos
        $nombre = $_POST['Nombre'];
        $apellido = $_POST['Apellido'];

        //creamos la consulta para eliminar
        $sql = "DELETE from usuario WHERE nombreUsuario='". $nombre ."' and apellidosUsuario='". $apellido ."'";
        if ($conn->query($sql) === false) {
            die(mysqli_error($conn));
        }else{
            //en caso de concretarse lo mandamos de nuevo a la lista
            mysqli_close($conn);
            header('Location: ../listaUsuarios.php');
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
        <title>Modificar usuario</title>
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
            <a id="regresarInicio" href="../listaUsuarios.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                </svg>
            </a>
        </div>
        <div id="contenedor">
            <div id="contenedorcentrado">
                <form id="registrar" method="POST">
                    <?php
                        //para poner un relleno primero debemos ver si se mando por medio de la pagina anterior con el metodo post
                        //Si no hay post se manda un mensaje de que no hay datos por modificar, pero si o hay entramos a poner el formulario
	                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            //Llamamos la configuracion de lectura
                            include('./config/usuarioLectura.php');
                            //y hacemos la busqueda del usuario exacto con el id que se mando con el metodo post
                            $consulta = "SELECT * FROM usuario WHERE idUsuario='". $_POST['id']."'";
                            $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                            $columna = mysqli_fetch_array($resultado);
                    ?>
                    <h1>Modificar usuario</h1>
                    <hr>
                    <h4>Modifica la información personal</h4>
                    <label for="Nombre" class="form-label">Nombre del usuario: </label>
                    <input id="Nombre" type="text" name="Nombre" required value="<?php echo $columna['nombreUsuario']; ?>" readonly>
                    <label for="Apellido" class="form-label">Apellidos del usuario: </label>
                    <input id="Apellido" type="text" name="Apellido" required value="<?php echo $columna['apellidosUsuario']; ?>" readonly>
                    <label for="Direccion" class="form-label">Dirección del usuario: </label>
                    <input id="Direccion" type="text" name="Direccion" required value="<?php echo $columna['direccion']; ?>">
                    <label for="Celular" class="form-label">Numero de celular del usuario: </label>
                    <input id="Celular" type="text" name="Celular" maxlength="10" required onKeyPress="return SoloNumeros(event);" value="<?php echo $columna['telefono']; ?>">
                    <label for="Email" class="form-label">Correo del usuario: </label>
                    <input id="Email" type="email" name="Email" required value="<?php echo $columna['email']; ?>">
                    <hr>
                    <h4>Por ultimo la información de inicio de sesión</h4>
                    <label for="Username" class="form-label">Nombre de inicio de sesión: </label>
                    <input id="Username" type="text" name="Username" required value="<?php echo $columna['username']; ?>" readonly>
                    <label for="Aviso" class="form-label">Si no la vas a cambiar dejalo vacio</label>
                    <label for="Password" class="form-label">Ingresa una nueva contraseña: </label>
                    <input id="Password" type="text" name="Password" value="">
                    <hr>
                    <fieldset>
                        <legend>Elije si es un usuario normal o administrador</legend>
                        <?php
                            //Este if es para decidir cual aparecera seleccionado
                            if($columna['tipo']=="Administrador"){
                        ?>
                        <label>
                            Administrador
                            <input type="radio" name="tipo" value="Administrador" required="required" checked>
                        </label>
                        <label>
                            Normal
                            <input type="radio" name="tipo" value="Normal" required="required">
                        </label>
                        <?php
                            }else{
                        ?>
                        <label>
                            Administrador
                            <input type="radio" name="tipo" value="Administrador" required="required">
                        </label>
                        <label>
                            Normal
                            <input type="radio" name="tipo" value="Normal" required="required" checked>
                        </label>
                        <?php      
                            }
                        ?>
                    </fieldset>
                    <hr>
                    <button type="submit" name="modifi" value="modifi">Modificar</button>
                    <button type="submit" name="elimi" value="elimi">Eliminar</button>
                    <?php
	                    }else{
		                    echo "<h1>No hay nada que modificar</h1>";
	                    }
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>

<?php
//Cierre del else principal
}
?>