<?php
    //Se llama la configuración del usuario de lectura
    include('./config/usuarioLectura.php');
    //Este if lee si se ya mandamos uns solicitud post
    if (isset($_POST['login'])) {
        $username = $_POST['usuario'];
        $password = $_POST['contrasena'];
        //debemos recuperar los usuarios para saber los usuarios que hay y sus contraseñas
        $consulta = "SELECT username, contrasena, tipo FROM usuario";
        $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
        //este while hara que se puedan recorrer todos los registros recuperados
        while($columna = mysqli_fetch_array($resultado)){
            //nos aseguramos de que el usuario que escribimos exista
            if($username == $columna['username']){
                //en caso de existir debemos ver si concuerda con la contrseña
                //debemos usar el metodo password_verify por como se encriptan las contraseñas, asi comparamos la contraseña recuperada con la ingresada
                if(password_verify($password, $columna['contrasena'])){
                    //si concuerda primero mandamos los datos para registarlos en la tabla de bitainisesion
                    //por ello recuperamos primero la fecha
                    $fechaactual = date("Y-n-j H:i:s");
                    //Se llama la configuración del usuario de escritura
                    include('./config/usuarioEscritura.php');
                    //hacemos el ingreso de los datos
                    $sql = "INSERT INTO bitainisesion (fecha, nombreUsuario) VALUES ('". $fechaactual ."', '" . $username . "')"; 
                    if (($result = mysqli_query($conn, $sql)) === false) {
                        die(mysqli_error($conn));
                    }
                    mysqli_close($conn);
                    //habilitamos la sesio
                    session_start();
                    //a la variable global de $_SESSION ingresmos datos como que usuario ingreso, si esta autentificado, y la fecha de acceso
                    $_SESSION['nombre'] = $username;
                    $_SESSION['tipo'] = $columna['tipo'];
                    $_SESSION["ultimoAcceso"]= date("Y-n-j H:i:s");
                    //mandamos a la pagina de inicio
                    header('Location: ../inicio.php');
                    //cerramos esta pagina para que no consuma recursos
                    exit;
                }else{
                    echo '<p class="error">Contraseña incorrecta</p>';
                }
            }else{
                echo '<p class="error">Usuario no encontrado</p>';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Inicio de sesión</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href=../css/estilosIndex.css>
    <script src="../js/funciones.js"></script>
</head>
<body>
    <div id="contenedor">
        <div id="contenedorcentrado">
            <div id="login">
                <form method="post" action="" id="loginform">
                    <label>Usuario</label>
                    <!– El onKeyPress es para poder llamar las funciones de javascript para evitar que se metan caracteres que no sean letras –>
                    <input type="text" name="usuario" onKeyPress="return SoloLetras(event);" required />

                    <label>Contraseña</label>
                    <input type="password" name="contrasena" required /> <br>

                    <button type="submit" name="login" value="login">Iniciar Sesión</button>
                </form>
            </div>
            <div id="derecho">
                    <div class="titulo">
                        Bienvenido
                    </div>
                    <hr>
                    <div class="pie-form">
                        <p>Ingresa los datos de inicio de sesión</p>
                    </div>
                    <hr>
                </div>
        </div>
    </div>
</body>
</html>
