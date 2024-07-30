<?php

    session_start();

    include ('conexion.php');

    $mysqli = conectarBaseDatos();

    $numero = $_POST['numero'];
    $capacidad = $_POST['capacidad'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];

    $stmt = $mysqli->prepare("INSERT INTO habitaciones (numero, capacidad, precio,descripcion) VALUES (?, ?, ?, ?)" );
    $stmt->bind_param("sids", $numero, $capacidad, $precio, $descripcion);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Habitación registrada correctamente";
    } else {
        $_SESSION['mensaje'] = "Error registrando la habitación"; 
    }
    header("Location: listado_habitaciones.php");
    
    $stmt->close();
    $mysqli->close();

?>