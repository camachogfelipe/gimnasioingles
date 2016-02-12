-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 23-11-2011 a las 15:50:48
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `gimnasioingles`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario`
--

CREATE TABLE IF NOT EXISTS `calendario` (
  `cal_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador de registro',
  `cal_dia` int(2) NOT NULL COMMENT 'dia',
  `cal_mes` int(2) NOT NULL COMMENT 'mes',
  `cal_year` int(4) NOT NULL COMMENT 'año',
  `cal_titulo` varchar(500) NOT NULL COMMENT 'titulo',
  `cal_descripcion_corta` varchar(150) NOT NULL COMMENT 'descripcion corta tooltips',
  `cal_descripcion` text NOT NULL COMMENT 'descripcion',
  `cal_vista` enum('I','E') NOT NULL DEFAULT 'I' COMMENT 'lugar donde se ve',
  `cat_id` int(11) NOT NULL COMMENT 'categoria',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registra',
  PRIMARY KEY (`cal_id`),
  KEY `FK_calendario_categoria` (`cat_id`),
  KEY `FK_calendario_usuario` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `cat_titulo` varchar(200) NOT NULL COMMENT 'titulo',
  `cat_descripcion` varchar(500) NOT NULL COMMENT 'descripción',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galerias`
--

CREATE TABLE IF NOT EXISTS `galerias` (
  `gal_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `gal_titulo` varchar(200) NOT NULL COMMENT 'titulo',
  `gal_descripcion` varchar(500) NOT NULL COMMENT 'descripción',
  `gal_archivos` text NOT NULL COMMENT 'archivos galeria',
  `gal_fecha_creada` date NOT NULL COMMENT 'fecha de creación',
  `gal_fecha_modificada` datetime NOT NULL COMMENT 'fecha de última modificación',
  `usr_id_creador` int(11) NOT NULL COMMENT 'usuario que crea',
  `usr_id_ultimo_modifico` int(11) NOT NULL COMMENT 'usuario que modifico última vez',
  PRIMARY KEY (`gal_id`),
  KEY `FK_galerias_usuario_creador` (`usr_id_creador`),
  KEY `FK_galerias_usuario_modifica` (`usr_id_ultimo_modifico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucional`
--

CREATE TABLE IF NOT EXISTS `institucional` (
  `inst_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `inst_titulo` varchar(300) NOT NULL COMMENT 'titulo',
  `inst_descripcion` text NOT NULL COMMENT 'descripcion',
  `inst_fecha_creado` datetime NOT NULL COMMENT 'fecha de creacion',
  `inst_fecha_modificado` date NOT NULL COMMENT 'fecha de la última modificación',
  `inst_archivo_pdf` varchar(500) DEFAULT NULL COMMENT 'si tiene archivo pdf',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registra',
  PRIMARY KEY (`inst_id`),
  KEY `FK_institucional_usuario` (`usr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE IF NOT EXISTS `niveles` (
  `niv_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `niv_nombre` varchar(500) NOT NULL COMMENT 'nombre del nivel',
  `niv_descripcion` text NOT NULL COMMENT 'descripcion del nivel',
  `niv_equivalente` varchar(500) NOT NULL COMMENT 'equivalente en otros planteles',
  `niv_rango_edad` varchar(100) NOT NULL COMMENT 'rango de edad que maneja el nivel',
  `niv_fecha_actualizado` date NOT NULL COMMENT 'fecha de actualización',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registra',
  PRIMARY KEY (`niv_id`),
  KEY `FK_niveles_usuario` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE IF NOT EXISTS `noticias` (
  `not_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `not_titulo` varchar(200) NOT NULL COMMENT 'titulo',
  `not_texto` varchar(500) NOT NULL COMMENT 'texto',
  `not_fecha` date NOT NULL COMMENT 'fecha de la noticia',
  `not_activa` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'activa o no',
  `not_permanente` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'si es permanente',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registra',
  PRIMARY KEY (`not_id`),
  KEY `FK_noticias_usuario` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador de usuario',
  `usr_nombre` varchar(200) NOT NULL COMMENT 'nombre',
  `usr_apellido` varchar(400) NOT NULL COMMENT 'apellidos',
  `usr_email` varchar(500) NOT NULL COMMENT 'email',
  `usr_login` varchar(20) NOT NULL COMMENT 'logn',
  `usr_clave` varchar(500) NOT NULL COMMENT 'clave',
  `usr_fecha_creacion` date NOT NULL COMMENT 'fecha de creacion',
  `usr_fecha_ultimo_acceso` datetime NOT NULL COMMENT 'fecha del último acceso',
  `usr_tipo_usuario` enum('A','NA') NOT NULL DEFAULT 'NA' COMMENT 'tipo de usuario',
  `usr_activo` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'activo o no',
  `usr_token` varchar(500) NOT NULL DEFAULT '0' COMMENT 'token de recuperacion de contraseña',
  `usr_institucional` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'permiso acceso institucional',
  `usr_calendario` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'permiso acceso calendario',
  `usr_galeria` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'permiso acceso galeria',
  `usr_niveles` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'permiso acceso niveles',
  `usr_noticias` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'permiso acceso noticias',
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calendario`
--
ALTER TABLE `calendario`
  ADD CONSTRAINT `FK_calendario_usuario` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`),
  ADD CONSTRAINT `FK_calendario_categoria` FOREIGN KEY (`cat_id`) REFERENCES `categorias` (`cat_id`);

--
-- Filtros para la tabla `galerias`
--
ALTER TABLE `galerias`
  ADD CONSTRAINT `FK_galerias_usuario_modifica` FOREIGN KEY (`usr_id_ultimo_modifico`) REFERENCES `usuarios` (`usr_id`),
  ADD CONSTRAINT `FK_galerias_usuario_creador` FOREIGN KEY (`usr_id_creador`) REFERENCES `usuarios` (`usr_id`);

--
-- Filtros para la tabla `institucional`
--
ALTER TABLE `institucional`
  ADD CONSTRAINT `FK_institucional_usuario` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`);

--
-- Filtros para la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD CONSTRAINT `FK_niveles_usuario` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`);

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `FK_noticias_usuario` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
