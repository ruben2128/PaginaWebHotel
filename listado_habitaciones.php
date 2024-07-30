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
        $idHabitacion = $_POST['id'];

        if ($accion == 'Actualizar'){
            $numero = $_POST['numero'];
            $capacidad = $_POST['capacidad'];
            $precio = $_POST['precio'];
            $descripcion = $_POST['descripcion'];

            actualizarDatosHabitacion($idHabitacion, $numero, $capacidad, $precio, $descripcion);
            $mensajeExito = 'Datos actualizados con éxito.';
            
        } else if ($accion == 'Borrar habitacion'){
            eliminarHabitacion($idHabitacion);
            $mensajeExito = 'Habitación eliminada con éxito.';
        }
    }

    $habitaciones = getHabitaciones();

    if (isset($_SESSION['mensaje_exito'])) {
        $mensajeExito = $_SESSION['mensaje_exito'];
        unset($_SESSION['mensaje_exito']); 
    }

    echo $twig->render('listado_habitaciones.html', ['habitaciones' => $habitaciones, 'login' => $login, 'rol' => $rol, 'mensaje_exito' => $mensajeExito]);

?>
