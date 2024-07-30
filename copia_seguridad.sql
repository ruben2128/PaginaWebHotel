DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `eventos` VALUES ('52', '2024-06-07 19:35:15', 'Usuario identificado: director@void.ugr.es');
INSERT INTO `eventos` VALUES ('53', '2024-06-07 19:42:59', 'Usuario cerró sesión: director@void.ugr.es');
INSERT INTO `eventos` VALUES ('54', '2024-06-07 20:02:27', 'Usuario identificado: director@void.ugr.es');
INSERT INTO `eventos` VALUES ('55', '2024-06-07 20:30:37', 'Usuario cerró sesión: director@void.ugr.es');
INSERT INTO `eventos` VALUES ('56', '2024-06-07 20:30:43', 'Usuario identificado: director@void.ugr.es');
INSERT INTO `eventos` VALUES ('57', '2024-06-07 20:35:13', 'Usuario cerró sesión: director@void.ugr.es');
INSERT INTO `eventos` VALUES ('58', '2024-06-07 20:35:34', 'Usuario identificado: abuela@void.ugr.es');
INSERT INTO `eventos` VALUES ('59', '2024-06-07 20:35:57', 'Usuario eliminado: tia@void.ugr.es');
INSERT INTO `eventos` VALUES ('60', '2024-06-07 20:36:17', 'Nuevo usuario registrado: tia@void.ugr.es');
INSERT INTO `eventos` VALUES ('61', '2024-06-07 20:37:59', 'Usuario cerró sesión: abuela@void.ugr.es');
INSERT INTO `eventos` VALUES ('62', '2024-06-07 20:38:10', 'Usuario identificado: director@void.ugr.es');
INSERT INTO `eventos` VALUES ('63', '2024-06-07 21:13:50', 'Nuevo usuario registrado: filemon@void.ugr.es');
INSERT INTO `eventos` VALUES ('64', '2024-06-07 21:15:18', 'Nuevo usuario registrado: bacterio@void.ugr.es');
INSERT INTO `eventos` VALUES ('65', '2024-06-07 21:15:51', 'Nuevo usuario registrado: ofelia@void.ugr.es');
INSERT INTO `eventos` VALUES ('66', '2024-06-07 21:16:53', 'Nuevo usuario registrado: irma@void.ugr.es');
INSERT INTO `eventos` VALUES ('67', '2024-06-07 21:17:11', 'Usuario cerró sesión: director@void.ugr.es');
INSERT INTO `eventos` VALUES ('68', '2024-06-07 21:17:16', 'Usuario identificado: tia@void.ugr.es');

DROP TABLE IF EXISTS `habitaciones`;
CREATE TABLE `habitaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `capacidad` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `estado` enum('Operativa','Pendiente','Confirmada') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Operativa',
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `habitaciones` VALUES ('5', '101', '2', '22.00', 'Una habitación de hotel para dos con cama grande, baño privado, escritorio, minibar y Wi-Fi. Decoración moderna, ventana con vistas y un pequeño rincón de estar completan el espacio confortable.', 'Confirmada');
INSERT INTO `habitaciones` VALUES ('7', '102', '2', '22.00', 'Una habitación de hotel para dos con cama grande, baño privado, escritorio, minibar y Wi-Fi. Decoración moderna, ventana con vistas y un pequeño rincón de estar completan el espacio confortable.', 'Confirmada');
INSERT INTO `habitaciones` VALUES ('8', '103', '2', '25.00', 'Una habitación de hotel para dos con doble cama individual, baño privado, escritorio, minibar y Wi-Fi. Decoración moderna, ventana con vistas y un pequeño rincón de estar completan el espacio confortable.', 'Operativa');
INSERT INTO `habitaciones` VALUES ('9', '104', '2', '25.00', 'Una habitación de hotel para dos con doble cama individual, baño privado, escritorio, minibar y Wi-Fi. Decoración moderna, ventana con vistas y un pequeño rincón de estar completan el espacio confortable.', 'Operativa');
INSERT INTO `habitaciones` VALUES ('10', '105', '2', '25.00', 'Una habitación de hotel para dos con cama grande, baño privado, escritorio, minibar y Wi-Fi. Decoración moderna, ventana con vistas y un pequeño rincón de estar completan el espacio confortable.', 'Operativa');
INSERT INTO `habitaciones` VALUES ('11', '201', '3', '33.00', 'Una habitación triple con tres camas individuales o una cama grande y una individual, baño privado, y Wi-Fi. Incluye escritorio, televisión de pantalla plana y minibar. Decorada con un estilo moderno y funcional, ofrece comodidad y practicidad.', 'Confirmada');
INSERT INTO `habitaciones` VALUES ('12', '202', '3', '33.00', 'Una habitación triple con tres camas individuales o una cama grande y una individual, baño privado, y Wi-Fi. Incluye escritorio, televisión de pantalla plana y minibar. Decorada con un estilo moderno y funcional, ofrece comodidad y practicidad.', 'Operativa');
INSERT INTO `habitaciones` VALUES ('13', '203', '3', '33.00', 'Una habitación triple con tres camas individuales o una cama grande y una individual, baño privado, y Wi-Fi. Incluye escritorio, televisión de pantalla plana y minibar. Decorada con un estilo moderno y funcional, ofrece comodidad y practicidad.', 'Operativa');
INSERT INTO `habitaciones` VALUES ('14', '204', '3', '33.00', 'Una habitación triple con tres camas individuales o una cama grande y una individual, baño privado, y Wi-Fi. Incluye escritorio, televisión de pantalla plana y minibar. Decorada con un estilo moderno y funcional, ofrece comodidad y practicidad.', 'Operativa');
INSERT INTO `habitaciones` VALUES ('15', '301', '4', '44.00', 'Una habitación cuádruple equipada con dos camas grandes o una cama grande y dos individuales, ideal para familias o grupos. Cuenta con baño privado completo, Wi-Fi gratuito, televisión de pantalla plana y minibar. El espacio está decorado de manera funcional y contemporánea, ofreciendo comodidad y conveniencia para todos los huéspedes.', 'Confirmada');
INSERT INTO `habitaciones` VALUES ('16', '302', '4', '44.00', 'Una habitación cuádruple equipada con dos camas grandes o una cama grande y dos individuales, ideal para familias o grupos. Cuenta con baño privado completo, Wi-Fi gratuito, televisión de pantalla plana y minibar. El espacio está decorado de manera funcional y contemporánea, ofreciendo comodidad y conveniencia para todos los huéspedes.', 'Confirmada');
INSERT INTO `habitaciones` VALUES ('17', 'suite presidencial', '4', '100.00', 'Una suite presidencial lujosa y espaciosa, con dormitorio principal con cama king-size, segundo dormitorio opcional, y sala de estar elegante. Incluye baño de mármol con jacuzzi, comedor, oficina privada, y balcón panorámico. Equipada con tecnología de punta, servicio de mayordomo y detalles de alta gama para una estancia excepcional.', 'Operativa');
INSERT INTO `habitaciones` VALUES ('18', 'suite nupcial', '2', '150.00', 'Estas suites suelen ser amplias y están equipadas con una cama grande y confortable, decoración elegante y romántica, y a menudo incluyen servicios adicionales como una botella de champán de cortesía, un jacuzzi privado y un servicio de habitaciones especial para garantizar una experiencia inolvidable. Además, pueden ofrecer vistas impresionantes y balcones privados para disfrutar de momentos íntimos y memorables', 'Operativa');

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dni` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tarjeta` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('cliente','recepcionista','administrador') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` VALUES ('5', 'abuela', 'abuela ', '294458230', 'abuela@void.ugr.es', '$2y$10$4mYKh0FjsKDoPr3pLO7kve.ueCfxbxIda1oEDIlyd.R17DhNRG6em', '2147483647', 'administrador');
INSERT INTO `usuarios` VALUES ('6', 'director', 'director', '102339584', 'director@void.ugr.es', '$2y$10$sX4nZukzVTGhTeGqHzi3NOUFNArdprg/KZAazQNqR9VFywTN9x2Ay', '2147483647', 'recepcionista');
INSERT INTO `usuarios` VALUES ('7', 'elsuper', 'elsuper', '49220423', 'elsuper@void.ugr.es', '$2y$10$YeNSfPAdCgnv8zFzvjgzG.1YqxqxEtH1/4bgAo9/4g1QoK8Z9/zGO', '2147483647', 'recepcionista');
INSERT INTO `usuarios` VALUES ('8', 'mortadelo', 'mortadelo', '10234932', 'mortadelo@void.ugr.es', '$2y$10$TLfniaJ2F4P2FN0cMijGG.Zig7ErxsQOq39BgLqHWJkRWA82cgUWG', '2147483647', 'cliente');
INSERT INTO `usuarios` VALUES ('18', 'tia', 'tia', '20889549Y', 'tia@void.ugr.es', '$2y$10$hbHKMdLRH4LoWiUs4dnfZuLtV1w396/U.wA0prF8LuM7zW8519Ft2', '4970110000001029', 'administrador');
INSERT INTO `usuarios` VALUES ('19', 'filemon', 'filemon', '20889549Y', 'filemon@void.ugr.es', '$2y$10$z4.B4n.vZztFMctR5kEMTudLoJfFrAbWnHuE9VHxk1zFgcDVNsaTS', '4970110000001029', 'cliente');
INSERT INTO `usuarios` VALUES ('20', 'bacterio', 'bacterio', '20889549Y', 'bacterio@void.ugr.es', '$2y$10$LSHzRT9yZWf1yPuw3LFTwe0rpV7.WtNkHbdQzfbyls6PlwSQgi4ke', '4970110000001029', 'cliente');
INSERT INTO `usuarios` VALUES ('21', 'ofelia', 'ofelia', '20889549Y', 'ofelia@void.ugr.es', '$2y$10$HrxII1Z3qkh.1bIOIJaLSO9CCJZKDdstA3gGr6/A6V651KtPXoaEO', '4970110000001029', 'cliente');
INSERT INTO `usuarios` VALUES ('22', 'irma', 'irma', '20889549Y', 'irma@void.ugr.es', '$2y$10$KfPLXFEWCj6UhFVqGzc4zunO4v/hvgI3.30ooTqskY3IwDjqf4Jc2', '4970110000001029', 'cliente');

DROP TABLE IF EXISTS `reservas`;
CREATE TABLE `reservas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `habitacion_id` int NOT NULL,
  `numero_personas` int NOT NULL,
  `comentarios` text COLLATE utf8mb4_general_ci,
  `dia_entrada` date NOT NULL,
  `dia_salida` date NOT NULL,
  `estado` enum('Pendiente','Confirmada') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pendiente',
  `marca_tiempo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `habitacion_id` (`habitacion_id`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`habitacion_id`) REFERENCES `habitaciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `reservas` VALUES ('20', '5', '5', '2', 'Reserva para dos personas', '2024-06-14', '2024-06-17', 'Confirmada', '2024-06-07 21:00:19');
INSERT INTO `reservas` VALUES ('21', '8', '11', '3', 'reserva para 3 personas', '2024-06-20', '2024-06-23', 'Confirmada', '2024-06-07 21:00:53');
INSERT INTO `reservas` VALUES ('22', '18', '15', '4', 'reserva para 4 personas', '2024-06-07', '2024-06-10', 'Confirmada', '2024-06-07 21:01:11');
INSERT INTO `reservas` VALUES ('23', '7', '7', '2', 'Reserva para dos personas', '2024-06-26', '2024-07-03', 'Confirmada', '2024-06-07 21:01:45');
INSERT INTO `reservas` VALUES ('24', '6', '16', '4', 'Reserva para cuatro personas', '2024-06-28', '2024-06-30', 'Confirmada', '2024-06-07 21:02:11');



