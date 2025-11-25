-- Agregar columna tipo_fac a tabla empresas
ALTER TABLE empresas
    ADD COLUMN tipo_fac VARCHAR(50) DEFAULT 'Modelo PDF' AFTER actividad;

-- Opcional: actualizar valor por defecto para empresas existentes
UPDATE empresas SET tipo_fac = 'Modelo PDF' WHERE tipo_fac IS NULL OR tipo_fac = '';
