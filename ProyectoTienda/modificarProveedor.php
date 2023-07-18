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
        //recuperamos los datos que se pueden modificar y el nombre para que podamos saber que a cual se cambiara
        $nombre = $_POST['Nombre'];
        $direccion = $_POST['Direccion'];
        $email = $_POST['Email'];
        $telefono = $_POST['Celular'];

        //recuperamos la hora para ingresarlo en la bitacora y creamos la descripción
        $fechaactual = date("Y-n-j H:i:s");
        $descripcion = "Se ha modificado al proveedor: " . $nombre . ". Los nuevos datos son: dirección: " . $direccion . " y las formas de contacto ahora son: " . $email . " / " . $telefono;

        //hacemos la consulta para actualizar, pero dependera de si se ha cambiado la contraseña o no
        $sql = "UPDATE proveedor SET direccion='". $direccion ."', email='". $email ."', telefono='". $telefono ."' WHERE nombreProveedor='". $nombre ."'";
        
        if ($conn->query($sql) === false) {
            die(mysqli_error($conn));
        }else{
            //ahora agregamos la nueva entrada en la bitacora que registre la modificacion
            $sql2 = "INSERT INTO bitaagregapro (fecha, nombreUsuario, descripcion) VALUES ('". $fechaactual ."', '" . $_SESSION['nombre'] . "', '" . $descripcion . "')"; 
            if (($result = mysqli_query($conn, $sql2)) === false) {
                die(mysqli_error($conn));
            }
            //en caso de concretarse lo mandamos de nuevo a la lista
            mysqli_close($conn);
            header('Location: ../listaProveedores.php');
            exit;
        }
    }

    //este if detecta si se manda como un post del boton de eliminar
    if (isset($_POST['elimi'])){
        //llamamos la configuracion de escritura
        include('./config/usuarioEscritura.php');
        //recuperamos el nombre y apellido para que sepamos cual es el que eliminaremos
        $nombre = $_POST['Nombre'];
        //en caso de que ya no haya productos con ese proveedor, se puede eliminar
        //creamos la consulta para eliminar
        $sql = "DELETE from proveedor WHERE nombreProveedor='". $nombre ."'";
        if ($conn->query($sql) === false) {
            die(mysqli_error($conn));
        }else{
            //en caso de concretarse, agregamos a la bitacora lo que se hizo con el proveedor
            $fechaactual = date("Y-n-j H:i:s");
            $descripcion = "Se ha dado de baja al proveedor: " . $nombre;

            $sql2 = "INSERT INTO bitabajapro (fecha, nombreUsuario, descripcion) VALUES ('". $fechaactual ."', '" . $_SESSION['nombre'] . "', '" . $descripcion . "')"; 
            if (($result = mysqli_query($conn, $sql2)) === false) {
                die(mysqli_error($conn));
            }
            //al finalizar lo mandamos a la lista de nuevo
            mysqli_close($conn);
            header('Location: ../listaProveedores.php');
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
        <title>Modificar proveedor</title>
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
            <a id="regresarInicio" href="../listaProveedores.php">
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
                            $consulta = "SELECT * FROM proveedor WHERE idProveedor='". $_POST['id']."'";
                            $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                            $columna = mysqli_fetch_array($resultado);
                    ?>

                    <h1>Modificar al proveedor</h1>
                    <hr>
                    <h4>Ingresa la información a modificar</h4>
                    <label for="Nombre" class="form-label">Nombre del proveedor: </label>
                    <input id="Nombre" type="text" name="Nombre" required value="<?php echo $columna['nombreProveedor']; ?>" readonly>
                    <label for="Direccion" class="form-label">Dirección del proveedor: </label>
                    <input id="Direccion" type="text" name="Direccion" required value="<?php echo $columna['direccion']; ?>">
                    <label for="Celular" class="form-label">Telefono del proveedor: </label>
                    <input id="Celular" type="text" name="Celular" maxlength="10" required onKeyPress="return SoloNumeros(event);" value="<?php echo $columna['telefono']; ?>">
                    <label for="Email" class="form-label">Correo del usuario: </label>
                    <input id="Email" type="email" name="Email" required value="<?php echo $columna['email']; ?>">
                    <hr>
                    <button type="submit" name="modifi" value="modifi">Modificar</button>
                    <?php
                        //si deseamos eliminarlo debemos ver que no haya productos que lo tengan como proveedor
                        $consulta1 = "SELECT * FROM producto WHERE nombreProveedor='". $columna['nombreProveedor'] ."'";
                        $resultado1 = mysqli_query( $conexion, $consulta1 ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                        if(mysqli_num_rows($resultado1)>0){
                    ?>
                    <button type="submit" name="elimi" value="elimi" disabled>Eliminar</button>
                    <?php
                        }else{
                    ?>
                    <button type="submit" name="elimi" value="elimi">Eliminar</button>
                    <?php
                        }
                    ?>
                
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