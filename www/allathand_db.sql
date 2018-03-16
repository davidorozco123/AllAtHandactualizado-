-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 02-03-2018 a las 07:29:30
-- Versión del servidor: 5.6.36-cll-lve
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `allathand_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_salidas`
--

CREATE TABLE IF NOT EXISTS `entradas_salidas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_productos` int(10) unsigned NOT NULL,
  `tipo` varchar(45) NOT NULL DEFAULT '',
  `hora` datetime(6) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id`,`id_productos`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `entradas_salidas`
--

INSERT INTO `entradas_salidas` (`id`, `id_productos`, `tipo`, `hora`, `cantidad`) VALUES
(2, 3, 'salida', '0000-00-00 00:00:00.000000', 123);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `tipo` varchar(25) NOT NULL,
  `existencia` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `tipo`, `existencia`, `descripcion`) VALUES
(2, 'mirinda', 'refresco', 100, 'refresco sabor naranja de 500 ml'),
(3, 'Sangría', 'Refresco', 0, 'Botella de 500'),
(4, 'arizona', 'refresco', 1000, 'pords'),
(5, 'polo', 'asdasdasd', 345, 'asdasdsd'),
(6, 'leche chavo', 'leche', 123, 'sadsdasd'),
(7, 'hola', 'asdasd', 100, 'asdasd');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
