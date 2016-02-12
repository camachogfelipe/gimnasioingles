/*
SQLyog Ultimate v9.20 
MySQL - 5.5.16 : Database - gimnasioingles
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


USE `gimnasio_ingles`;

/*Table structure for table `alianzas` */

DROP TABLE IF EXISTS `alianzas`;

CREATE TABLE `alianzas` (
  `ali_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificación de registro',
  `ali_nombre` varchar(300) NOT NULL COMMENT 'nombre de la alianza',
  `ali_web` varchar(500) NOT NULL COMMENT 'dirección web de la alianza',
  `ali_logo` varchar(1000) NOT NULL COMMENT 'logotipo de la alianza',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registro',
  PRIMARY KEY (`ali_id`),
  KEY `FK_alianzas_usr_id` (`usr_id`),
  CONSTRAINT `FK_alianzas_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='tabla de registro de alianzas';

/*Data for the table `alianzas` */

LOCK TABLES `alianzas` WRITE;

insert  into `alianzas`(`ali_id`,`ali_nombre`,`ali_web`,`ali_logo`,`usr_id`) values (1,'Cibercolegios','http://www.cibercolegios.com','sciberlogo.png',1);

UNLOCK TABLES;

/*Table structure for table `calendario` */

DROP TABLE IF EXISTS `calendario`;

CREATE TABLE `calendario` (
  `cal_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador de registro',
  `cal_dia` int(2) NOT NULL COMMENT 'dia',
  `cal_mes` int(2) NOT NULL COMMENT 'mes',
  `cal_year` int(4) NOT NULL COMMENT 'año',
  `cal_hora_inicio` varchar(10) DEFAULT NULL COMMENT 'Hora de inicio del evento',
  `cal_hora_fin` varchar(10) DEFAULT NULL COMMENT 'Hora de finalización del evento',
  `cal_titulo` varchar(500) NOT NULL COMMENT 'titulo',
  `cal_descripcion_corta` varchar(150) NOT NULL COMMENT 'descripcion corta tooltips',
  `cal_descripcion` text NOT NULL COMMENT 'descripcion',
  `cal_vista` enum('I','E') NOT NULL DEFAULT 'E' COMMENT 'lugar donde se ve',
  `cat_id` int(11) NOT NULL COMMENT 'categoria',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registra',
  PRIMARY KEY (`cal_id`),
  KEY `FK_calendario_categoria` (`cat_id`),
  KEY `FK_calendario_usuario` (`usr_id`),
  CONSTRAINT `FK_calendario_categoria` FOREIGN KEY (`cat_id`) REFERENCES `categorias` (`cat_id`),
  CONSTRAINT `FK_calendario_usuario` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `calendario` */

LOCK TABLES `calendario` WRITE;

UNLOCK TABLES;

/*Table structure for table `categorias` */

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `cat_titulo` varchar(200) NOT NULL COMMENT 'titulo',
  `cat_descripcion` varchar(500) NOT NULL COMMENT 'descripción',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `categorias` */

LOCK TABLES `categorias` WRITE;

UNLOCK TABLES;

/*Table structure for table `configuracion` */

DROP TABLE IF EXISTS `configuracion`;

CREATE TABLE `configuracion` (
  `configuracion_nombre` varchar(200) NOT NULL COMMENT 'nombre de la variable',
  `configuracion_valor` varchar(200) NOT NULL COMMENT 'valor de la variable',
  PRIMARY KEY (`configuracion_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tabla de configuración general';

/*Data for the table `configuracion` */

LOCK TABLES `configuracion` WRITE;

insert  into `configuracion`(`configuracion_nombre`,`configuracion_valor`) values ('correo_contacto','contacto@gimnasioingles.com');

UNLOCK TABLES;

/*Table structure for table `galerias` */

DROP TABLE IF EXISTS `galerias`;

CREATE TABLE `galerias` (
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
  KEY `FK_galerias_usuario_modifica` (`usr_id_ultimo_modifico`),
  CONSTRAINT `FK_galerias_usuario_creador` FOREIGN KEY (`usr_id_creador`) REFERENCES `usuarios` (`usr_id`),
  CONSTRAINT `FK_galerias_usuario_modifica` FOREIGN KEY (`usr_id_ultimo_modifico`) REFERENCES `usuarios` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `galerias` */

LOCK TABLES `galerias` WRITE;

UNLOCK TABLES;

/*Table structure for table `institucional` */

DROP TABLE IF EXISTS `institucional`;

CREATE TABLE `institucional` (
  `inst_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `inst_titulo` varchar(300) NOT NULL COMMENT 'titulo',
  `inst_descripcion` text NOT NULL COMMENT 'descripcion',
  `inst_fecha_creado` datetime NOT NULL COMMENT 'fecha de creacion',
  `inst_fecha_modificado` date NOT NULL COMMENT 'fecha de la última modificación',
  `inst_archivo_pdf` varchar(500) DEFAULT NULL COMMENT 'si tiene archivo pdf',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registra',
  PRIMARY KEY (`inst_id`),
  KEY `FK_institucional_usuario` (`usr_id`),
  CONSTRAINT `FK_institucional_usuario` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `institucional` */

LOCK TABLES `institucional` WRITE;

insert  into `institucional`(`inst_id`,`inst_titulo`,`inst_descripcion`,`inst_fecha_creado`,`inst_fecha_modificado`,`inst_archivo_pdf`,`usr_id`) values (1,'NUESTRO PROPÓSITO','<p>Somos una instituci&oacute;n educativa de calidad, apasionada por el desarrollo del potencial cognitivo, afectivo, comunicativo y moral de los ni&ntilde;os, al tiempo que forma a las familias y los colaboradores de la instituci&oacute;n.</p>','2011-11-25 17:52:44','2011-11-25','IdentidadCorporativaFINAL.pdf',1),(2,'NUESTRA META','<p>Ser una instituci&oacute;n educativa pr&oacute;spera, en continuo crecimiento, reconocida por ser l&iacute;der en la oferta de servicios especializados para los ni&ntilde;os y sus familias, brindando mayores oportunidades de desarrollo profesional y personal a sus colaboradores.</p>','2011-11-25 17:56:24','0000-00-00',NULL,1);

UNLOCK TABLES;

/*Table structure for table `niveles` */

DROP TABLE IF EXISTS `niveles`;

CREATE TABLE `niveles` (
  `niv_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `niv_nombre` varchar(500) NOT NULL COMMENT 'nombre del nivel',
  `niv_descripcion` text NOT NULL COMMENT 'descripcion del nivel',
  `niv_equivalente` varchar(500) NOT NULL COMMENT 'equivalente en otros planteles',
  `niv_rango_edad` varchar(100) NOT NULL COMMENT 'rango de edad que maneja el nivel',
  `niv_fecha_actualizado` date NOT NULL COMMENT 'fecha de actualización',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registra',
  PRIMARY KEY (`niv_id`),
  KEY `FK_niveles_usuario` (`usr_id`),
  CONSTRAINT `FK_niveles_usuario` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `niveles` */

LOCK TABLES `niveles` WRITE;

UNLOCK TABLES;

/*Table structure for table `noticias` */

DROP TABLE IF EXISTS `noticias`;

CREATE TABLE `noticias` (
  `not_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `not_titulo` varchar(36) NOT NULL COMMENT 'titulo',
  `not_texto` varchar(232) NOT NULL COMMENT 'texto',
  `not_fecha` date NOT NULL COMMENT 'fecha de la noticia',
  `not_activa` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'activa o no',
  `not_permanente` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'si es permanente',
  `usr_id` int(11) NOT NULL COMMENT 'usuario que registra',
  PRIMARY KEY (`not_id`),
  KEY `FK_noticias_usuario` (`usr_id`),
  CONSTRAINT `FK_noticias_usuario` FOREIGN KEY (`usr_id`) REFERENCES `usuarios` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `noticias` */

LOCK TABLES `noticias` WRITE;

UNLOCK TABLES;

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `usuarios` */

LOCK TABLES `usuarios` WRITE;

insert  into `usuarios`(`usr_id`,`usr_nombre`,`usr_apellido`,`usr_email`,`usr_login`,`usr_clave`,`usr_fecha_creacion`,`usr_fecha_ultimo_acceso`,`usr_tipo_usuario`,`usr_activo`,`usr_token`,`usr_institucional`,`usr_calendario`,`usr_galeria`,`usr_niveles`,`usr_noticias`) values (1,'Felipe','Camacho','felipe@cogroupsas.com','felipe','918923642dc3de0b5ae697fc0630de38','2011-11-23','2011-11-23 09:46:00','A','S','0','S','S','S','S','S'),(2,'Diana Isabel','Rodriguez Pinzón','dianaisabelrodriguezpinzon@gmail.com','dianarodriguez','e1d54e8d524d50925f425d48187808c8','2012-02-09','0000-00-00 00:00:00','A','S','0','S','S','S','S','S');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
