-- Primero crear la tabla rubros
CREATE TABLE IF NOT EXISTS `rubros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar algunos rubros por defecto
INSERT INTO `rubros` (nombre, descripcion) VALUES
('Ferretería', 'Artículos de ferretería y construcción'),
('Celulares', 'Teléfonos móviles y accesorios'),
('Autopartes', 'Repuestos y accesorios para vehículos'),
('Lubricantes', 'Aceites y lubricantes'),
('Servicios', 'Servicios varios');

-- Agregar columna rubro_id a detalles sin constraint primero
ALTER TABLE `detalles`
ADD COLUMN IF NOT EXISTS `rubro_id` int(11) DEFAULT NULL;

-- Asignar rubros por defecto basado en descripciones
UPDATE detalles SET rubro_id = (SELECT id FROM rubros WHERE nombre = 'Ferretería')
WHERE LOWER(descripcion) LIKE '%hierro%'
   OR LOWER(descripcion) LIKE '%cemento%'
   OR LOWER(descripcion) LIKE '%chapa%'
   OR LOWER(descripcion) LIKE '%construcción%';

UPDATE detalles SET rubro_id = (SELECT id FROM rubros WHERE nombre = 'Celulares')
WHERE LOWER(descripcion) LIKE '%motorola%'
   OR LOWER(descripcion) LIKE '%xiaomi%'
   OR LOWER(descripcion) LIKE '%redmi%';

UPDATE detalles SET rubro_id = (SELECT id FROM rubros WHERE nombre = 'Autopartes')
WHERE LOWER(descripcion) LIKE '%volkswagen%'
   OR LOWER(descripcion) LIKE '%mercedes%'
   OR LOWER(descripcion) LIKE '%benz%'
   OR LOWER(descripcion) LIKE '%palier%'
   OR LOWER(descripcion) LIKE '%turbo%';

UPDATE detalles SET rubro_id = (SELECT id FROM rubros WHERE nombre = 'Lubricantes')
WHERE LOWER(descripcion) LIKE '%aceite%'
   OR LOWER(descripcion) LIKE '%helix%'
   OR LOWER(descripcion) LIKE '%shell%'
   OR LOWER(descripcion) LIKE '%rimula%'
   OR LOWER(descripcion) LIKE '%grasa%';

UPDATE detalles SET rubro_id = (SELECT id FROM rubros WHERE nombre = 'Servicios')
WHERE LOWER(descripcion) LIKE '%servicio%'
   OR LOWER(descripcion) LIKE '%flete%';

-- Ahora sí agregar la foreign key
ALTER TABLE `detalles`
ADD CONSTRAINT `fk_detalles_rubros`
FOREIGN KEY (`rubro_id`) REFERENCES `rubros`(`id`)
ON DELETE SET NULL;