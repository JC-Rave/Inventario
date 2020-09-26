DROP DATABASE IF EXISTS `tecnoacademia`;
CREATE DATABASE IF NOT EXISTS `tecnoacademia` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;
USE `tecnoacademia`; 
  
CREATE TABLE `categorias` (
  `id_categoria` TINYINT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL, 
  `nombre_categoria` VARCHAR(40) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `descripcion_categoria` VARCHAR(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` SET('a','i') COLLATE utf8_spanish2_ci DEFAULT 'a'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci; 
 
CREATE TABLE `unidad_medida` (
  `id_unidad` TINYINT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `nombre_unidad` VARCHAR(40) COLLATE utf8_spanish2_ci DEFAULT NULL, 
  `estado` SET('a','i') COLLATE utf8_spanish2_ci DEFAULT 'a'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `tipo_usuarios` (
  `id_tipo` TINYINT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `nombre_tipo` VARCHAR(15) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci; 

CREATE TABLE `lineas` (
  `id_linea` TINYINT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `nombre_linea` VARCHAR(25) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `estados_productos` (
  `id_estado` TINYINT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `descripcion_estado` VARCHAR(200) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `personas_externas` (
  `documento_exterior` CHAR(11) COLLATE utf8_spanish2_ci PRIMARY KEY NOT NULL,
  `nombre_exterior` VARCHAR(60) COLLATE utf8_spanish2_ci NOT NULL,
  `empresa_exterior` VARCHAR(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cargo_exterior` VARCHAR(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono_exterior` CHAR(11) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `proveedores` (
  `id_proveedor` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `nit` VARCHAR(20) COLLATE utf8_spanish2_ci UNIQUE NOT NULL,
  `nombre_proveedor` VARCHAR(100) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono_proveedor` CHAR(11) COLLATE utf8_spanish2_ci NOT NULL,
  `correo_proveedor` VARCHAR(40) COLLATE utf8_spanish2_ci UNIQUE NOT NULL,
  `estado` SET('a','i') COLLATE utf8_spanish2_ci DEFAULT 'a',
  `url` VARCHAR(140) COLLATE utf8_spanish2_ci UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `personas` (
  `documento_persona` CHAR(11) COLLATE utf8_spanish2_ci PRIMARY KEY NOT NULL,
  `nombre_persona` VARCHAR(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_persona` VARCHAR(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono_persona` CHAR(11) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `galeria_productos` (
  `id_galeria` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `nombre` VARCHAR(50) COLLATE utf8_spanish2_ci UNIQUE NOT NULL,
  `imagen` VARCHAR(50) COLLATE utf8_spanish2_ci UNIQUE NOT NULL,
  `tipo_img` SET('devolutivo','consumible') COLLATE utf8_spanish2_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `usuarios` (
  `id_usuario` CHAR(11) COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` VARCHAR(100) COLLATE utf8_spanish2_ci UNIQUE NOT NULL,
  `password` VARCHAR(255) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_usuario` TINYINT(3) UNSIGNED DEFAULT '3',
  `linea` TINYINT(3) UNSIGNED NOT NULL,
  `img` VARCHAR(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` SET('a','i') COLLATE utf8_spanish2_ci DEFAULT 'a',
  FOREIGN KEY(`id_usuario`) REFERENCES `personas`(`documento_persona`),
  FOREIGN KEY(`tipo_usuario`) REFERENCES `tipo_usuarios`(`id_tipo`),
  FOREIGN KEY(`linea`) REFERENCES `lineas`(`id_linea`),
  PRIMARY KEY(`id_usuario`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `productos` (
  `id_producto` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `categoria_producto` TINYINT(3) UNSIGNED DEFAULT NULL,
  `estado_producto` TINYINT(3) UNSIGNED DEFAULT NULL,
  `unidad_medida` TINYINT(3) UNSIGNED DEFAULT NULL,
  `linea_producto` TINYINT(3) UNSIGNED DEFAULT NULL,
  `usuario_producto` CHAR(11) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_producto` VARCHAR(100) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion_producto` TEXT COLLATE utf8_spanish2_ci DEFAULT NULL,
  `precio_producto` FLOAT(10,2) DEFAULT NULL,
  `tipo_producto` SET('Consumible', 'Devolutivo','Pedido') COLLATE utf8_spanish2_ci NOT NULL,
  `imagenp` INT(11) UNSIGNED DEFAULT NULL,
  FOREIGN KEY(`categoria_producto`) REFERENCES `categorias`(`id_categoria`),
  FOREIGN KEY(`estado_producto`) REFERENCES `estados_productos`(`id_estado`),
  FOREIGN KEY(`unidad_medida`) REFERENCES `unidad_medida`(`id_unidad`),
  FOREIGN KEY(`linea_producto`) REFERENCES `lineas`(`id_linea`),
  FOREIGN KEY(`usuario_producto`) REFERENCES `usuarios`(`id_usuario`),
  FOREIGN KEY(`imagenp`) REFERENCES `galeria_productos`(`id_galeria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `urls_productos` (
  `id_proveedor` INT(10) UNSIGNED NOT NULL,
  `id_producto` INT(10) UNSIGNED NOT NULL,
  `precio` FLOAT(10,2) UNSIGNED NOT NULL,
  `descripcion` TEXT NOT NULL,
  FOREIGN KEY(`id_proveedor`) REFERENCES `proveedores`(`id_proveedor`),
  FOREIGN KEY(`id_producto`) REFERENCES `productos`(`id_producto`),
  PRIMARY KEY(`id_proveedor`,`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `consumibles` (
  `id_consumible` INT(10) UNSIGNED NOT NULL,
  `cantidad_consumible` SMALLINT(5) NOT NULL,
  FOREIGN KEY(`id_consumible`) REFERENCES `productos`(`id_producto`),
  PRIMARY KEY(`id_consumible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `historial_consumible` (
  `fecha` DATE NOT NULL,
  `consumible` INT(10) UNSIGNED NOT NULL,
  `cantidad` SMALLINT(5) NOT NULL,
  FOREIGN KEY(`consumible`) REFERENCES `consumibles`(`id_consumible`),
  PRIMARY KEY(`fecha`, `consumible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `devolutivo` (
  `id_devolutivo` INT(10) UNSIGNED NOT NULL,
  `placa` VARCHAR(30) COLLATE utf8_spanish2_ci NOT NULL,
  `codigo_sena` VARCHAR(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `serial` VARCHAR(30) COLLATE utf8_spanish2_ci NOT NULL,
  FOREIGN KEY(`id_devolutivo`) REFERENCES `productos`(`id_producto`),
  PRIMARY KEY(`id_devolutivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `mantenimiento_equipos` (
  `devolutivo` INT(10) UNSIGNED NOT NULL,
  `registrado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE,
  `tipo_matenimiento` SET('Correctivo','Preventivo') COLLATE utf8_spanish2_ci NOT NULL,
  `estado_matenimiento` SET('Vigente','Expirado','Ahora','Anulado','Terminado','En proceso') COLLATE utf8_spanish2_ci NOT NULL,
  FOREIGN KEY(`devolutivo`) REFERENCES `devolutivo`(`id_devolutivo`),
  PRIMARY KEY(`devolutivo`,`registrado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `pedidos` (
  `id_pedido` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `fecha_pedido` DATETIME DEFAULT NULL,
  `fecha_entregado` DATETIME DEFAULT NULL,
  `usuario_pedido` CHAR(11) COLLATE utf8_spanish2_ci NOT NULL,
  `total` FLOAT(10,2) DEFAULT NULL,
  `estado_pedido` SET('En proceso','Cancelado','Pendiente','Entregado') COLLATE utf8_spanish2_ci NOT NULL,
  FOREIGN KEY(`usuario_pedido`) REFERENCES `usuarios`(`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `detalle_pedido` (
  `pedido` INT(10) UNSIGNED NOT NULL,
  `producto` INT(10) UNSIGNED NOT NULL,
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `cantidad` SMALLINT(5) UNSIGNED NOT NULL,
  `precio_1` FLOAT(10,2) DEFAULT NULL,
  `precio_2` FLOAT(10,2) DEFAULT NULL,
  `precio_3` FLOAT(10,2) DEFAULT NULL,
  `total_producto` FLOAT(10,2) DEFAULT NULL,
  `proveedor_1` INT(10) UNSIGNED NOT NULL,
  `proveedor_2` INT(10) UNSIGNED DEFAULT NULL,
  `proveedor_3` INT(10) UNSIGNED DEFAULT NULL,
  `descripcion` TEXT COLLATE utf8_spanish2_ci NOT NULL,
  FOREIGN KEY (`pedido`) REFERENCES `pedidos`(`id_pedido`),
  FOREIGN KEY (`producto`) REFERENCES `productos`(`id_producto`),
  FOREIGN KEY (`proveedor_1`) REFERENCES `proveedores`(`id_proveedor`),
  FOREIGN KEY (`proveedor_2`) REFERENCES `proveedores`(`id_proveedor`),
  FOREIGN KEY (`proveedor_3`) REFERENCES `proveedores`(`id_proveedor`),
  PRIMARY KEY (`pedido`,`producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `prestamos` (
  `id_prestamo` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `fecha_prestamo` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `linea_prestamo` TINYINT(3) UNSIGNED NOT NULL,
  `emisor` CHAR(11) COLLATE utf8_spanish2_ci NOT NULL,
  `receptor` CHAR(11) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` SET('En proceso','Pendiente','Devuelto','Cancelado') COLLATE utf8_spanish2_ci NOT NULL,
  FOREIGN KEY (`linea_prestamo`) REFERENCES `lineas`(`id_linea`),
  FOREIGN KEY (`emisor`) REFERENCES `usuarios`(`id_usuario`),
  FOREIGN KEY (`receptor`) REFERENCES `usuarios`(`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `detalle_prestamo` (
  `prestamo` INT(10) UNSIGNED NOT NULL,
  `producto` INT(10) UNSIGNED NOT NULL,
  FOREIGN KEY (`prestamo`) REFERENCES `prestamos`(`id_prestamo`),
  FOREIGN KEY (`producto`) REFERENCES `productos`(`id_producto`),
  PRIMARY KEY(`prestamo`,`producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `solicitudes` (
  `id_solicitud` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `usuario_solicitud` CHAR(11) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_solicitud` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `estado_solicitud` SET('En proceso','En prestamo','Cancelado','Terminado','Pausado') COLLATE utf8_spanish2_ci NOT NULL,
  `total_solicitud` FLOAT(10,2) DEFAULT NULL,
  FOREIGN KEY(`usuario_solicitud`) REFERENCES `usuarios`(`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `salidas` (
  `id_salida` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `solicitud_salida` INT(10) UNSIGNED NOT NULL,
  `producto_salida` INT(10) UNSIGNED NOT NULL,
  `fecha_salida` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `cantidad_salida` SMALLINT(5) UNSIGNED NOT NULL,
  `estado_salida` SET('En prestamo','Retornado','No retorna') COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_salida` SET('Prestamo','Definitiva') COLLATE utf8_spanish2_ci NOT NULL,
  `persona_exterior` CHAR(11) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `persona_usuario` CHAR(11) COLLATE utf8_spanish2_ci DEFAULT NULL,
  FOREIGN KEY(`solicitud_salida`) REFERENCES `solicitudes`(`id_solicitud`),
  FOREIGN KEY(`producto_salida`) REFERENCES `productos`(`id_producto`),
  FOREIGN KEY(`persona_exterior`) REFERENCES `personas_externas`(`documento_exterior`),
  FOREIGN KEY(`persona_usuario`) REFERENCES `usuarios`(`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


DELIMITER //
CREATE OR REPLACE FUNCTION reg_pedido(usuario_pedido CHAR(11), estado_pedido VARCHAR(10), fecha TINYINT(1)) RETURNS INT
  BEGIN
    DECLARE id INT;

    IF(fecha) THEN
      INSERT INTO `pedidos` VALUES(NULL, NULL, NOW(), usuario_pedido, 0, estado_pedido);
    ELSE
      INSERT INTO `pedidos` VALUES(NULL, NULL, NULL, usuario_pedido, 0, estado_pedido);  
    END IF;

    SELECT @@identity INTO id;
    RETURN id;
  END
//

CREATE OR REPLACE PROCEDURE editar_pedido(codigoPed INT, estado_pedido VARCHAR(10))
  BEGIN
    CASE estado_pedido
      WHEN 'Pendiente' THEN
        IF(SELECT `fecha_pedido` FROM `pedidos` WHERE `id_pedido`=codigoPed) IS NULL THEN
          UPDATE `pedidos` SET `estado_pedido`=estado_pedido, `fecha_pedido`=NOW() 
          WHERE `id_pedido`=codigoPed;
        ELSE
          UPDATE `pedidos` SET `estado_pedido`=estado_pedido 
          WHERE `id_pedido`=codigoPed;  
        END IF;
      WHEN 'Entregado' THEN
        UPDATE `pedidos` SET `estado_pedido`=estado_pedido, `fecha_entregado`=NOW() 
        WHERE `id_pedido`=codigoPed;
      ELSE
        UPDATE `pedidos` SET `estado_pedido`=estado_pedido 
        WHERE `id_pedido`=codigoPed;
    END CASE;
  END
//

CREATE OR REPLACE TRIGGER reg_historial AFTER INSERT ON `consumibles`
FOR EACH ROW
  BEGIN
    IF NEW.cantidad_consumible!=0 THEN
      INSERT INTO `historial_consumible` 
      VALUES(CURDATE(), NEW.id_consumible, NEW.cantidad_consumible);
    END IF;
  END
//

CREATE OR REPLACE TRIGGER edit_historial AFTER UPDATE ON `consumibles`
FOR EACH ROW
  BEGIN
  
    IF (SELECT `consumible` FROM `historial_consumible` 
    WHERE `fecha`=CURDATE() AND `consumible`=OLD.id_consumible) IS NULL THEN
      INSERT INTO `historial_consumible` 
      VALUES(CURDATE(), OLD.id_consumible, NEW.cantidad_consumible-OLD.cantidad_consumible);

    ELSE
      UPDATE `historial_consumible` SET `cantidad`= `cantidad`+(NEW.cantidad_consumible-OLD.cantidad_consumible) 
      WHERE `fecha`=CURDATE() AND `consumible`=OLD.id_consumible;  

      IF (SELECT `cantidad` FROM `historial_consumible`
      WHERE `fecha`=CURDATE() AND `cantidad`=0) IS NOT NULL THEN
        DELETE FROM `historial_consumible` WHERE `fecha`=CURDATE() AND `consumible`=OLD.id_consumible;

      END IF;  
    END IF;
  END
//  
DELIMITER ;


INSERT INTO `personas` (`documento_persona`, `nombre_persona`, `apellido_persona`, `telefono_persona`) VALUES
('1234567', 'Administrador', '8D', '1234567');

INSERT INTO `tipo_usuarios` (`id_tipo`, `nombre_tipo`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'INSTRUCTOR');

INSERT INTO `lineas` (`id_linea`, `nombre_linea`) VALUES
(1, 'TICs'),
(2, 'Biotecnología'),
(3, 'Nanotecnología'),
(4, 'Química'),
(5, 'Física'),
(6, 'Matemáticas y diseño'),
(7, 'Electrónica y robótica'),
(8, 'Administrativa');

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `password`, `tipo_usuario`, `linea`, `img`, `estado`) VALUES
('1234567', 'admin@gmail.com', '696057471e88364301e403977bb4384cb39fa65a8fd63c8ec912844568a378ae347b7b5c81b73b140d7cc26184592c84119ea5d3b1d5ad40c9a17a9116261fd2ao8YAjutTbMoAwC56SIbs4KvpRKsA0HRKCHK/9VpfyE=', 1, 1, NULL, 'a');

INSERT INTO `unidad_medida` (`id_unidad`, `nombre_unidad`, `estado`) VALUES
(1, 'Unidad', 'a');

INSERT INTO `estados_productos` (`id_estado`, `descripcion_estado`) VALUES
(1, 'Bueno'), 
(2, 'Bueno, Se usa'),
(3, 'Bueno, No usa'),
(4, 'En uso, Sin Mantenimiento'),
(5, 'Malo'),
(6, 'Se usa'),
(7, 'No se usa'),
(8, 'de baja');

--
-- usuario de la base de datos tecnoacademia = user: inventario - pass: UjSaxvxJVCqIy3kR
--
GRANT USAGE ON *.* TO 'inventario'@'localhost' IDENTIFIED BY PASSWORD '*3E4457F67B7C027485E56E13A5F5945FC3E8CFD1';
GRANT ALL PRIVILEGES ON `tecnoacademia`.* TO 'inventario'@'localhost'; 
