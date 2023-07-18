<?php
//debemos iniciar la sesion
session_start();
//debemos ver que que si hayamos iniciado sesion en nuestra pagina anterior, revisando que $_SESSION este iniciado
if(!isset($_SESSION['nombre'])){
  //En caso contrario deberemos regresar de nuevo al index.php para que inicien sesión
  header('Location: ../index.php');
  exit;
}else {
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
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <title>Inicio</title>
        <link rel="stylesheet" type="text/css" href=../css/estilos.css>
    </head>
    <body>
      <nav>
        <ul class="menu">
          <li class="logo"><a href="../inicio.php">Inicio</a></li>
          <li class="item button secondary"><a href="../config/cerrarSesion.php">Cerrar sesión</a></li>
        </ul>
      </nav>
      
      <div id="c1">
            <h2>Selecciona lo que deseas hacer</h2>
            <table class="table table-striped" id="TablaOpciones">
              <thead>
                <tr>
                  <th scope="col">Tarea</th>
                  <th scope="col">Aquí</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  if($_SESSION['tipo']=="Administrador"){
                    echo "<tr>";
                      echo "<td>Registrar usuario</td>";
                      echo "<td><a class='btn btn-outline-dark' role='button' href='../registrarUsuario.php' id='btnRegistrarUsuario'>IR</a></td>";
                    echo "</tr>";
                    echo "<tr>";
                      echo "<td>Consultar usuario</td>";
                      echo "<td><a class='btn btn-outline-dark' role='button' href='../listaUsuarios.php' id='btnUsuarios'>IR</a></td>";
                    echo "</tr>";
                  }
                ?>
                <tr>
                  <td>Registrar nuevo producto</td>
                  <td><a class="btn btn-outline-dark" role="button" href="../registrarProducto.php" id="btnRegistrarProdcuto">IR</a></td>
                </tr>
                <tr>
                  <td>Consultar inventario</td>
                  <td><a class="btn btn-outline-dark" role="button" href="../listaInventario.php" id="btnInventario">IR</a></td>
                </tr>
                <tr>
                  <td>Registrar nuevo proveedor</td>
                  <td><a class="btn btn-outline-dark" role="button" href="../registrarProveedor.php" id="btnRegistrarProveedor">IR</a></td>
                </tr>
                <tr>
                  <td>Consultar proveedores</td>
                  <td><a class="btn btn-outline-dark" role="button" href="../listaProveedores.php" id="btnProveedores">IR</a></td>
                </tr>
                <tr>
                  <td>Imprimir bitacoras</td>
                  <td><a class="btn btn-outline-dark" role="button" href="../bitacoras.php" id="btnBitacoras">IR</a></td>
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