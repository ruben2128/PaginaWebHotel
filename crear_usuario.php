<?php
    session_start();

    include 'conexion.php'; 
    require_once "vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if (isset($_SESSION['mensaje'])) {
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
    }

    $usuario = null;
    $login = null;
    $rol = null;

    if (isset($_SESSION['email'])) {
        $usuario = getUsuario($_SESSION['email']);
        $login = $_SESSION['email'];
        $rol = $usuario['rol'];
    }

    echo $twig->render('crear_usuario.html', ['login' => $login, 'rol' => $rol, 'usuario' => $usuario]);
?>