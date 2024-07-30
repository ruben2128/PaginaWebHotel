<?php
session_start();

include 'conexion.php';

$login = '';
$rol = '';
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    if (isset($_SESSION['rol'])) {
        $rol = $_SESSION['rol'];
    }
}

$registro = '';
if (isset($_SESSION['registro'])) {
    $registro = $_SESSION['registro'];
    unset($_SESSION['registro']);
}

$errores = [];
if (isset($_SESSION['errores'])) {
    $errores = $_SESSION['errores'];
    unset($_SESSION['errores']);
}

require_once "vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('index.html', ['login' => $login, 'registro' => $registro, 'rol' => $rol, 'errores' => $errores]);
?>
