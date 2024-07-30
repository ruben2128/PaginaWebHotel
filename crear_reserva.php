<?php
    session_start();

    include 'conexion.php'; 
    require_once "vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $usuario = null;
    $login = null;
    $rol = null;

    if (isset($_SESSION['email'])) {
        $usuario = getUsuario($_SESSION['email']);
        $login = $_SESSION['email'];
        $rol = $usuario['rol'];
    }

    if (isset($_SESSION['mensaje'])) {
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
    }
    
    $habitaciones = getHabitaciones();
    $usuarios = getUsuarios();

    echo $twig->render('crear_reserva.html', ['habitaciones' => $habitaciones, 'usuarios' => $usuarios, 'login' => $login, 'rol' => $rol, 'usuario' => $usuario]);
?>