-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2025 a las 19:55:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `eduglossa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('disponible','cerrado') NOT NULL DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`id_curso`, `nombre`, `descripcion`, `precio`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, 'Curso  Profesional', '...', 20000.00, '0000-00-00', '0000-00-00', 'disponible'),
(2, 'uñas capping', '.....', 450000.00, '2025-06-20', '2025-06-28', 'disponible'),
(3, 'Curso  Profesional manicure intermedio', 'sncdcxnms vd', 340000.00, '2025-06-19', '2025-06-27', 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencias`
--

CREATE TABLE `evidencias` (
  `id_evidencia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `url_archivo` varchar(255) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','aprobado','reprobado') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evidencias`
--

INSERT INTO `evidencias` (`id_evidencia`, `id_usuario`, `id_curso`, `id_modulo`, `url_archivo`, `fecha_subida`, `estado`) VALUES
(2, 5009, 2, 102, '6850b57bb794d_collage foro prueba3.png', '2025-06-17 00:23:23', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro`
--

CREATE TABLE `foro` (
  `id_foro` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','cerrado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `foro`
--

INSERT INTO `foro` (`id_foro`, `titulo`, `fecha_creacion`, `estado`) VALUES
(505, 'preguntas sobre la primera seccion ', '2025-05-14 00:21:28', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro_comentarios`
--

CREATE TABLE `foro_comentarios` (
  `id_comentario` int(11) NOT NULL,
  `id_foro` int(11) NOT NULL,
  `id_comentario_padre` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `foro_comentarios`
--

INSERT INTO `foro_comentarios` (`id_comentario`, `id_foro`, `id_comentario_padre`, `id_usuario`, `contenido`, `fecha`, `leido`) VALUES
(5076, 505, NULL, 3001, 'holi', '2025-05-20 02:28:36', 0),
(5077, 505, NULL, 5001, 'holi', '2025-05-20 02:50:31', 0),
(5078, 505, 5076, 5001, 'holi', '2025-06-17 22:17:26', 0),
(5079, 505, NULL, 5001, 'holi', '2025-06-17 22:22:33', 0),
(5080, 505, 5079, 5001, 'holi', '2025-06-17 22:57:53', 0),
(5081, 505, NULL, 5001, 'hola', '2025-06-17 22:58:05', 0),
(5082, 505, NULL, 5001, 'holi', '2025-06-17 23:11:30', 0),
(5083, 505, 5082, 5001, 'que tal', '2025-06-17 23:11:42', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro_participantes`
--

CREATE TABLE `foro_participantes` (
  `id_participacion` int(11) NOT NULL,
  `id_foro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` enum('activo','bloqueado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `foro_participantes`
--

INSERT INTO `foro_participantes` (`id_participacion`, `id_foro`, `id_usuario`, `estado`) VALUES
(50541, 505, 5001, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id_inscripcion` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL,
  `estado` enum('activa','inactiva','cancelada') DEFAULT 'activa',
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripcion`
--

INSERT INTO `inscripcion` (`id_inscripcion`, `id_pago`, `estado`, `id_usuario`, `id_curso`, `id_modulo`) VALUES
(3434, 12144, 'activa', 5009, 2, NULL),
(3435, 12146, 'activa', 5010, NULL, 102),
(3437, 12149, 'activa', 5012, NULL, 101),
(3438, 12151, 'activa', 5014, 3, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material`
--

CREATE TABLE `material` (
  `id_material` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `url_material` varchar(255) NOT NULL,
  `tipo` enum('video','pdf') NOT NULL DEFAULT 'video'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `material`
--

INSERT INTO `material` (`id_material`, `id_modulo`, `nombre`, `url_material`, `tipo`) VALUES
(1, 101, 'Manicure Basic', 'https://www.youtube.com/watch?v=5in4D1tRobs', 'video'),
(2, 101, 'Manicure Basic gel', 'https://www.youtube.com/watch?v=zlaQtQZNMDk', 'video'),
(100, 101, 'Manicure Basic gel', 'holi.pdf', 'pdf'),
(1011, 101, 'video completo', 'https://www.youtube.com/watch?v=zlaQtQZNMD', 'video');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_modulo` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` enum('disponible','cerrado') NOT NULL DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id_modulo`, `id_curso`, `nombre`, `descripcion`, `precio`, `estado`) VALUES
(101, 1, 'Manicure Basic ', '...', 5000.00, 'cerrado'),
(102, 2, 'modulo profesional', '.....', 4500000.00, 'disponible'),
(103, 1, 'manicure', '.....', 3000000.00, 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL,
  `tipo` enum('respuesta','mencion') NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `leida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `estado` enum('pendiente','completado','cancelado') DEFAULT 'pendiente',
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `detalles_pago` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles_pago`)),
  `id_curso` int(11) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `estado`, `fecha_pago`, `detalles_pago`, `id_curso`, `id_modulo`, `id_usuario`) VALUES
(12144, 'completado', '2025-06-17 00:02:36', '{\"monto\":\"450000.00\",\"metodo_pago\":\"transferencia\",\"referencia\":null,\"comprobante\":\"comprobante_1750118556.pdf\"}', 2, NULL, 5009),
(12146, 'completado', '2025-06-17 00:31:33', '{\"monto\":\"4500000.00\",\"metodo_pago\":\"transferencia\",\"referencia\":null,\"comprobante\":\"comprobante_1750120293.png\"}', NULL, 102, 5010),
(12147, 'cancelado', '2025-06-17 03:40:23', '{\"monto\":\"450000.00\",\"metodo_pago\":\"transferencia\",\"referencia\":null,\"comprobante\":\"comprobante_1750131623.txt\"}', 2, NULL, 5011),
(12148, 'pendiente', '2025-06-17 03:45:25', '{\"monto\":\"450000.00\",\"metodo_pago\":\"transferencia\",\"referencia\":null,\"comprobante\":\"comprobante_1750131925.txt\"}', 2, NULL, 5011),
(12149, 'completado', '2025-06-20 03:40:57', '{\"monto\":\"5000.00\",\"metodo_pago\":\"transferencia\",\"referencia\":null,\"comprobante\":\"comprobante_1750390857.jpeg\"}', NULL, 101, 5012),
(12150, 'pendiente', '2025-06-24 02:57:27', '{\"monto\":\"20000.00\",\"metodo_pago\":\"transferencia\",\"referencia\":null,\"comprobante\":\"comprobante_1750733847.jpeg\"}', 1, NULL, 5013),
(12151, 'completado', '2025-06-24 13:52:50', '{\"monto\":\"340000.00\",\"metodo_pago\":\"transferencia\",\"referencia\":null,\"comprobante\":\"comprobante_1750773170.png\"}', 3, NULL, 5014);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `correo` varchar(200) NOT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `rol` enum('estudiante','docente','administrador','cliente') NOT NULL,
  `fecha_creacion` date DEFAULT curdate(),
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `foto_perfil` varchar(255) DEFAULT 'icon1.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `telefono`, `correo`, `contraseña`, `rol`, `fecha_creacion`, `estado`, `foto_perfil`) VALUES
(3001, 'Lidia', 'Gutierrez', '1234567890', 'idagz#09@gmail.com', '$2y$10$ROeYnNPxBggFU12f0m0.S.GcpryvFO2CWzGZD/oFAHQl9mk4FrHva', 'docente', '2025-03-21', 'activo', 'icon1.png'),
(4001, 'Lidia', 'Gutierrez', '0987654321', 'lidiag9@gmail.com', '$2y$10$5Z/PKW7/FQcmEnaARMO05O44hwheZ6K0o/RjUQ9Hm63gcO4tSjK5q', 'administrador', '2025-03-21', 'activo', 'icon1.png'),
(5001, 'María', 'Lopez', '5546543212', 'maria@gmail.com', '$2y$10$vCFbCyM.G08CA8kQh9WOl.A.Lzne8NCsaSQ3/19jHkFUPbcZpGxNO', 'estudiante', '2025-03-21', 'activo', 'icon2.png'),
(5002, 'luisa', 'hernandez', '3195862728', 'luisahernandez12@gmail.com', NULL, 'cliente', '2025-05-05', 'activo', 'icon1.png'),
(5003, 'mary', 'niño', '31958627765', 'mary12@gmail.com', NULL, 'cliente', '2025-05-05', 'activo', 'icon1.png'),
(5004, 'Alejandra', 'Acosta', '3115622124', 'AlejaAcosta@gmail.com', NULL, 'cliente', '2025-05-05', 'activo', 'icon1.png'),
(5008, 'sabrina', 'carpinteria', '3245678789', 'sabri@gmail.com', NULL, 'cliente', '2025-06-17', 'activo', 'icon1.png'),
(5009, 'norma', 'sanchez', '4545454566', 'norma@gmail.com', '$2y$10$ujRkyC0U1gy975SOgiBUwOm.qVPhXXchFymgg.RmVAUBB4jra3odS', 'estudiante', '2025-06-17', 'activo', 'icon1.png'),
(5010, 'pepito', 'perez', '23232323', 'pepe@gmail.com', NULL, 'estudiante', '2025-06-17', 'activo', 'icon1.png'),
(5011, 'po', 'pi', '888787878787', 'popi@gmail.com', NULL, 'cliente', '2025-06-17', 'activo', 'icon1.png'),
(5012, 'Juliana', 'Rincón', '3204568978', 'juliana06@gmail.com', '$2y$10$ArrP72U.OESmy51HZ9kNvu6INHAdpKa2CkzUQtIDs9Z.QZrOrWOWC', 'estudiante', '2025-06-20', 'activo', 'icon1.png'),
(5013, 'sara petunia', 'lozano carvajal', '32456789', 'petunia@gmail.com', NULL, 'cliente', '2025-06-24', 'activo', 'icon1.png'),
(5014, 'sara', 'pulido', '3195862728', 'sara1990pulido@gmail.com', '$2y$10$VqceWT.3jiKvcTSzJMusyudkzxetDBG49o8x.eEwXqqRJ0wapWpDW', 'estudiante', '2025-06-24', 'activo', 'icon1.png');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD PRIMARY KEY (`id_evidencia`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `foro`
--
ALTER TABLE `foro`
  ADD PRIMARY KEY (`id_foro`);

--
-- Indices de la tabla `foro_comentarios`
--
ALTER TABLE `foro_comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_foro` (`id_foro`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_comentario_padre` (`id_comentario_padre`);

--
-- Indices de la tabla `foro_participantes`
--
ALTER TABLE `foro_participantes`
  ADD PRIMARY KEY (`id_participacion`),
  ADD KEY `id_foro` (`id_foro`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD KEY `id_pago` (`id_pago`),
  ADD KEY `fk_inscripcion_usuario` (`id_usuario`),
  ADD KEY `fk_inscripcion_curso` (`id_curso`),
  ADD KEY `fk_inscripcion_modulo` (`id_modulo`);

--
-- Indices de la tabla `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id_material`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id_modulo`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_comentario` (`id_comentario`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `fk_pago_curso` (`id_curso`),
  ADD KEY `fk_pago_modulo` (`id_modulo`),
  ADD KEY `fk_pago_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  MODIFY `id_evidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `foro`
--
ALTER TABLE `foro`
  MODIFY `id_foro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=506;

--
-- AUTO_INCREMENT de la tabla `foro_comentarios`
--
ALTER TABLE `foro_comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5084;

--
-- AUTO_INCREMENT de la tabla `foro_participantes`
--
ALTER TABLE `foro_participantes`
  MODIFY `id_participacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50542;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3439;

--
-- AUTO_INCREMENT de la tabla `material`
--
ALTER TABLE `material`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12152;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5015;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD CONSTRAINT `evidencias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `evidencias_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `evidencias_ibfk_3` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `foro_comentarios`
--
ALTER TABLE `foro_comentarios`
  ADD CONSTRAINT `fk_comentario_padre` FOREIGN KEY (`id_comentario_padre`) REFERENCES `foro_comentarios` (`id_comentario`) ON DELETE CASCADE,
  ADD CONSTRAINT `foro_comentarios_ibfk_1` FOREIGN KEY (`id_foro`) REFERENCES `foro` (`id_foro`) ON DELETE CASCADE,
  ADD CONSTRAINT `foro_comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `foro_participantes`
--
ALTER TABLE `foro_participantes`
  ADD CONSTRAINT `foro_participantes_ibfk_1` FOREIGN KEY (`id_foro`) REFERENCES `foro` (`id_foro`) ON DELETE CASCADE,
  ADD CONSTRAINT `foro_participantes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `fk_inscripcion_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
  ADD CONSTRAINT `fk_inscripcion_modulo` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`),
  ADD CONSTRAINT `fk_inscripcion_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `inscripcion_ibfk_4` FOREIGN KEY (`id_pago`) REFERENCES `pago` (`id_pago`) ON DELETE CASCADE;

--
-- Filtros para la tabla `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `material_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD CONSTRAINT `modulo_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `notificaciones_ibfk_2` FOREIGN KEY (`id_comentario`) REFERENCES `foro_comentarios` (`id_comentario`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `fk_pago_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
  ADD CONSTRAINT `fk_pago_modulo` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`),
  ADD CONSTRAINT `fk_pago_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
