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

    if (isset($_POST['agregarProducto'])){
        //Se llama la configuración del usuario de lectura
        include('./config/usuarioLectura.php');
        //ponemos en variables todos los datos que nos mandarón
        $nombre = $_POST['Nombre'];
        $descripcionPro = $_POST['Descripcion'];
        $precioCompra = $_POST['PrecioCompra'];
        $precioVenta = $_POST['PrecioVenta'];
        $PesoOCantidad = $_POST['PesoCantidad'];
        $numero = $_POST['Numero'];
        $proveedor = $_POST['Proveedor'];
        $categoria = $_POST['Categoria'];

        //recuperamos la hora para ingresarlo en la bitacora y creamos la descripción
        $fechaactual = date("Y-n-j H:i:s");
        if($PesoOCantidad=="Peso"){
            $descripcion = "Se ha agregado el producto: " . $nombre . " con la descripción: " . $descripcionPro . " el cual se compra en: " . $precioCompra . " por kilo y se vende en: " . $precioVenta . " por kilo, siendo que se agregaron: " . $numero . "Kg. su categoria de producto es: " . $categoria . " y su proveedor es: " . $proveedor;
        }else{
            $descripcion = "Se ha agregado el producto: " . $nombre . " con la descripción: " . $descripcionPro . " el cual se compra en: " . $precioCompra . " por unidad y se vende en: " . $precioVenta . " por unidad, siendo que se agregaron: " . $numero . " unidades, su categoria de producto es: " . $categoria . " y su proveedor es: " . $proveedor;
        }
        

        //Consulta, con esta recuperamos resultados que sean similares al producto que ingresamos
        $consulta1 = "SELECT nombreProducto FROM producto WHERE nombreProducto='". $nombre ."'";
        $resultado1 = mysqli_query( $conexion, $consulta1 ) or die ( "Algo ha ido mal en la consulta a la base de datos");


        //si hay mas de 0 mandara un mensaje de error
        if(mysqli_num_rows($resultado1)>0){
            echo '<p class="error">Este producto ya esta registrado</p>';
        }else{
            //Se llama la configuración del usuario de escritura
            include('./config/usuarioEscritura.php');
            //Hacemos la insercion, con los datos recuperados para registrar el producto
            $sql1 = "INSERT INTO producto (nombreProducto, descripcion, precioCompra, precioVenta, pesoOunidad, cantidad, nombreCategoria, nombreProveedor) VALUES 
            ('". $nombre . "', '" . $descripcionPro . "', '" . $precioCompra . "', '" . $precioVenta . "', '" . $PesoOCantidad . "', '" . $numero . "', '" . $categoria . "', '" . $proveedor . "')"; 
            if (($result = mysqli_query($conn, $sql1)) === false) {
                die(mysqli_error($conn));
            }
            //hacemos la insercion con los datos para hacer la bitacora
            $sql2 = "INSERT INTO bitaagregainv (fecha, nombreUsuario, descripcion) VALUES ('". $fechaactual ."', '" . $_SESSION['nombre'] . "', '" . $descripcion . "')"; 
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
        <title>Registrar producto</title>
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
                    <h1>Añadir nuevo producto</h1>
                    <hr>
                    <h4>Ingresa la información solicitada</h4>
                    <label for="Nombre" class="form-label">Nombre del producto: </label>
                    <input id="Nombre" type="text" name="Nombre" required onKeyPress="return SoloLetras(event);">
                    <label for="Descripcion" class="form-label">Descripción del producto: </label>
                    <input id="Descripcion" type="text" name="Descripcion" required>
                    <label for="PrecioCompra" class="form-label">Precio de compra por unidad: </label>
                    <input id="PrecioCompra" type="text" name="PrecioCompra" required onKeyPress="return SoloNumerosDecimales(event);">
                    <label for="PrecioVenta" class="form-label">Precio de venta por unidad: </label>
                    <input id="PrecioVenta" type="text" name="PrecioVenta" required onKeyPress="return SoloNumerosDecimales(event);">
                    <hr>
                    <fieldset>
                        <legend>Elije si el producto es por peso o por unidad</legend>
                        <label>
                            Peso
                            <input type="radio" name="PesoCantidad" value="Peso" required="required">
                        </label>
                        <label>
                            Unidad
                            <input type="radio" name="PesoCantidad" value="Unidad" required="required">
                        </label>
                        <label for="Numero" class="form-label">Ingresa la cantidad: </label>
                        <input id="Numero" type="text" name="Numero" required>
                    </fieldset>
                    <hr>
                    <label for="Proveedor" class="form-label">Elije el proveedor: </label>
                    <select name="Proveedor" id="Proveedor" required>
                        <option value="0"> </option> 
                        <?php
                            //Se llama la configuración del usuario de lectura
                            include('./config/usuarioLectura.php');
                            //recuperamos todos los proveedores
                            $consulta = "SELECT * FROM proveedor";
                            $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                            while($columna = mysqli_fetch_array($resultado)){
                                //vamos creando los opcion con los datos recuperados
                                echo "<option value='". $columna['nombreProveedor'] ."'>" . $columna['nombreProveedor'] . "</option>";
                            }
                        ?>
                    </select><br>

                    <label for="Categoria" class="form-label">Elije la categoria: </label>
                    <select name="Categoria" id="Categoria" required>
                        <option value="0"> </option> 
                        <?php
                            //Se llama la configuración del usuario de lectura
                            include('./config/usuarioLectura.php');
                            //recuperamos todas las categorias posibles
                            $consulta = "SELECT * FROM categoriaprodu";
                            $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                            while($columna = mysqli_fetch_array($resultado)){
                                //vamos creando los opcion con los datos recuperados
                                echo "<option value='". $columna['nombreCategoria'] ."'>" . $columna['nombreCategoria'] . "</option>";
                            }
                        ?>
                    </select><br>
                    <hr>
                    <button type="submit" name="agregarProducto" value="agregarProducto">Agregar producto</button>
                </form>
            </div>
        </div>
    </body>
</html>

<?php
//Cierre del else principal
}
?>