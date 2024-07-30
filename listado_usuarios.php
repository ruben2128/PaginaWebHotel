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
        $idUsuario = $_POST['id'];

        if ($accion == 'Actualizar'){
            $nuevoRol = $_POST['rol'];
            $nuevoNombre = $_POST['nombre'];
            $nuevosApellidos = $_POST['apellidos'];
            $nuevoEmail = $_POST['email'];
            $nuevaTarjeta = $_POST['tarjeta'];
            $nuevoDNI = $_POST['dni'];

            actualizarDatosUsuario($idUsuario, $nuevoRol, $nuevoNombre, $nuevosApellidos, $nuevoEmail, $nuevaTarjeta, $nuevoDNI);
            $mensajeExito = 'Datos actualizados con éxito.';
            
        } else if ($accion == 'Borrar usuario'){
            eliminarUsuario($idUsuario);
            $mensajeExito = 'Usuario eliminado con éxito.';
        }
    }

    $usuarios = getTodosLosUsuarios();

    if (isset($_SESSION['mensaje_exito'])) {
        $mensajeExito = $_SESSION['mensaje_exito'];
        unset($_SESSION['mensaje_exito']); 
    }

    echo $twig->render('listado_usuarios.html', ['usuarios' => $usuarios, 'login' => $login, 'rol' => $rol, 'mensaje_exito' => $mensajeExito]);

?>
