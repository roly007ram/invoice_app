-- Agregar campo rubro_id a la tabla detalles
ALTER TABLE detalles ADD COLUMN rubro_id INT AFTER descripcion;
ALTER TABLE detalles ADD FOREIGN KEY (rubro_id) REFERENCES rubro(id);