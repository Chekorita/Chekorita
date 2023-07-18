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
        //recuperamos los datos que se pueden modificar y el nombre para que podamos saber a cual se cambiara
        $nombre = $_POST['Nombre'];
        $descripcionPro = $_POST['Descripcion'];
        $precioCompra = $_POST['PrecioCompra'];
        $precioVenta = $_POST['PrecioVenta'];
        $PesoOCantidad = $_POST['PesoCantidad'];
        $numero = $_POST['Numero'];
        $proveedor = $_POST['Proveedor'];

        //recuperamos la hora para ingresarlo en la bitacora y creamos la descripción
        $fechaactual = date("Y-n-j H:i:s");
        if($PesoOCantidad=="Peso"){
            $descripcion = "Se ha modificado el producto: " . $nombre . ". Los nuevos datos son: descripción: " . $descripcionPro . " el cual ahora se compra en: " . $precioCompra . " por kilo y se vende al nuevo precio de: " . $precioVenta . " por kilo, siendo que la nueva cantidad registrada de producto es de: " . $numero . "Kg. y su proveedor es: " . $proveedor;
        }else{
            $descripcion = "Se ha modificado el producto: " . $nombre . ". Los nuevos datos son: descripción: " . $descripcionPro . " el cual ahora se compra en: " . $precioCompra . " por unidad y se vende al nuevo precio de: " . $precioVenta . " por unidad, siendo que la nueva cantidad registrada de producto son: " . $numero . " unidades, y su proveedor es: " . $proveedor;
        }

        //hacemos la consulta para actualizar, pero dependera de si se ha cambiado la contraseña o no
        $sql = "UPDATE producto SET descripcion='". $descripcionPro ."', precioCompra='". $precioCompra ."', precioVenta='". $precioVenta . "', cantidad='". $numero . "', nombreProveedor='". $proveedor ."' WHERE nombreProducto='". $nombre ."'";
        
        if ($conn->query($sql) === false) {
            die(mysqli_error($conn));
        }else{
            //Ahora agregamos la nueva entrada de bitacora que registre la modificación
            $sql2 = "INSERT INTO bitaagregainv (fecha, nombreUsuario, descripcion) VALUES ('". $fechaactual ."', '" . $_SESSION['nombre'] . "', '" . $descripcion . "')"; 
            if (($result = mysqli_query($conn, $sql2)) === false) {
                die(mysqli_error($conn));
            }
            //en caso de concretarse lo mandamos de nuevo a la lista
            mysqli_close($conn);
            header('Location: ../listaInventario.php');
            exit;
        }
    }

    //este if detecta si se manda como un post del boton de eliminar
    if (isset($_POST['elimi'])){
        //llamamos la configuracion de escritura
        include('./config/usuarioEscritura.php');
        //recuperamos el nombre y apellido para que sepamos cual es el que eliminaremos
        $nombre = $_POST['Nombre'];
        //creamos la consulta para eliminar
        $sql = "DELETE from producto WHERE nombreProducto='". $nombre ."'";
        if ($conn->query($sql) === false) {
            die(mysqli_error($conn));
        }else{
            //en caso de concretarse, agregamos a la bitacora lo que se hizo con el producto
            $fechaactual = date("Y-n-j H:i:s");
            $descripcion = "Se ha dado de baja al producto: " . $nombre;

            $sql2 = "INSERT INTO bitabajainv (fecha, nombreUsuario, descripcion) VALUES ('". $fechaactual ."', '" . $_SESSION['nombre'] . "', '" . $descripcion . "')"; 
            if (($result = mysqli_query($conn, $sql2)) === false) {
                die(mysqli_error($conn));
            }
            //en caso de concretarse lo mandamos de nuevo a la lista
            mysqli_close($conn);
            header('Location: ../listaInventario.php');
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
        <title>Modificar producto</title>
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
            <a id="regresarInicio" href="../listaInventario.php">
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
                            $consulta = "SELECT * FROM producto WHERE idProducto='". $_POST['id']."'";
                            $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                            $columna = mysqli_fetch_array($resultado);

                            $categoria = $columna['nombreCategoria'];
                            $proveedor = $columna['nombreProveedor'];
                    ?>
                    <h1>Modificar el producto</h1>
                    <h4>Ingresa la información a modificar</h4>
                    <label for="Nombre" class="form-label">Nombre del producto: </label>
                    <input id="Nombre" type="text" name="Nombre" required value="<?php echo $columna['nombreProducto']; ?>" readonly>
                    <label for="Descripcion" class="form-label">Descripción del producto: </label>
                    <input id="Descripcion" type="text" name="Descripcion" required value="<?php echo $columna['descripcion']; ?>">
                    <label for="PrecioCompra" class="form-label">Precio de compra por unidad: </label>
                    <input id="PrecioCompra" type="text" name="PrecioCompra" required onKeyPress="return SoloNumerosDecimales(event);" value="<?php echo $columna['precioCompra']; ?>">
                    <label for="PrecioVenta" class="form-label">Precio de venta por unidad: </label>
                    <input id="PrecioVenta" type="text" name="PrecioVenta" required onKeyPress="return SoloNumerosDecimales(event);" value="<?php echo $columna['precioVenta']; ?>">
                    <fieldset>
                        <legend>Elije si el producto es por peso o por unidad</legend>
                        <?php
                            //Este if es para decidir cual aparecera seleccionado
                            if($columna['nombreProducto']=="Peso"){
                        ?>
                        <label>
                            Peso
                            <input type="radio" name="PesoCantidad" value="Peso" checked disabled>
                        </label>
                        <label>
                            Unidad
                            <input type="radio" name="PesoCantidad" value="Unidad" disabled> 
                        </label>
                        <?php
                            }else{
                        ?>
                        <label>
                            Peso
                            <input type="radio" name="PesoCantidad" value="Peso" disabled>
                        </label>
                        <label>
                            Unidad
                            <input type="radio" name="PesoCantidad" value="Unidad" checked disabled>
                        </label>
                        <?php      
                            }
                        ?>
                        <label for="Numero" class="form-label">Ingresa la nueva cantidad: </label>
                        <input id="Numero" type="text" name="Numero" required value="<?php echo $columna['cantidad']; ?>">
                    </fieldset>
                
                    <label for="Proveedor" class="form-label">Elije el proveedor: </label>
                    <select name="Proveedor" id="Proveedor" required>
                        <option value="0"> </option> 
                        <?php
                            //Se llama la configuración del usuario de lectura
                            include('./config/usuarioLectura.php');
                            //recuperamos todos los proveedores
                            $consulta = "SELECT * FROM proveedor";
                            $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                            while($columna1 = mysqli_fetch_array($resultado)){
                                //vamos creando los opcion con los datos recuperados
                                //pero con este if lo que se hace es decidir cual es el seleccionado al cargar
                                //revisando que el que se esta cargando coincida con el que se recupero
                                if($columna1['nombreProveedor'] == $proveedor){
                                    echo "<option value='". $columna1['nombreProveedor'] ."' selected>" . $columna1['nombreProveedor'] . "</option>";
                                }else{
                                    echo "<option value='". $columna1['nombreProveedor'] ."'>" . $columna1['nombreProveedor'] . "</option>";
                                }
                            }
                        ?>
                    </select>

                    <label for="Categoria" class="form-label">Elije la categoria: </label>
                    <select name="Categoria" id="Categoria" required disabled>
                        <option value="0"> </option> 
                        <?php
                            //Se llama la configuración del usuario de lectura
                            include('./config/usuarioLectura.php');
                            //recuperamos todas las categorias posibles
                            $consulta = "SELECT * FROM categoriaprodu";
                            $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                            while($columna2 = mysqli_fetch_array($resultado)){
                                //vamos creando los opcion con los datos recuperados
                                if($columna2['nombreCategoria']==$categoria){
                                    echo "<option value='". $columna2['nombreCategoria'] ."' selected>" . $columna2['nombreCategoria'] . "</option>";
                                }else{
                                    echo "<option value='". $columna2['nombreCategoria'] ."'>" . $columna2['nombreCategoria'] . "</option>";
                                }
                            }
                        ?>
                    </select>
        
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