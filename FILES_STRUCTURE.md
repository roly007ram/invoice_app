# Estructura de Archivos: Sistema de Plantillas PDF

## 📁 Árbol de Archivos (Nuevos y Modificados)

```
invoice_app/
├── 📄 MODIFICADOS (Código Principal)
│   ├── factura_.php                          ⭐ UI Modal + Controles
│   ├── save_modelo_posiciones.php            ⭐ Guardar config en BD
│   ├── get_modelo_posiciones.php             ⭐ Leer config de BD
│   └── imprimir_factura_pdf.php              ⭐ Generar PDF con config
│
├── 📄 CREADOS (Scripts de Prueba)
│   ├── generate_test_template.php            🧪 Generar PDF plantilla
│   ├── test_e2e.php                          🧪 Setup datos e2e
│   └── diagnostico_pdf.php                   🧪 Análisis y sugerencias
│
├── 📄 CREADOS (Documentación)
│   ├── QUICK_START.md                        📖 Inicio rápido (5 min)
│   ├── TESTING_E2E.md                        📖 Guía de pruebas
│   ├── RESUMEN_PLANTILLAS_PDF.md             📖 Especificación técnica
│   ├── CHANGELOG.md                          📖 Cambios implementados
│   └── FILES_STRUCTURE.md                    📖 Este archivo
│
├── 📁 migrations/
│   └── create_modelo_config_table.sql        🔧 Migración tabla config
│
├── 📁 pdfmodelo/                             📦 Plantillas PDF
│   ├── test_template.pdf                     🧪 Plantilla de prueba
│   ├── modelo_8_1764053663.pdf               📦 Plantillas de usuarios
│   └── ...otros PDFs...
│
└── 📁 (Rest of app structure)
    └── db_config.php                         [no cambios]
    └── ...other files...                     [no cambios]
```

---

## 🔄 Flujo de Datos

```
┌─────────────────────────────────────────────────────────────┐
│                    FRONT-END: factura_.php                  │
├─────────────────────────────────────────────────────────────┤
│  • Modal "Configuración de modelos"                         │
│  • Controles: Page Width, Font, Font Size                  │
│  • Editor visual: Colocar variables                         │
│  • Preview: iframe + overlay                                │
└────────────────────┬──────────────────────────────────────┘
                     │
                     │ FormData con config
                     │ (empresa_id, posiciones,
                     │  page_width_mm, font_name,
                     │  font_size, archivo PDF)
                     ▼
┌─────────────────────────────────────────────────────────────┐
│              BACK-END: save_modelo_posiciones.php           │
├─────────────────────────────────────────────────────────────┤
│  1. Validar inputs                                           │
│  2. Guardar PDF → pdfmodelo/                                │
│  3. UPDATE empresas.modelo_pdf                              │
│  4. DELETE/INSERT modelo_posiciones                         │
│  5. Crear tabla modelo_config                               │
│  6. INSERT/UPDATE modelo_config                             │
│  7. Devolver JSON respuesta                                 │
└────────────────────┬──────────────────────────────────────┘
                     │
                     │ Datos guardados
                     │ (transacción)
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                  BASE DE DATOS                              │
├─────────────────────────────────────────────────────────────┤
│  • empresas.modelo_pdf = "modelo_8_1764053663.pdf"         │
│  • modelo_posiciones:                                       │
│    ├─ id, empresa_id, key_name, label                      │
│    ├─ x_pct (0-100), y_pct (0-100), page                   │
│    └─ timestamps                                            │
│  • modelo_config:                                           │
│    ├─ id, empresa_id, page_width_mm                        │
│    ├─ font_name, font_size                                 │
│    └─ timestamps                                            │
└────────────────────┬──────────────────────────────────────┘
                     │
                     │ Lectura de config
                     │ (get_modelo_posiciones.php)
                     ▼
┌─────────────────────────────────────────────────────────────┐
│              FRONT-END: Cargar y mostrar                    │
├─────────────────────────────────────────────────────────────┤
│  • Rellenar controles con valores guardados                 │
│  • Mostrar marcadores en preview                            │
│  • Listar posiciones colocadas                              │
└────────────────────┬──────────────────────────────────────┘
                     │
                     │ Usuario genera PDF
                     │ (click "Imprimir por modelo")
                     ▼
┌─────────────────────────────────────────────────────────────┐
│           BACK-END: imprimir_factura_pdf.php                │
├─────────────────────────────────────────────────────────────┤
│  1. Leer factura_id                                         │
│  2. Obtener empresa_id de factura                           │
│  3. Leer modelo_config (page_width, font, size)            │
│  4. Leer modelo_posiciones                                  │
│  5. Cargar plantilla PDF                                    │
│  6. Calcular escala según page_width_mm                     │
│  7. Para cada página:                                       │
│     ├─ Crear página con tamaño escalado                     │
│     ├─ Importar template escalado                           │
│     ├─ SetFont(font_name, font_size)                        │
│     ├─ Renderizar cada posición en (x%, y%)                │
│     ├─ Manejar items con offset                             │
│     └─ Generar contenido                                    │
│  8. Output PDF (navegador)                                  │
└─────────────────────────────────────────────────────────────┘
                     │
                     ▼
          ┌──────────────────────┐
          │  PDF Generado ✓      │
          │  (Navegador)         │
          └──────────────────────┘
```

---

## 📊 Resumen de Cambios

### Archivos Modificados: 4

| Archivo | Líneas | Cambios principales |
|---------|--------|-------------------|
| `factura_.php` | +80 líneas | Botón, modal, controles, envío config |
| `save_modelo_posiciones.php` | +120 líneas | Guardar config en modelo_config |
| `get_modelo_posiciones.php` | +40 líneas | Devolver modelo_config |
| `imprimir_factura_pdf.php` | +150 líneas | Aplicar config en generación |

### Archivos Creados: 7

| Archivo | Líneas | Propósito |
|---------|--------|----------|
| `generate_test_template.php` | 150 | Generar PDF plantilla |
| `test_e2e.php` | 200 | Setup de pruebas |
| `diagnostico_pdf.php` | 180 | Análisis de PDF |
| `QUICK_START.md` | 150 | Inicio rápido |
| `TESTING_E2E.md` | 300 | Guía detallada |
| `RESUMEN_PLANTILLAS_PDF.md` | 250 | Especificación |
| `CHANGELOG.md` | 400 | Cambios detallados |

### Tablas Creadas: 2

| Tabla | Filas de esquema | Propósito |
|-------|------------------|----------|
| `modelo_posiciones` | 12 líneas | Guardar posiciones (x%, y%) |
| `modelo_config` | 12 líneas | Guardar config (ancho, fuente, tamaño) |

---

## 🎯 Funcionalidades Nuevas

### UI (factura_.php)
- ✅ Botón "Configuración de modelos"
- ✅ Modal con preview en iframe
- ✅ Overlay interactivo para colocar marcadores
- ✅ Selector de ancho de hoja (55/80/210mm)
- ✅ Selector de fuente (Arial/Helvetica/Times/Courier)
- ✅ Input tamaño fuente (6-48pt)
- ✅ Lista de marcadores colocados
- ✅ Cargar/guardar configuración

### Backend (save_modelo_posiciones.php)
- ✅ Crear tabla `modelo_config` si no existe
- ✅ Guardar `page_width_mm`, `font_name`, `font_size`
- ✅ Transacción para atomicidad
- ✅ Validación de inputs mejorada
- ✅ Manejo robusto de errores

### Backend (get_modelo_posiciones.php)
- ✅ Devolver `modelo_config` junto con posiciones
- ✅ Crear tabla si no existe
- ✅ Valores por defecto si no hay config

### Backend (imprimir_factura_pdf.php)
- ✅ Leer `modelo_config` desde BD
- ✅ Escalar plantilla al `page_width_mm` especificado
- ✅ Aplicar `font_name` y `font_size`
- ✅ Validación de dimensiones
- ✅ Validación de escala (rango 0.1x-10x)
- ✅ Fallback mejorado sin plantilla
- ✅ Soportar múltiples páginas

### Testing
- ✅ Script generador de plantilla
- ✅ Script setup end-to-end
- ✅ Script diagnóstico
- ✅ 4 guías de documentación

---

## 🔐 Seguridad

- ✅ Validación de sesión en todos los endpoints
- ✅ Prepared statements (sin SQL injection)
- ✅ Validación de tipo de archivo (PDF)
- ✅ Sanitización de rutas
- ✅ Permisos en archivos (644)
- ✅ Foreign keys con CASCADE

---

## 🚀 Despliegue

### Checklist Pre-Producción
- [ ] Ejecutar migraciones SQL (`create_modelo_config_table.sql`)
- [ ] Verificar permisos en `pdfmodelo/` (755)
- [ ] Configurar `php.ini`:
  - `upload_max_filesize = 10M`
  - `post_max_size = 10M`
- [ ] Hacer backup de `empresas` tabla
- [ ] Probar con `test_e2e.php`
- [ ] Validar con `diagnostico_pdf.php`
- [ ] Generar 2-3 PDFs de prueba
- [ ] Revisar logs en `error.log`

### Rollback
```sql
-- Si es necesario revertir:
DROP TABLE IF EXISTS modelo_posiciones;
DROP TABLE IF EXISTS modelo_config;
ALTER TABLE empresas DROP COLUMN modelo_pdf;
```

---

## 📞 Soporte

- **Quick Start:** `QUICK_START.md`
- **Troubleshooting:** `TESTING_E2E.md` → Debugging
- **Técnico:** `CHANGELOG.md` → Notas Técnicas
- **Especificación:** `RESUMEN_PLANTILLAS_PDF.md`

