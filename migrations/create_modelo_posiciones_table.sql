-- Migra la tabla modelo_posiciones para guardar marcadores de plantilla por empresa
CREATE TABLE IF NOT EXISTS `modelo_posiciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `x_pct` decimal(8,4) NOT NULL,
  `y_pct` decimal(8,4) NOT NULL,
  `page` int(3) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `fk_modelo_posiciones_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
