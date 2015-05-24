-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 23-05-2015 a las 17:22:56
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `AlohaDB`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Alquileres`
--

CREATE TABLE IF NOT EXISTS `Alquileres` (
  `Id_H` int(11) NOT NULL,
  `Id_P` int(11) NOT NULL,
  `FechaIni` date NOT NULL,
  `FechaFin` date NOT NULL,
  `EmailInvitado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Alquileres`
--

INSERT INTO `Alquileres` (`Id_H`, `Id_P`, `FechaIni`, `FechaFin`, `EmailInvitado`) VALUES
(1, 2, '2015-05-28', '2015-05-31', 'huesped@demicorason'),
(1, 2, '2015-06-04', '2015-06-05', 'huesped@demicorason');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Habitaciones`
--

CREATE TABLE IF NOT EXISTS `Habitaciones` (
  `Id_H` int(11) NOT NULL,
  `Id_P` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Habitaciones`
--

INSERT INTO `Habitaciones` (`Id_H`, `Id_P`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Imagenes`
--

CREATE TABLE IF NOT EXISTS `Imagenes` (
  `Ruta` varchar(30) NOT NULL,
  `Id_P` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Propiedad`
--

CREATE TABLE IF NOT EXISTS `Propiedad` (
  `Id_P` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` varchar(5000) NOT NULL,
  `Estrellas` int(11) NOT NULL DEFAULT '0',
  `Direccion` varchar(150) NOT NULL,
  `Tipo` enum('Casa','Hotel','Piso') NOT NULL,
  `Pension` enum('Ninguna','Media','Completa') NOT NULL,
  `Zona` enum('Centro','Zaidín','Chana','Estación de autobuses') NOT NULL,
  `Precio` int(11) NOT NULL,
  `Habitaciones` int(11) NOT NULL,
  `Garaje` int(11) NOT NULL,
  `Seguridad` int(11) NOT NULL,
  `AireAcondicionado` int(11) NOT NULL,
  `Balcon` int(11) NOT NULL,
  `Piscina` int(11) NOT NULL,
  `Internet` int(11) NOT NULL,
  `Calefaccion` int(11) NOT NULL,
  `TV` int(11) NOT NULL,
  `Jardin` int(11) NOT NULL,
  `Telefono` int(11) NOT NULL,
  `Email` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Propiedad`
--

INSERT INTO `Propiedad` (`Id_P`, `Nombre`, `Descripcion`, `Estrellas`, `Direccion`, `Tipo`, `Pension`, `Zona`, `Precio`, `Habitaciones`, `Garaje`, `Seguridad`, `AireAcondicionado`, `Balcon`, `Piscina`, `Internet`, `Calefaccion`, `TV`, `Jardin`, `Telefono`, `Email`) VALUES
(2, 'Salón de JC', 'Tiene sofá', 0, 'Calle sol 25 1ºB', 'Piso', 'Ninguna', 'Centro', 120, 3, 0, 0, 0, 1, 0, 1, 1, 1, 0, 1, 'hostelero@demicorason.com'),
(3, 'El salón de troy', 'Recién limpico y tal', 0, 'Calle Sor Cristina mesa 1 2ºA', 'Piso', 'Ninguna', 'Zaidín', 400, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 'hostelero@demicorason.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE IF NOT EXISTS `Usuario` (
  `Email` varchar(50) NOT NULL,
  `Password` varchar(40) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `Apellidos` varchar(80) NOT NULL,
  `Telefono` int(11) NOT NULL,
  `Foto` varchar(100) NOT NULL DEFAULT 'invitado.jpg',
  `Hostelero` int(11) NOT NULL,
  `Empresa` varchar(100) DEFAULT NULL,
  `NIF` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`Email`, `Password`, `Nombre`, `Apellidos`, `Telefono`, `Foto`, `Hostelero`, `Empresa`, `NIF`) VALUES
('hostelero@demicorason.com', 'hostelero', 'Hostelero', 'Hostelerín Hostelerete', 662015816, 'invitado.jpg', 1, 'Hosteleros SA', 'A1234567A'),
('huesped@demicorason', 'huesped', 'Huésped', 'Huespedcito Huespencio', 646882512, 'invitado.jpg', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Valorar`
--

CREATE TABLE IF NOT EXISTS `Valorar` (
  `Id_C` int(11) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Id_P` int(11) NOT NULL,
  `Estrellas` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Comentario` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Valorar`
--

INSERT INTO `Valorar` (`Id_C`, `Email`, `Id_P`, `Estrellas`, `Fecha`, `Comentario`) VALUES
(1, 'huesped@demicorason', 2, 3, '2015-05-22', 'Comentario 1!'),
(2, 'huesped@demicorason', 2, 3, '2015-05-23', 'Comentario 2!'),
(3, 'huesped@demicorason', 2, 3, '2015-05-24', 'Comentario 3!'),
(4, 'huesped@demicorason', 2, 3, '2015-05-25', 'Comentario 4!'),
(5, 'huesped@demicorason', 2, 3, '2015-05-26', 'Comentario 5!'),
(6, 'huesped@demicorason', 2, 3, '2015-05-27', 'Comentario 6!'),
(7, 'huesped@demicorason', 2, 3, '2015-05-28', 'Comentario 7!');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Alquileres`
--
ALTER TABLE `Alquileres`
  ADD PRIMARY KEY (`Id_H`,`Id_P`,`FechaIni`,`FechaFin`),
  ADD KEY `EmailInvitado` (`EmailInvitado`),
  ADD KEY `Id_P` (`Id_P`);

--
-- Indices de la tabla `Habitaciones`
--
ALTER TABLE `Habitaciones`
  ADD PRIMARY KEY (`Id_H`,`Id_P`),
  ADD KEY `Id_P` (`Id_P`);

--
-- Indices de la tabla `Imagenes`
--
ALTER TABLE `Imagenes`
  ADD PRIMARY KEY (`Ruta`),
  ADD KEY `Id_P` (`Id_P`);

--
-- Indices de la tabla `Propiedad`
--
ALTER TABLE `Propiedad`
  ADD PRIMARY KEY (`Id_P`),
  ADD KEY `Email` (`Email`);

--
-- Indices de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`Email`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indices de la tabla `Valorar`
--
ALTER TABLE `Valorar`
  ADD PRIMARY KEY (`Id_C`),
  ADD KEY `Id_P` (`Id_P`),
  ADD KEY `Email` (`Email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Habitaciones`
--
ALTER TABLE `Habitaciones`
  MODIFY `Id_H` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `Propiedad`
--
ALTER TABLE `Propiedad`
  MODIFY `Id_P` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `Valorar`
--
ALTER TABLE `Valorar`
  MODIFY `Id_C` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Alquileres`
--
ALTER TABLE `Alquileres`
  ADD CONSTRAINT `Alquileres_ibfk_1` FOREIGN KEY (`EmailInvitado`) REFERENCES `Usuario` (`Email`),
  ADD CONSTRAINT `Alquileres_ibfk_2` FOREIGN KEY (`Id_P`) REFERENCES `Propiedad` (`Id_P`),
  ADD CONSTRAINT `Alquileres_ibfk_3` FOREIGN KEY (`Id_H`) REFERENCES `Habitaciones` (`Id_H`);

--
-- Filtros para la tabla `Habitaciones`
--
ALTER TABLE `Habitaciones`
  ADD CONSTRAINT `Habitaciones_ibfk_1` FOREIGN KEY (`Id_P`) REFERENCES `Propiedad` (`Id_P`);

--
-- Filtros para la tabla `Imagenes`
--
ALTER TABLE `Imagenes`
  ADD CONSTRAINT `Imagenes_ibfk_1` FOREIGN KEY (`Id_P`) REFERENCES `Propiedad` (`Id_P`);

--
-- Filtros para la tabla `Propiedad`
--
ALTER TABLE `Propiedad`
  ADD CONSTRAINT `Propiedad_ibfk_1` FOREIGN KEY (`Email`) REFERENCES `Usuario` (`Email`);

--
-- Filtros para la tabla `Valorar`
--
ALTER TABLE `Valorar`
  ADD CONSTRAINT `Valorar_ibfk_1` FOREIGN KEY (`Id_P`) REFERENCES `Propiedad` (`Id_P`),
  ADD CONSTRAINT `Valorar_ibfk_2` FOREIGN KEY (`Email`) REFERENCES `Usuario` (`Email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
