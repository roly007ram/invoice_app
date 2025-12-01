# CHANGELOG: Sistema de Plantillas PDF para Facturas

**Versión:** 1.0  
**Fecha:** 25 de noviembre de 2025  
**Autor:** Implementación de Configuración de Modelos PDF

---

## Cambios Implementados

### 1. Frontend - `factura_.php`

#### Cambios:
- **Botón:** Agregado "Configuración de modelos" junto a "Imprimir por modelo"
- **Modal:** Nuevo modal `#configModeloModal` con:
  - Upload de PDF plantilla
  - Selector de variables a colocar
  - Editor visual (iframe + overlay)
  - Lista de marcadores colocados
  - **Nuevos controles:**
    - `#configPageWidth` (select 55/80/210 mm)
    - `#configFontName` (select Helvetica/Arial/Times/Courier)
    - `#configFontSize` (input numérico 6-48pt)

#### JavaScript:
- `loadModeloConfig(empresaId)` - Carga configuración al abrir modal
  - Ahora incluye `modelo_config` (ancho, fuente, tamaño)
  - Rellena controles con valores guardados
- `guardarConfigBtn` - Envía FormData con:
  - `empresa_id`
  - `posiciones` (JSON)
  - `modelo_pdf_file` (si se subió)
  - `page_width_mm` ⭐ NEW
  - `font_name` ⭐ NEW
  - `font_size` ⭐ NEW

---

### 2. Backend - `save_modelo_posiciones.php`

#### Cambios:
- **Lectura de nuevos parámetros:**
  - `$page_width_mm` de POST
  - `$font_name` de POST
  - `$font_size` de POST

- **Nueva lógica de persistencia:**
  - Crea tabla `modelo_config` si no existe (con UNIQUE en empresa_id)
  - Lee configuración actual de empresa
  - Usa valores nuevos si se envían, preserva existentes si no
  - INSERT o UPDATE según sea necesario
  - Usa transacción para atomicidad

- **Validación mejorada:**
  - page_width_mm: validación de rango (positivo)
  - font_name: trim y validación de no vacío
  - font_size: conversión a int, rango válido

---

### 3. Backend - `get_modelo_posiciones.php`

#### Cambios:
- **Crea tabla `modelo_config`** si no existe (mismo esquema que en save_modelo_posiciones)
- **Lee configuración actual:**
  - `page_width_mm`
  - `font_name`
  - `font_size`
- **Devuelve JSON extendido:**
  ```json
  {
    "success": true,
    "modelo_file": "modelo_8_1764053663.pdf",
    "posiciones": [...],
    "modelo_config": {
      "page_width_mm": 80,
      "font_name": "Helvetica",
      "font_size": 10
    }
  }
  ```

---

### 4. Backend - `imprimir_factura_pdf.php`

#### Cambios principales:

**1. Lee `modelo_config` desde BD:**
```php
$modelo_config = ['page_width_mm' => 80, 'font_name' => 'Helvetica', 'font_size' => 10];
// Query a modelo_config
// Valores por defecto si no existe
```

**2. Validación de dimensiones de template:**
```php
$origWidth = floatval($size['width']);
$origHeight = floatval($size['height']);
if ($origWidth <= 0 || $origHeight <= 0) {
    // Usar valores por defecto (A4)
}
```

**3. Cálculo de escala con validación:**
```php
$targetWidth = floatval($modelo_config['page_width_mm']);
$scale = $targetWidth / $origWidth;
// Validar escala en rango 0.1x a 10x
if ($scale < 0.1 || $scale > 10) {
    // Usar escala original
}
```

**4. AddPage con tamaño personalizado:**
```php
$pdf->AddPage('', [$targetWidth, $targetHeight]);
$pdf->useTemplate($tplIdx, 0, 0, $targetWidth);
```

**5. UseTemplate mejorado:**
- Origin en (0, 0) consistentemente
- Ancho escalado al `page_width_mm`
- Altura proporcional

**6. SetFont con valores guardados:**
```php
$fontToUse = $modelo_config['font_name'] ?: 'Helvetica';
$fontSize = intval($modelo_config['font_size']) ?: 10;
$pdf->SetFont($fontToUse, '', $fontSize);
```

**7. Posiciones escaladas dinámicamente:**
```php
// Usar targetWidth/targetHeight en lugar de $size
$x = ($xPct / 100.0) * $targetWidth;
$y = ($yPct / 100.0) * $targetHeight;
```

**8. Fallback mejorado (sin template):**
- Respeta `page_width_mm` al crear página
- Ajusta márgenes automáticamente para distintos anchos (55/80/210mm)
- Usa fuente y tamaño configurados

---

### 5. Base de Datos - Tabla `modelo_config`

**Migración:** `migrations/create_modelo_config_table.sql`

```sql
CREATE TABLE `modelo_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) NOT NULL,
  `page_width_mm` int(11) NOT NULL DEFAULT 80,
  `font_name` varchar(100) NOT NULL DEFAULT 'Helvetica',
  `font_size` int(3) NOT NULL DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empresa_id` (`empresa_id`),
  CONSTRAINT `fk_modelo_config_empresas` FOREIGN KEY (`empresa_id`) 
    REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

**Características:**
- UNIQUE en empresa_id: una configuración por empresa
- Foreign key con CASCADE: si empresa se elimina, config se borra
- Timestamps: auditoria automática

---

### 6. Scripts de Prueba (Nuevos)

#### `generate_test_template.php`
- Genera PDF plantilla simple (80x200mm)
- Incluye áreas marcadas para referencia visual
- Guarda en `pdfmodelo/test_template.pdf`
- Útil para pruebas sin plantilla real

#### `test_e2e.php`
- Script CLI para setup de pruebas end-to-end
- Crea tablas si no existen
- Inserta 13 posiciones de prueba
- Configura modelo_config (80mm, Helvetica, 10pt)
- Verifica datos en BD
- Proporciona instrucciones paso a paso

#### `diagnostico_pdf.php`
- Script CLI para análisis de PDF generado
- Muestra configuración y posiciones actuales
- Datos de factura a renderizar
- Consejos de ajuste manual
- Guía de rangos válidos para X%, Y%

---

### 7. Documentación (Nuevos)

#### `TESTING_E2E.md`
- Guía completa de pruebas end-to-end
- Flujo UI paso a paso
- Flujo CLI con ejemplos
- Ajustes comunes y soluciones
- Checklist de verificación
- Debugging troubleshooting

#### `RESUMEN_PLANTILLAS_PDF.md`
- Resumen ejecutivo de la funcionalidad
- Requisitos cumplidos
- Archivos modificados/creados
- Esquemas de BD
- Flujo de uso
- Limitaciones y próximos pasos

---

## Flujo de Datos

### Guardado de Configuración

```
Frontend (factura_.php)
  ↓
  FormData con: empresa_id, posiciones, page_width_mm, font_name, font_size, archivo PDF
  ↓
POST /save_modelo_posiciones.php
  ↓
  1. Validar empresa_id
  2. Guardar PDF en pdfmodelo/
  3. Actualizar empresas.modelo_pdf
  4. DELETE posiciones previas de empresa
  5. INSERT nuevas posiciones en modelo_posiciones
  6. Crear tabla modelo_config si no existe
  7. Comprobar si existe config para empresa
  8. UPDATE o INSERT en modelo_config
  9. Devolver JSON con success: true
  ↓
BD (tablas: empresas, modelo_posiciones, modelo_config)
```

### Carga de Configuración

```
Frontend (factura_.php)
  ↓
GET /get_modelo_posiciones.php?empresa_id=X
  ↓
  1. Leer empresas.modelo_pdf
  2. Leer posiciones de modelo_posiciones
  3. Crear tabla modelo_config si no existe
  4. Leer modelo_config
  5. Devolver JSON con modelo_file, posiciones, modelo_config
  ↓
Frontend carga controles con valores y renderiza preview
```

### Generación de PDF

```
Usuario (factura_.php)
  ↓
GET /imprimir_factura_pdf.php?factura_id=X
  ↓
  1. Obtener factura y empresa
  2. Leer modelo_config para empresa
  3. Leer modelo_posiciones para empresa
  4. Crear FPDI PDF
  5. Para cada página de plantilla:
     - Importar página
     - Calcular escala según page_width_mm
     - Crear nueva página con tamaño escalado
     - Usar template escalado
     - Para cada posición:
       * Renderizar campo con SetFont(font_name, font_size)
       * Colocar en (x%, y%) escalado
     - Para items:
       * Renderizar con offset de fila
  6. Output PDF (navegador o descarga)
```

---

## Validaciones Implementadas

| Validación | Ubicación | Descripción |
|-----------|-----------|------------|
| empresa_id > 0 | save_modelo_posiciones.php | Empresa válida |
| PDF type | save_modelo_posiciones.php | Solo .pdf |
| upload_err | save_modelo_posiciones.php | Sin errores de carga |
| page_width_mm | save_modelo_posiciones.php, imprimir_factura_pdf.php | Rango válido (positivo) |
| font_name | save_modelo_posiciones.php, imprimir_factura_pdf.php | No vacío, trim |
| font_size | save_modelo_posiciones.php, imprimir_factura_pdf.php | Int, > 0 |
| template dims | imprimir_factura_pdf.php | Validar width/height > 0 |
| scale range | imprimir_factura_pdf.php | 0.1x a 10x |
| x_pct, y_pct | DB y imprimir_factura_pdf.php | Float(8,4) |

---

## Mejoras de Robustez

1. **Transacciones:** Guardado de posiciones usa transacción para atomicidad
2. **Validación de tipos:** Conversión explícita de tipos (int, float, string)
3. **Valores por defecto:** Si config falta, usa defaults válidos
4. **Escalado seguro:** Valida dimensiones y escala antes de usar
5. **Error handling:** Try-catch en carga de PDF, manejo de excepciones
6. **Permisos:** chmod 0644 en archivos subidos
7. **Logging:** error_log para debugging en caso de fallo

---

## Retrocompatibilidad

- ✅ Funciona con/sin plantilla PDF
- ✅ Si modelo_config no existe, usa defaults
- ✅ Si modelo_posiciones está vacío, genera PDF simple
- ✅ Empresas sin configuración funcionan con valores por defecto
- ✅ Scripts CLI pueden ejecutarse sin dependencias externas

---

## Performance

- **Queries optimizadas:** Índices en empresa_id en ambas tablas
- **Caché:** Posiciones y config se leen una sola vez por generación
- **Lazy loading:** Tablas se crean al primero necesitarlo (no en setup)
- **Sin resize iterativo:** Escala se calcula una vez

---

## Seguridad

- ✅ Validación de sesión en endpoints
- ✅ Prepared statements en todas las queries
- ✅ Validación de tipo de archivo (PDF)
- ✅ Sanitización de rutas (uso de DIRECTORY_SEPARATOR)
- ✅ Restricción de permisos en archivos subidos
- ✅ Sin inyección SQL posible

---

## Próximas Mejoras Sugeridas

1. **Almacenamiento de blobs:** Guardar PDF en BD en lugar de filesystem
2. **Soporte TTF:** Registrar fuentes personalizadas
3. **Múltiples templates:** Permitir varias plantillas por empresa
4. **Versioning:** Historial de cambios en configuración
5. **UI mejorado:** Editor visual para arrastrar/editar marcadores
6. **Export/Import:** Guardar/cargar configuración como JSON

---

## Notas Técnicas

- FPDI v4.x compatible (setasign/fpdi)
- Requiere PHP 7.2+
- MySQL/MariaDB 5.7+
- Soporta templates multi-página (campo `page` en modelo_posiciones)
- Posiciones en porcentajes (0-100) para independencia de escala
- Offset de items fijo en 6mm por línea (configurable en código)

