<?php
    session_start();
    require_once "vendor/autoload.php";
    include("conexion.php");

    $usuario = null;
    $login = null;
    $rol = null;

    if (isset($_SESSION['email'])) {
        $usuario = getUsuario($_SESSION['email']);
        $login = $_SESSION['email'];
        $rol = $usuario['rol'];
    }

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if (isset($_SESSION['email'])) {
        $usuario = getUsuario($_SESSION['email']);
        if ($usuario['rol'] == 'administrador') {
            $eventos = obtenerEventosLog();
            echo $twig->render('log.html', ['eventos' => $eventos, 'login' => $login, 'rol' => $rol]);
        } else {
            echo "No tienes permiso para ver esta página.";
        }
    } else {
        echo "Por favor, inicia sesión.";
    }

   
?>