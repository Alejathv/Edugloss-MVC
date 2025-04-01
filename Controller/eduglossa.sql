-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2025 a las 02:49:19
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
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1
) ;

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
  `estado` enum('disponible','cerrado') NOT NULL DEFAULT 'disponible',
  `imagen` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro`
--

CREATE TABLE `foro` (
  `id_foro` int(11) NOT NULL,
  `id_video` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','cerrado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion`
--

CREATE TABLE `inscripcion` (
  `id_inscripcion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `id_pago` int(11) NOT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activa','completada','cancelada') DEFAULT 'activa'
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_modulo` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('tarjeta','paypal','mercado_pago') NOT NULL,
  `estado` enum('pendiente','completado','cancelado') DEFAULT 'pendiente',
  `referencia_transaccion` varchar(255) DEFAULT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `detalles_pago` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles_pago`))
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
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('estudiante','docente','administrador') NOT NULL,
  `fecha_creacion` date DEFAULT curdate(),
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `especialidad` varchar(150) DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `telefono`, `correo`, `contraseña`, `rol`, `fecha_creacion`, `estado`, `especialidad`) VALUES
(3001, 'Lidia', 'Gutierrez', '1234567890', 'idagz#09@gmail.com', 'cinnamon', 'docente', '2025-03-21', 'activo', 'Manicure y Pedicure'),
(4001, 'Lidia', 'Gutierrez', '0987654321', 'lidiag9@gmail.com', 'passmnb', 'administrador', '2025-03-21', 'activo', NULL),
(5001, 'María', 'Lopez', '5546543212', 'marialopez@gmail.com', 'contra13', 'estudiante', '2025-03-21', 'activo', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `videos`
--

CREATE TABLE `videos` (
  `id_video` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `url_video` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`),
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
  ADD PRIMARY KEY (`id_foro`),
  ADD KEY `id_video` (`id_video`);

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
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_modulo` (`id_modulo`),
  ADD KEY `id_pago` (`id_pago`);

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
  ADD UNIQUE KEY `referencia_transaccion` (`referencia_transaccion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id_video`),
  ADD KEY `id_modulo` (`id_modulo`);

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
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  MODIFY `id_evidencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `foro`
--
ALTER TABLE `foro`
  MODIFY `id_foro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `foro_comentarios`
--
ALTER TABLE `foro_comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `foro_participantes`
--
ALTER TABLE `foro_participantes`
  MODIFY `id_participacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `videos`
--
ALTER TABLE `videos`
  MODIFY `id_video` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
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
-- Filtros para la tabla `foro`
--
ALTER TABLE `foro`
  ADD CONSTRAINT `foro_ibfk_1` FOREIGN KEY (`id_video`) REFERENCES `videos` (`id_video`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_3` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripcion_ibfk_4` FOREIGN KEY (`id_pago`) REFERENCES `pago` (`id_pago`) ON DELETE CASCADE;

--
-- Filtros para la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD CONSTRAINT `modulo_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
