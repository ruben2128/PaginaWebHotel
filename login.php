<?php
    session_start();
    
    include 'conexion.php'; 

    $mysqli = conectarBaseDatos();

    if ($mysqli->connect_error) {
        die('Error de Conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['clave'])) {

            $_SESSION['email'] = $email;
            if ($user['rol'] == 'administrador') {
                $_SESSION['rol'] = 'administrador';
            } else if ($user['rol'] == 'recepcionista'){
                $_SESSION['rol'] = 'recepcionista';
            }else if ($user['rol'] == 'cliente'){
                $_SESSION['rol'] = 'cliente';
            }
            registrarEvento("Usuario identificado: " . $email);
            $_SESSION['login'] = 'BIENVENID@ ' . $user['nombre'] ;
            header ("Location: index.php");

        } else {
            echo 'Usuario y/o contraseña incorrectos';
        }
    } else {
        echo 'Usuario y/o contraseña incorrectos';
    }

    $stmt->close();
    $mysqli->close();
?>
