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
    if (isset($_POST['accion']) && isset($_POST['id'])) {
        $accion = $_POST['accion'];
        $idReserva = $_POST['id'];

        if ($accion == 'Actualizar') {
            $id_cliente = $_POST['id_cliente'];
            $id_habitacion = $_POST['id_habitacion'];
            $total_personas = $_POST['total_personas'];
            $comentarios = $_POST['comentarios'];
            $dia_entrada = $_POST['dia_entrada'];
            $dia_salida = $_POST['dia_salida'];
            $estado = $_POST['estado'];
            $marca_tiempo = $_POST['marca_tiempo'];

            actualizarDatosReserva($idReserva, $id_cliente, $id_habitacion, $total_personas, $comentarios, $dia_entrada, $dia_salida, $estado, $marca_tiempo);
            $mensajeExito = 'Datos actualizados con éxito.';

        } else if ($accion == 'Borrar Reserva') {
            eliminarReserva($idReserva);
            $mensajeExito = 'Reserva eliminada con éxito.';
        }
    }
}

// Recoger filtros y ordenación de la solicitud POST o cookies
$comentariosFiltro = isset($_POST['comentarios_filtro']) ? $_POST['comentarios_filtro'] : (isset($_COOKIE['comentarios_filtro']) ? $_COOKIE['comentarios_filtro'] : '');
$fechaInicioFiltro = isset($_POST['fecha_inicio_filtro']) ? $_POST['fecha_inicio_filtro'] : (isset($_COOKIE['fecha_inicio_filtro']) ? $_COOKIE['fecha_inicio_filtro'] : '');
$fechaFinFiltro = isset($_POST['fecha_fin_filtro']) ? $_POST['fecha_fin_filtro'] : (isset($_COOKIE['fecha_fin_filtro']) ? $_COOKIE['fecha_fin_filtro'] : '');
$ordenarPor = isset($_POST['ordenar_por']) ? $_POST['ordenar_por'] : (isset($_COOKIE['ordenar_por']) ? $_COOKIE['ordenar_por'] : 'antiguedad');
$orden = isset($_POST['orden']) ? $_POST['orden'] : (isset($_COOKIE['orden']) ? $_COOKIE['orden'] : 'asc');
$pagina = isset($_POST['pagina']) ? $_POST['pagina'] : (isset($_COOKIE['pagina']) ? $_COOKIE['pagina'] : 1);

// Guardar los valores en cookies
setcookie('comentarios_filtro', $comentariosFiltro, time() + 86400);
setcookie('fecha_inicio_filtro', $fechaInicioFiltro, time() + 86400);
setcookie('fecha_fin_filtro', $fechaFinFiltro, time() + 86400);
setcookie('ordenar_por', $ordenarPor, time() + 86400);
setcookie('orden', $orden, time() + 86400);
setcookie('pagina', $pagina, time() + 86400);

$offset = ($pagina - 1) * 3;

$filtros = [];
if ($rol == 'cliente') {
    $filtros['id_cliente'] = $usuario['id'];
}
if ($comentariosFiltro) {
    $filtros['comentarios'] = $comentariosFiltro;
}
if ($fechaInicioFiltro) {
    $filtros['fecha_inicio'] = $fechaInicioFiltro;
}
if ($fechaFinFiltro) {
    $filtros['fecha_fin'] = $fechaFinFiltro;
}

$reservas = getReservas($filtros, $orden, $ordenarPor, $offset);

echo $twig->render('listado_reservas.html', [
    'reservas' => $reservas,
    'login' => $login,
    'rol' => $rol,
    'mensaje_exito' => $mensajeExito,
    'comentarios_filtro' => $comentariosFiltro,
    'fecha_inicio_filtro' => $fechaInicioFiltro,
    'fecha_fin_filtro' => $fechaFinFiltro,
    'ordenar_por' => $ordenarPor,
    'orden' => $orden,
    'pagina' => $pagina
]);
?>
