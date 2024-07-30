<?php
session_start();
require_once 'vendor/autoload.php';
include 'conexion.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$mysqli = conectarBaseDatos();

// Limpiar reservas pendientes que hayan superado el tiempo límite
$tiempo_limite = 30; // en segundos
$mysqli->query("DELETE FROM reservas WHERE estado = 'Pendiente' AND TIMESTAMPDIFF(SECOND, marca_tiempo, NOW()) > $tiempo_limite");

// Recoger datos del formulario
$numero_personas = $_POST['numero_personas'];
$dia_entrada = $_POST['dia_entrada'];
$dia_salida = $_POST['dia_salida'];
$comentarios = $_POST['comentarios'];
$cliente_id = $_POST['id_cliente'];

// Verificar que el número de personas es mayor que 0
if ($numero_personas <= 0) {
    $usuario = null;
    $login = null;
    $rol = null;

    if (isset($_SESSION['email'])) {
        $usuario = getUsuario($_SESSION['email']);
        $login = $_SESSION['email'];
        $rol = $usuario['rol'];
    }

    echo $twig->render('index.html', ['mensaje' => 'El número de personas debe ser mayor que 0.', 'login' => $login, 'rol' => $rol, 'usuario' => $usuario]);
    exit;
}

// Convertir fechas a objetos DateTime para validación
$fecha_entrada = new DateTime($dia_entrada);
$fecha_salida = new DateTime($dia_salida);

// Verificar que la fecha de entrada es anterior a la fecha de salida
if ($fecha_entrada >= $fecha_salida) {
    $usuario = null;
    $login = null;
    $rol = null;

    if (isset($_SESSION['email'])) {
        $usuario = getUsuario($_SESSION['email']);
        $login = $_SESSION['email'];
        $rol = $usuario['rol'];
    }

    echo $twig->render('index.html', ['mensaje' => 'La fecha de entrada debe ser anterior a la fecha de salida.', 'login' => $login, 'rol' => $rol, 'usuario' => $usuario]);
} else {
    // Buscar habitación libre
    $stmt = $mysqli->prepare("SELECT * FROM habitaciones WHERE estado = 'Operativa' AND capacidad >= ? ORDER BY capacidad ASC LIMIT 1");
    $stmt->bind_param("i", $numero_personas);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $habitacion = $result->fetch_assoc();
        $habitacion_id = $habitacion['id'];
        
        // Crear reserva
        $stmt = $mysqli->prepare("INSERT INTO reservas (id_cliente, habitacion_id, numero_personas, comentarios, dia_entrada, dia_salida) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $cliente_id, $habitacion_id, $numero_personas, $comentarios, $dia_entrada, $dia_salida);
        $stmt->execute();
        $reserva_id = $stmt->insert_id;

        // Marcar la habitación como pendiente
        $stmt = $mysqli->prepare("UPDATE habitaciones SET estado = 'Pendiente' WHERE id = ?");
        $stmt->bind_param("i", $habitacion_id);
        $stmt->execute();
        
        // Mostrar detalles de la reserva
        $reserva = [
            'numero_personas' => $numero_personas,
            'dia_entrada' => $dia_entrada,
            'dia_salida' => $dia_salida,
            'comentarios' => $comentarios,
            'numero_habitacion' => $habitacion['numero'],
            'precio_noche' => $habitacion['precio']
        ];
        
        echo $twig->render('confirmar_reserva.html', ['reserva' => $reserva, 'reserva_id' => $reserva_id]);
    } else {    
        $usuario = null;
        $login = null;
        $rol = null;

        if (isset($_SESSION['email'])) {
            $usuario = getUsuario($_SESSION['email']);
            $login = $_SESSION['email'];
            $rol = $usuario['rol'];
        }
        echo $twig->render('index.html', ['mensaje' => 'No hay habitaciones disponibles con la capacidad solicitada.', 'login' => $login, 'rol' => $rol, 'usuario' => $usuario]);
    }

    $stmt->close();
    $mysqli->close();
}
?>
