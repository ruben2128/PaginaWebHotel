<?php

  function conectarBaseDatos(){
    $mysqli = new mysqli('127.0.0.1', 'rubenmartin122324', 'AMNlE7UnkJlxBsYj', 'rubenmartin122324');
    
    if ($mysqli->connect_errno) {
      echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    return $mysqli;
  }

  function getTodosLosUsuarios() {
    $mysqli = conectarBaseDatos();

    $res = $mysqli->prepare("SELECT id, nombre, apellidos, email, dni, tarjeta, rol FROM usuarios");

    $usuarios = array();

    $res->execute();

    $result = $res->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = array('id' => $row['id'], 'nombre' => $row['nombre'],'apellidos' => $row['apellidos'], 'email' => $row['email'], 'tarjeta' => $row['tarjeta'], 'dni' => $row['dni'], 'rol' => $row['rol'] );
        }
    }

    return $usuarios;
  }

  function getUsuario($email) {
    $mysqli = conectarBaseDatos();

    $res = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
    $res->bind_param("s", $email);

    $res->execute();

    $usuario = [];

    $result = $res->get_result();

    if ($row = $result->fetch_assoc()) {
        $usuario = array('id' => $row['id'],'nombre' => $row['nombre'], 'apellidos' => $row['apellidos'], 'email' => $row['email'], 'dni' => $row['dni'], 'tarjeta' => $row['tarjeta'], 'rol' => $row['rol']);
    }

    return $usuario;
  }

  function getUsuarioPorID($id) {
    $mysqli = conectarBaseDatos();

    $res = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ?");
    $res->bind_param("i", $id);

    $res->execute();

    $usuario = [];

    $result = $res->get_result();

    if ($row = $result->fetch_assoc()) {
        $usuario = array('id' => $row['id'],'nombre' => $row['nombre'], 'apellidos' => $row['apellidos'], 'email' => $row['email'], 'dni' => $row['dni'], 'tarjeta' => $row['tarjeta'], 'rol' => $row['rol']);
    }

    return $usuario;
  }


  function getUsuarios() {
    $mysqli = conectarBaseDatos();

    $result = $mysqli->query("SELECT * FROM usuarios");

    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    return $usuarios;
  }

  function getHabitaciones() {
    $mysqli = conectarBaseDatos();

    $result = $mysqli->query("SELECT * FROM habitaciones");

    $habitaciones = [];
    while ($row = $result->fetch_assoc()) {
        $habitaciones[] = $row;
    }

    return $habitaciones;
  }

  function actualizarDatosUsuario($idUsuario, $nuevoRol, $nuevoNombre, $nuevosApellidos, $nuevoEmail, $nuevaTarjeta, $nuevoDNI) {
    $mysqli = conectarBaseDatos();

    $res = $mysqli->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, email = ?, tarjeta = ?, dni = ?, rol = ? WHERE id = ?");
    $res->bind_param("sssissi", $nuevoNombre, $nuevosApellidos, $nuevoEmail, $nuevaTarjeta, $nuevoDNI,  $nuevoRol, $idUsuario);
    if ($res->execute()){
      $_SESSION['mensaje_exito'] = "Datos actualizados correctamente.";
    }
  }

  function eliminarUsuario($id) {
    $mysqli = conectarBaseDatos();
    $usuario = getUsuarioPorID($id);

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      registrarEvento("Usuario eliminado: " . $usuario['email']);
    }

    $stmt->close();
    $mysqli->close();
  }

  function getNombreUsuario($id) {
    $nombre = '';

    $mysqli = conectarBaseDatos();

    $stmt = $mysqli->prepare("SELECT nombre FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $nombre = $result->fetch_assoc()['nombre'];

    $stmt->close();
    $mysqli->close();
    
    return $nombre;
  }

  function actualizarDatosHabitacion($idHabitacion, $numero, $capacidad, $precio, $descripcion) {
    $mysqli = conectarBaseDatos();

    $res = $mysqli->prepare("UPDATE habitaciones SET numero = ?, capacidad = ?, precio = ?, descripcion = ? WHERE id = ?");
    $res->bind_param("sidsi", $numero, $capacidad, $precio, $descripcion, $idHabitacion);
    if ($res->execute()){
      $_SESSION['mensaje_exito'] = "Datos actualizados correctamente.";
    }
  }

  function eliminarHabitacion($id) {
    $mysqli = conectarBaseDatos();

    $sql = "DELETE FROM habitaciones WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();
  }

  function crearHabitacion(){
    $mysqli = conectarBaseDatos();

    $res = $mysqli->prepare("INSERT INTO habitaciones (numero, capacidad, precio, descripcion) VALUES (?, ?, ?, ?)");
    $res->bind_param("sids", $_POST['numero'], $_POST['capacidad'], $_POST['precio'], $_POST['descripcion']);
    if ($res->execute()){
      $_SESSION['mensaje_exito'] = "Habitación creada correctamente.";
    }
  
  }

  function getNumeroHabitacion($id) {
    $numero = '';

    $mysqli = conectarBaseDatos();

    $stmt = $mysqli->prepare("SELECT numero FROM habitaciones WHERE id = ?");
    $stmt->bind_param ("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $numero = $result->fetch_assoc()['numero'];

    $stmt->close();
    $mysqli->close();
    
    return $numero;
  }

  function getReservas($filtros = [], $orden = "ASC", $ordenarPor = "antiguedad", $offset = 0, $limite = 3) {
    $mysqli = conectarBaseDatos();
    $sql = "SELECT reservas.*, usuarios.nombre AS nombre_usuario, habitaciones.numero AS numero_habitacion 
            FROM reservas 
            JOIN usuarios ON reservas.id_cliente = usuarios.id 
            JOIN habitaciones ON reservas.habitacion_id = habitaciones.id";
    $params = [];
    $types = "";

    $whereClauses = [];
    if (isset($filtros['id_cliente'])) {
        $whereClauses[] = "reservas.id_cliente = ?";
        $params[] = $filtros['id_cliente'];
        $types .= "i";
    }
    if (isset($filtros['comentarios'])) {
        $whereClauses[] = "reservas.comentarios LIKE ?";
        $params[] = "%" . $filtros['comentarios'] . "%";
        $types .= "s";
    }
    if (isset($filtros['fecha_inicio']) && isset($filtros['fecha_fin'])) {
        $whereClauses[] = "reservas.dia_entrada BETWEEN ? AND ?";
        $params[] = $filtros['fecha_inicio'];
        $params[] = $filtros['fecha_fin'];
        $types .= "ss";
    } elseif (isset($filtros['fecha_inicio'])) {
        $whereClauses[] = "reservas.dia_entrada >= ?";
        $params[] = $filtros['fecha_inicio'];
        $types .= "s";
    } elseif (isset($filtros['fecha_fin'])) {
        $whereClauses[] = "reservas.dia_entrada <= ?";
        $params[] = $filtros['fecha_fin'];
        $types .= "s";
    }

    if (count($whereClauses) > 0) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    if ($ordenarPor == 'antiguedad') {
        $sql .= " ORDER BY reservas.marca_tiempo " . $orden;
    } elseif ($ordenarPor == 'dias') {
        $sql .= " ORDER BY DATEDIFF(reservas.dia_salida, reservas.dia_entrada) " . $orden;
    }

    $sql .= " LIMIT ? OFFSET ?";
    $params[] = $limite;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $mysqli->error);
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservas = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $mysqli->close();

    return $reservas;
  }

  function getReservasCliente($idCliente) {
    $mysqli = conectarBaseDatos();
    $res = $mysqli->prepare("SELECT * FROM reservas WHERE id_cliente = ?");
    $res->bind_param("i", $idCliente);
    $res->execute();
    $result = $res->get_result();
    $reservas = [];
    while ($row = $result->fetch_assoc()) {
        $row['nombre_usuario'] = getNombreUsuario($row['id_cliente']);
        $row['numero_habitacion'] = getNumeroHabitacion($row['habitacion_id']);
        $reservas[] = $row;
    }
    return $reservas;
  }

  function actualizarDatosReserva($idReserva, $id_cliente, $id_habitacion, $total_personas, $comentarios, $dia_entrada, $dia_salida, $estado, $marca_tiempo) {
    $mysqli = conectarBaseDatos();

    $res = $mysqli->prepare("UPDATE reservas SET id_cliente = ?, habitacion_id = ?, numero_personas = ?, comentarios = ?, dia_entrada = ?, dia_salida = ?, estado = ?, marca_tiempo = ? WHERE id = ?");
    $res->bind_param("iiisssssi", $id_cliente, $id_habitacion, $total_personas, $comentarios, $dia_entrada, $dia_salida, $estado, $marca_tiempo, $idReserva);
    if ($res->execute()){
      $_SESSION['mensaje_exito'] = "Datos actualizados correctamente.";
    }
  }

  function eliminarReserva($id) {
    $mysqli = conectarBaseDatos();

    $sql = "DELETE FROM reservas WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();
  }

  function generarBackup() {
    $mysqli = conectarBaseDatos();
    $backup = "";

    $result = $mysqli->query("SHOW TABLES");
    while ($row = $result->fetch_row()) {
        $table = $row[0];
        $backup .= "DROP TABLE IF EXISTS `$table`;\n";
        $createTableResult = $mysqli->query("SHOW CREATE TABLE `$table`")->fetch_assoc();
        $backup .= $createTableResult['Create Table'] . ";\n\n";
        
        $rowsResult = $mysqli->query("SELECT * FROM `$table`");
        while ($row = $rowsResult->fetch_assoc()) {
            $backup .= "INSERT INTO `$table` VALUES (";
            foreach ($row as $value) {
                $backup .= "'" . $mysqli->real_escape_string($value) . "', ";
            }
            $backup = rtrim($backup, ", ") . ");\n";
        }
        $backup .= "\n";
    }

    return $backup;
  }


  function restaurarBackup($backupContent) {
    $mysqli = conectarBaseDatos();
    $mysqli->multi_query($backupContent);
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->next_result());

    if ($mysqli->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'administrador'")->fetch_row()[0] == 0) {
        $mysqli->query("INSERT INTO usuarios (nombre, apellidos, email, tarjeta, dni, rol) VALUES ('Admin', 'Admin', 'admin@example.com', '1234567890123456', '12345678X', 'administrador')");
    }
  }

  function reiniciarBaseDeDatos($adminId) {
    $mysqli = conectarBaseDatos();
    
    // Guardar la información del administrador actual
    $adminInfo = $mysqli->query("SELECT * FROM usuarios WHERE id = $adminId")->fetch_assoc();

    $mysqli->query("SET foreign_key_checks = 0");
    $result = $mysqli->query("SHOW TABLES");
    while ($row = $result->fetch_row()) {
        $table = $row[0];
        $mysqli->query("TRUNCATE TABLE `$table`");
    }
    $mysqli->query("SET foreign_key_checks = 1");

    // Restaurar la información del administrador actual
    $stmt = $mysqli->prepare("INSERT INTO usuarios (id, nombre, apellidos, email, tarjeta, dni, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $adminInfo['id'], $adminInfo['nombre'], $adminInfo['apellidos'], $adminInfo['email'], $adminInfo['tarjeta'], $adminInfo['dni'], $adminInfo['rol']);
    $stmt->execute();
  }

  function registrarEvento($descripcion) {
    $mysqli = conectarBaseDatos();

    $stmt = $mysqli->prepare("INSERT INTO eventos (descripcion) VALUES (?)");
    $stmt->bind_param("s", $descripcion);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();
  }

  function obtenerEventosLog() {
    $mysqli = conectarBaseDatos();

    $result = $mysqli->query("SELECT * FROM eventos ORDER BY fecha DESC");
    $eventos = $result->fetch_all(MYSQLI_ASSOC);
    
    $result->close();
    $mysqli->close();

    return $eventos;
  }
  

?>