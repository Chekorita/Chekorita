<?php
//Servidor de la bases de datos (Cambiar al que se vaya a usar en el servidor final)
$servername = "localhost";
//Nombre del usuario con permisos de lectura
$username = "usuarioLectura";
//Contraseña de ese usuario de pruebas
$password = "2GHrHVv3Y";
//Nombre de la base de datos de pruebas
$basededatos = "proyectotienda";

//conexion a la base de datos, en caso de error mandara mensaje a consola
$conexion = mysqli_connect($servername, $username, $password) or die ("No se ha podido conectar al servidor de la base de datos");

$bd = mysqli_select_db($conexion, $basededatos) or die ("No se ha podido conectar al servidor de la base de datos");

?>