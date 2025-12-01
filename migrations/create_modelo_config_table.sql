-- Migra la tabla modelo_config para guardar ajustes de plantilla por empresa
CREATE TABLE IF NOT EXISTS `modelo_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) NOT NULL,
  `page_width_mm` int(5) NOT NULL DEFAULT 80,
  `font_name` varchar(100) NOT NULL DEFAULT 'Arial',
  `font_size` int(3) NOT NULL DEFAULT 10,
  `font_bold` tinyint(1) NOT NULL DEFAULT 0,
  `font_italic` tinyint(1) NOT NULL DEFAULT 0,
  `font_underline` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `fk_modelo_config_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
