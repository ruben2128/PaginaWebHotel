<?php
    session_start();

    include ('conexion.php');

    $usuario = null;
    $login = null;
    $rol = null;

    if (isset($_SESSION['email'])) {
        $usuario = getUsuario($_SESSION['email']);
        $login = $_SESSION['email'];
        $rol = $usuario['rol'];
    }

    require_once "vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
        
    echo $twig->render('servicios.html', ['login' => $login, 'rol' => $rol]);
?>
