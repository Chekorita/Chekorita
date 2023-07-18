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

    //if que revisa si se manda a llamar el metodo post para agregar
    if (isset($_POST['agregarUsuario'])){
        //Se llama la configuración del usuario de lectura
        include('./config/usuarioLectura.php');
        //ponemos en variables todos los datos que nos mandarón
        $nombre = $_POST['Nombre'];
        $apellido = $_POST['Apellido'];
        $nomusuario = $_POST['Username'];
        //la contraseña la vamos a cifrar con el metodo password_hash
        //este metodo tiene que recibir 3 cosas, primero la contraseña que se va a cifrar
        //El siguiente es elegir el metodo, aqui es PASSWORD_DEFAULT elige la opcion mas segura que sienta el propio PHP
        //Lo ultimo es el costo, el costo se refiere a cuantas veces va a hacer la operación, pero entre mas vueltas mas tarda en hacer la operación tener cuidado con la cantidad
        $contrasena = password_hash($_POST['Password'],PASSWORD_DEFAULT,['cost' => 4]);
        $email = $_POST['Email'];
        $telefono = $_POST['Celular'];
        $direccion = $_POST['Direccion'];
        $tipo = $_POST['tipo'];

        //primera consulta, con esta recuperamos resultados que sean similares al usuario que ingresamos
        $consulta1 = "SELECT username FROM usuario WHERE username='". $nomusuario ."'";
        $resultado1 = mysqli_query( $conexion, $consulta1 ) or die ( "Algo ha ido mal en la consulta a la base de datos");
        //la segunda consulta recuperamos que la persona que estamos registrando no se haya registrado anteriormente
        $consulta2 = "SELECT nombreUsuario, apellidosUsuario  FROM usuario WHERE nombreUsuario='". $nombre ."' AND apellidosUsuario='". $apellido ."'";
        $resultado2 = mysqli_query( $conexion, $consulta2 ) or die ( "Algo ha ido mal en la consulta a la base de datos");

        //vemos con este arbol de if si los resultados de la segunda consulta son mayores de 0, si lo son van a mandar la advertencia de que ya esta registrada
        if(mysqli_num_rows($resultado2)>0){
            echo '<p class="error">Esta persona ya esta registrada</p>';
        }else{
            //si no hay registros ahora revisa cuantos resultados hay en la primera consulta, si son mas de 0 mandara un mensaje de error
            if(mysqli_num_rows($resultado1)>0){
                echo '<p class="error">Este nombre de usuario ya esta en uso</p>';
            }else{
                //Se llama la configuración del usuario de escritura
                include('./config/usuarioEscritura.php');
                //Hacemos la insercion, con los datos recuperados
                $sql = "INSERT INTO usuario (nombreUsuario, apellidosUsuario, username, contrasena, email, telefono, direccion, tipo) VALUES ('". $nombre ."', '" . $apellido . "', '" . $nomusuario . "', '" . $contrasena. "', '" . $email . "', '" . $telefono . "', '" . $direccion . "', '" . $tipo . "')"; 
                if (($result = mysqli_query($conn, $sql)) === false) {
                    die(mysqli_error($conn));
                }
                mysqli_close($conn);
                //mandamos de nuevo al inicio para que puedan hacer otra cosa en caso de ser necesario
                header('Location: ../inicio.php');
                exit;
            }
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
        <title>Registrar usuario</title>
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
                    <h1>Añadir nuevo usuario</h1>
                    <hr>
                    <h4>Ingresa la información personal</h4>
                    <label for="Nombre" class="form-label">Nombre del usuario: </label>
                    <input id="Nombre" type="text" name="Nombre" required onKeyPress="return SoloLetras(event);">
                    <label for="Apellido" class="form-label">Apellidos del usuario: </label>
                    <input id="Apellido" type="text" name="Apellido" required onKeyPress="return SoloLetras(event);">
                    <label for="Direccion" class="form-label">Dirección del usuario: </label>
                    <input id="Direccion" type="text" name="Direccion" required>
                    <label for="Celular" class="form-label">Numero de celular del usuario: </label>
                    <input id="Celular" type="text" name="Celular" maxlength="10" required onKeyPress="return SoloNumeros(event);">
                    <label for="Email" class="form-label">Correo del usuario: </label>
                    <input id="Email" type="email" name="Email" required>
                    <hr>
                    <h4>Por ultimo la información de inicio de sesión</h4>
                    <label for="Username" class="form-label">Ingresa un nombre de usuario: </label>
                    <input id="Username" type="text" name="Username" required onKeyPress="return SoloLetras(event);">
                    <label for="Password" class="form-label">Ingresa una contraseña: </label>
                    <input id="Password" type="text" name="Password" required>
                    <hr>
                    <fieldset>
                        <legend>Elije si es un usuario normal o administrador</legend>
                        <label>
                            Administrador: 
                            <input type="radio" name="tipo" value="Administrador" required="required">
                        </label>
                        <label>
                            Normal:
                            <input type="radio" name="tipo" value="Normal" required="required"> 
                        </label>
                        <br>
                    </fieldset>
                    <hr>
                    <button type="submit" name="agregarUsuario" value="agregarUsuario">Agregar usuario</button>
                </form>  
            </div>
        </div>
    </body>
</html>

<?php
//Cierre del else principal
}
?>