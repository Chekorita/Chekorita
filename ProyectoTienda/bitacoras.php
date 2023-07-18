<?php
//debemos iniciar la sesion
session_start();
//debemos ver que que si hayamos iniciado sesion en nuestra pagina anterior, revisando que $_SESSION este iniciado
if(!isset($_SESSION['nombre'])){
    //En caso contrario deberemos regresar de nuevo al index.php para que inicien sesión
    header('Location: ./index.php');
    exit;
} else {
    //En caso contrario primero debemos recuperar la hora actual
    $ahora= date("Y-n-j H:i:s");
    //Vamos a saber cuanto tiempo ha pasado, debemos restarle el tiempo que recuperamos ahora, al que se establecio cuando se inicio sesion
    $tiempotranscurrido = (strtotime($ahora)-strtotime($_SESSION["ultimoAcceso"]));
    //En este if, revisamos si el tiempo transcurrido es mayor a 900 segundos (15 minutos)
    if ($tiempotranscurrido > 900 ){
        //En caso de que uno de esos dos se cumpla mandamos a llamar a cerrarSesión.php
        header('Location: ./config/cerrarSesion.php');
        exit;
    }else{
        //En caso de no cumplirse solo actualizamos el la hora de la sesion
        $_SESSION["ultimoAcceso"]=$ahora;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <title>Bitacoras</title>
        <link rel="stylesheet" type="text/css" href=css/estilos.css>
    </head>
    <body>
        <nav>
            <ul class="menu">
                <li class="logo"><a href="../inicio.php">Inicio</a></li>
                <li class="item button secondary"><a href="../config/cerrarSesion.php">Cerrar sesión</a></li>
            </ul>
        </nav>
        <div class="c1">
            <div id="regreso">
                <a id="regresarInicio" href="../inicio.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                    </svg>
                </a>
            </div>
            <h2>Elige la bitacora que gustes imprimir</h2>
            <table  class="table table-striped" id="TablaOpciones">
                <thead>
                    <tr>
                        <th scope="col">Bitacora</th>
                        <th scope="col">Aquí</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Bitacora de inicios de sesión</td>
                        <td><a class="btn btn-outline-dark" role="button" href="./configBitacoras/bitacoraInicioSesion.php" id="btnBitaIniSesion" target="_blank" rel="noopener noreferrer">IR</a></td>
                    </tr>
                    <tr>
                        <td>Bitacora de de registro y modificaciones de proveedores</td>
                        <td><a class="btn btn-outline-dark" role="button" href="./configBitacoras/bitacoraAgreModiProveedor.php" id="btnBitaAgrePro" target="_blank" rel="noopener noreferrer">IR</a></td>
                    </tr>
                    <tr>
                        <td>Bitacora de de registro y modificaciones de inventario</td>
                        <td><a class="btn btn-outline-dark" role="button" href="./configBitacoras/bitacoraAgreModiInventario.php" id="btnBitaAgreInv" target="_blank" rel="noopener noreferrer">IR</a></td>
                    </tr>
                    <tr>
                        <td>Bitacora de bajas de proveedores</td>
                        <td><a class="btn btn-outline-dark" role="button" href="./configBitacoras/bitacoraBajaProveedor.php" id="btnBitaBajaPro" target="_blank" rel="noopener noreferrer">IR</a></td>
                    </tr>
                    <tr>
                        <td>Bitacora de bajas de inventario</td>
                        <td><a class="btn btn-outline-dark" role="button" href="./configBitacoras/bitacoraBajaInventario.php" id="btnBitaBajaInv" target="_blank" rel="noopener noreferrer">IR</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>

<?php
//Cierre del else principal
}
?>