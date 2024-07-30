<?php
session_start();

include 'conexion.php';

$mysqli = conectarBaseDatos();

$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$dni = $_POST['dni'];
$email = $_POST['email'];
$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
$tarjeta = $_POST['tarjeta'];

$error = false;
$errores = [];

if (empty($nombre) || empty($apellidos)) {
    $errores[] = "El nombre y los apellidos no pueden estar vacíos";
    $error = true;
} else if (!preg_match("/^[0-9]{8}[A-Z]$/", $dni)) {
    $errores[] = "El DNI no es válido. Debe tener 8 dígitos y una letra mayúscula.";
    $error = true;
} else if (!verificarDNI($dni)) {
    $errores[] = "El DNI no es válido. La letra no coincide con el número.";
    $error = true;
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El email no es válido";
    $error = true;
} else if (strlen($_POST['clave']) < 3) { 
    $errores[] = "La contraseña debe tener al menos 5 caracteres";
    $error = true;
} else if (!preg_match("/^[0-9]{16}$/", $tarjeta)) {
    $errores[] = "La tarjeta de crédito no es válida. Debe tener 16 dígitos.";
    $error = true;
} else if (!algoritmoLuhn($tarjeta)) {
    $errores[] = "El número de tarjeta no es válido.";
    $error = true;
} else {
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errores[] = 'El email ya está registrado';
        $error = true;
    }
    $stmt->close();
}

if ($error == false) {
    $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, apellidos, dni, email, clave, tarjeta) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $apellidos, $dni, $email, $clave, $tarjeta);

    if ($stmt->execute()) {
        $_SESSION['registro'] = 'Usuario registrado con éxito';
        $errores[] = 'Usuario registrado con éxito';
        registrarEvento("Nuevo usuario registrado: " . $email);
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['errores'] = $errores;
}

$mysqli->close();
header("Location: index.php");
exit();

function verificarDNI($dni) {
    $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    $numero = substr($dni, 0, -1);
    $letra = strtoupper(substr($dni, -1));
    $resto = $numero % 23;

    return $letras[$resto] == $letra;
}

function algoritmoLuhn($tarjeta) {
    $suma = 0;
    $longitud = strlen($tarjeta);
    $par = $longitud % 2;

    for ($i = 0; $i < $longitud; $i++) {
        $digito = $tarjeta[$i];
        if ($i % 2 == $par) {
            $digito *= 2;
            if ($digito > 9) {
                $digito -= 9;
            }
        }
        $suma += $digito;
    }

    return $suma % 10 == 0;
}
?>
