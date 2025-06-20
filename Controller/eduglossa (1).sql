-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-06-2025 a las 14:38:03
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
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `total` int(5) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Curso  Profesional', '...', 20000.00, '2025-03-20', '2025-03-28', 'cerrado'),
(2, 'uñas capping', '.....', 450000.00, '2025-06-20', '2025-06-28', 'disponible'),
(3, 'Curso  Profesional manicure intermedio', 'sncdcxnms vd', 340000.00, '2025-06-13', '2025-06-19', 'cerrado');

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
  `id_usuario` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `foro_comentarios`
--

INSERT INTO `foro_comentarios` (`id_comentario`, `id_foro`, `id_usuario`, `contenido`, `fecha`) VALUES
(5076, 505, 3001, 'holi', '2025-05-20 02:28:36'),
(5077, 505, 5001, 'holi', '2025-05-20 02:50:31');

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
  `estado` enum('activa','inactiva','cancelada') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(101, 1, 'Manicure Basic ', '...', 5000.00, 'disponible'),
(102, 2, 'modulo profesional', '.....', 4500000.00, 'disponible'),
(103, 1, 'manicure', '.....', 3000000.00, 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` enum('pendiente','completado','cancelado') DEFAULT 'pendiente',
  `referencia_pago` varchar(255) DEFAULT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `detalles_pago` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles_pago`)),
  `id_carrito` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `especialidad` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `telefono`, `correo`, `contraseña`, `rol`, `fecha_creacion`, `estado`, `especialidad`) VALUES
(3001, 'Lidia', 'Gutierrez', '1234567890', 'idagz#09@gmail.com', 'cinnamon', 'docente', '2025-03-21', 'activo', 'Manicure y Pedicure'),
(4001, 'Lidia', 'Gutierrez', '0987654321', 'lidiag9@gmail.com', 'passmnb', 'administrador', '2025-03-21', 'activo', NULL),
(5001, 'María', 'Lopez', '5546543212', 'marialopez@gmail.com', 'contra14', 'estudiante', '2025-03-21', 'activo', NULL),
(5002, 'luisa', 'hernandez', '3195862728', 'luisahernandez12@gmail.com', NULL, 'cliente', '2025-05-05', 'activo', NULL),
(5003, 'mary', 'niño', '31958627765', 'mary12@gmail.com', NULL, 'cliente', '2025-05-05', 'activo', NULL),
(5004, 'Alejandra', 'Acosta', '3115622124', 'AlejaAcosta@gmail.com', NULL, 'cliente', '2025-05-05', 'activo', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_modulo` (`id_modulo`);

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
  ADD KEY `id_usuario` (`id_usuario`);

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
  ADD KEY `id_pago` (`id_pago`);

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
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `fk_pago_carrito` (`id_carrito`),
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
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  MODIFY `id_evidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `foro`
--
ALTER TABLE `foro`
  MODIFY `id_foro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=506;

--
-- AUTO_INCREMENT de la tabla `foro_comentarios`
--
ALTER TABLE `foro_comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5078;

--
-- AUTO_INCREMENT de la tabla `foro_participantes`
--
ALTER TABLE `foro_participantes`
  MODIFY `id_participacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50542;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3435;

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
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12131;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5005;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE SET NULL,
  ADD CONSTRAINT `carrito_ibfk_3` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE SET NULL;

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
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `fk_pago_carrito` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`),
  ADD CONSTRAINT `fk_pago_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
