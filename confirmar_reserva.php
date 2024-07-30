<?php
session_start();
include 'conexion.php';
require_once 'vendor/autoload.php'; // Asegúrate de que el archivo autoload.php está en la ubicación correcta

// Inicializar Twig
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$reserva_id = $_POST['reserva_id'];
$accion = $_POST['accion'];

$mysqli = conectarBaseDatos();

$usuario = null;
$login = null;
$rol = null;

if (isset($_SESSION['email'])) {
    $usuario = getUsuario($_SESSION['email']);
    $login = $_SESSION['email'];
    $rol = $usuario['rol'];
}

if ($accion == 'Confirmar') {
    // Verificar si el tiempo ha expirado
    $stmt = $mysqli->prepare("SELECT TIMESTAMPDIFF(SECOND, marca_tiempo, NOW()) AS diff, habitacion_id FROM reservas WHERE id = ? AND estado = 'Pendiente'");
    $stmt->bind_param("i", $reserva_id);
    $stmt->execute();
    $stmt->bind_result($diff, $habitacion_id);
    $stmt->fetch();
    $stmt->close();
    
    if ($diff > 30) {
        // Tiempo expirado, eliminar reserva y actualizar el estado de la habitación
        $stmt = $mysqli->prepare("DELETE FROM reservas WHERE id = ?");
        $stmt->bind_param("i", $reserva_id);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $mysqli->prepare("UPDATE habitaciones SET estado = 'Operativa' WHERE id = ?");
        $stmt->bind_param("i", $habitacion_id);
        $stmt->execute();
        $stmt->close();

        echo $twig->render('index.html', ['mensaje' => 'El tiempo para confirmar la reserva ha expirado. Por favor, intente nuevamente.', 'login' => $login, 'rol' => $rol, 'usuario' => $usuario]);
    } else {
        // Confirmar reserva
        $stmt = $mysqli->prepare("UPDATE reservas SET estado = 'Confirmada' WHERE id = ?");
        $stmt->bind_param("i", $reserva_id);
        $stmt->execute();
        $stmt->close();
        
        // Cambiar estado de la habitación a confirmada
        $stmt = $mysqli->prepare("UPDATE habitaciones SET estado = 'Confirmada' WHERE id = ?");
        $stmt->bind_param("i", $habitacion_id);
        $stmt->execute();
        $stmt->close();
        
        echo $twig->render('index.html', ['mensaje' => 'Reserva confirmada con éxito.', 'login' => $login, 'rol' => $rol, 'usuario' => $usuario]);
    }
} elseif ($accion == 'Cancelar') {
    // Obtener el habitacion_id antes de eliminar la reserva
    $stmt = $mysqli->prepare("SELECT habitacion_id FROM reservas WHERE id = ?");
    $stmt->bind_param("i", $reserva_id);
    $stmt->execute();
    $stmt->bind_result($habitacion_id);
    $stmt->fetch();
    $stmt->close();

    // Eliminar reserva y liberar habitación
    $stmt = $mysqli->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->bind_param("i", $reserva_id);
    $stmt->execute();
    $stmt->close();

    if ($habitacion_id) {
        $stmt = $mysqli->prepare("UPDATE habitaciones SET estado = 'Operativa' WHERE id = ?");
        $stmt->bind_param("i", $habitacion_id);
        $stmt->execute();
        $stmt->close();
    }
    
    echo $twig->render('index.html', ['mensaje' => 'Reserva cancelada.', 'login' => $login, 'rol' => $rol, 'usuario' => $usuario]);
}

$mysqli->close();
?>
