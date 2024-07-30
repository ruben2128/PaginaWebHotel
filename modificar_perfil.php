<?php

    session_start();

    include 'conexion.php'; 

    $mysqli = conectarBaseDatos();

    if ($mysqli->connect_error) {
        die('Error de Conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $email = $_POST['email'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    $tarjeta = $_POST['tarjeta'];

    $query = "UPDATE usuarios SET   email = ?, clave = ?, tarjeta = ? WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssis", $email, $clave, $tarjeta, $_SESSION['email']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['mensaje'] = 'Perfil modificado con éxito';
        registrarEvento("Datos del usuario actualizado: " . $email);
    } else {
        $_SESSION['mensaje'] = 'Error al modificar el perfil';
    }

    $stmt->close();
    $mysqli->close();

    header("Location: perfil.php");
    exit();

?>
