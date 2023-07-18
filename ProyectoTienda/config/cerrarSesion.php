<?php
    //iniciamos la misma sesion
    session_start();
    //Con esta funcion cerramos todos y borramos todo lo que se haya puesto en la sesion y en $_SESSION
    session_unset();
    //Aqui vamos a borrar lo que haya en las cookies
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    //destruimos la sesion
    session_destroy();
    //regresamos al index para que se pueda iniciar sesion
    header('Location: ../index.php');
    exit;
?>