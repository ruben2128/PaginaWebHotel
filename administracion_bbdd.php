<?php
session_start();
require_once "vendor/autoload.php";
include("conexion.php");

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$mensajeExito = '';
$usuario = null;
$login = null;
$rol = null;

if (isset($_SESSION['email'])) {
    $usuario = getUsuario($_SESSION['email']);
    $login = $_SESSION['email'];
    $rol = $usuario['rol'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    if ($accion == 'backup') {
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="backup.sql"');

        $backup = generarBackup();
        echo $backup;
        exit;

    } elseif ($accion == 'restaurar') {
        if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] == 0) {
            $backupFile = $_FILES['backup_file']['tmp_name'];
            $backupContent = file_get_contents($backupFile);
            restaurarBackup($backupContent);
            $mensajeExito = 'Base de datos restaurada con éxito.';
        }

    } elseif ($accion == 'reiniciar') {
        reiniciarBaseDeDatos($usuario['id']);
        $mensajeExito = 'Base de datos reiniciada con éxito.';
    }
}

echo $twig->render('administracion_bbdd.html', ['login' => $login, 'rol' => $rol, 'mensaje_exito' => $mensajeExito]);

?>
