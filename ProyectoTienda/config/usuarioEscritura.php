<?php
//Servidor de la bases de datos (Cambiar al que se vaya a usar en el servidor final)
$servername = "localhost";
//Nombre del usuario con permisos de escritura
$usuario = "usuarioEscritura";
//Contraseña de ese usuario de prueba
$password = "2GHrHVv3Y";
//Nombre de la base de datos de prueba
$basededatos = "proyectotienda";

//Nueva conexion a la base de datos
$conn = new mysqli($servername, $usuario, $password, $basededatos);

?>