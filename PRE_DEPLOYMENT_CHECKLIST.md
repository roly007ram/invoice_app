# Pre-Deployment Checklist

## ✅ Antes de Pasar a Producción

### 1️⃣ Verificaciones de Código

- [ ] `factura_.php`: Modal renderiza sin errores de consola
- [ ] `save_modelo_posiciones.php`: Guardar config devuelve `"success": true`
- [ ] `get_modelo_posiciones.php`: GET devuelve JSON con `modelo_config`
- [ ] `imprimir_factura_pdf.php`: Genera PDF sin errores de PHP
- [ ] Validar sesión en todos los endpoints

### 2️⃣ Verificaciones de Base de Datos

- [ ] Tabla `modelo_posiciones` existe y tiene índices
- [ ] Tabla `modelo_config` existe con UNIQUE en empresa_id
- [ ] Columna `empresas.modelo_pdf` existe
- [ ] Migración SQL ejecutada exitosamente
- [ ] No hay registros huérfanos en tablas nuevas

**Comando para verificar:**
```sql
DESC modelo_posiciones;
DESC modelo_config;
SELECT COUNT(*) FROM modelo_posiciones;
SELECT COUNT(*) FROM modelo_config;
```

### 3️⃣ Verificaciones del Filesystem

- [ ] Directorio `pdfmodelo/` existe
- [ ] Permisos correctos: `chmod 755 pdfmodelo/`
- [ ] Escribible por usuario Apache/PHP: `touch pdfmodelo/test.txt && rm pdfmodelo/test.txt`
- [ ] Espacio suficiente en disco (mínimo 1GB recomendado)

**Comando para verificar:**
```bash
ls -la | grep pdfmodelo
chmod 755 pdfmodelo/
```

### 4️⃣ Verificaciones de PHP.ini

- [ ] `upload_max_filesize >= 10M` (en `C:\xampp\php\php.ini`)
- [ ] `post_max_size >= 10M`
- [ ] `memory_limit >= 256M` (para PDFs grandes)
- [ ] `display_errors = 0` (en producción)
- [ ] `log_errors = 1` (para debugging)
- [ ] `error_log` apunta a archivo válido

**Cómo verificar:**
```php
<?php phpinfo(); ?>
// Buscar upload_max_filesize, post_max_size, memory_limit
```

### 5️⃣ Verificaciones de Dependencias

- [ ] FPDI está instalado en `vendor/setasign/fpdi/`
- [ ] FPDF está disponible en vendor
- [ ] `vendor/autoload.php` existe y se carga correctamente
- [ ] No hay warnings de deprecación en logs

**Verificar:**
```bash
ls vendor/setasign/fpdi/
php -r "require 'vendor/autoload.php'; echo 'OK';"
```

### 6️⃣ Pruebas Funcionales Básicas

#### Test A: Upload de PDF
- [ ] Seleccionar empresa
- [ ] Abrir modal "Configuración de modelos"
- [ ] Subir archivo PDF (usar `generate_test_template.php`)
- [ ] Verificar archivo en `pdfmodelo/` con tamaño > 0
- [ ] Revisar en BD que `empresas.modelo_pdf` fue actualizado

#### Test B: Posicionar Variables
- [ ] Seleccionar variable en combo
- [ ] Pulsar botón "Hacer clic en el PDF para colocar"
- [ ] Hacer clic en preview → Aparece marcador rojo
- [ ] Lista de marcadores se actualiza
- [ ] Borrar marcador funciona

#### Test C: Configuración
- [ ] Cambiar Ancho de hoja → Seleccionar valor
- [ ] Cambiar Fuente → Seleccionar valor
- [ ] Cambiar Tamaño → Escribir número
- [ ] Guardar configuración → JSON response success: true
- [ ] Reabrir modal → Valores cargados correctamente

#### Test D: Generación de PDF
- [ ] Crear factura con datos (cliente, items)
- [ ] Pulsar "Imprimir por modelo"
- [ ] PDF se abre en navegador
- [ ] Verificar visualmente:
  - [ ] Escala correcta (no demasiado pequeño/grande)
  - [ ] Texto legible (fuente clara)
  - [ ] Campos alineados (no cortados, no superpuestos)
  - [ ] Items renderizados correctamente

### 7️⃣ Pruebas de Stress

- [ ] Upload de PDF grande (5MB)
- [ ] Múltiples variables colocadas (20+)
- [ ] Generar 10 PDFs consecutivas
- [ ] Revisar uso de memoria y tiempo de respuesta

### 8️⃣ Pruebas de Errores

- [ ] Upload sin seleccionar archivo → Error visible
- [ ] Upload de archivo no-PDF → Error visible
- [ ] Guardar sin empresa seleccionada → Error/prevención
- [ ] Generar PDF sin configuración → PDF genérico (fallback)
- [ ] Archivo `pdfmodelo/` sin permisos → Error informativo

### 9️⃣ Pruebas de Seguridad

- [ ] SQL Injection: Intentar inyectar en empresa_id → Bloqueado
- [ ] File Upload: Intentar subir archivo malicioso → Validado (solo PDF)
- [ ] CSRF: Verificar token (si aplica) → Presente
- [ ] Session Timeout: Loguearse, esperar → Sesión requerida en endpoint

### 🔟 Pruebas de Compatibilidad

- [ ] navegador Chrome → ✓
- [ ] navegador Firefox → ✓
- [ ] navegador Safari → ✓
- [ ] navegador Edge → ✓
- [ ] Dispositivos móviles → Modal responsive ✓
- [ ] PHP 7.4+ → ✓
- [ ] MySQL 5.7+ / MariaDB 10.3+ → ✓

### 1️⃣1️⃣ Logs y Monitoreo

- [ ] No hay errores en `error.log` durante las pruebas
- [ ] DevTools Network tab limpio (sin errores HTTP)
- [ ] Respuestas JSON válidas (no HTML errores)
- [ ] Tiempos de respuesta aceptables (< 5s)

### 1️⃣2️⃣ Documentación

- [ ] QUICK_START.md es accesible para usuarios
- [ ] TESTING_E2E.md disponible para técnicos
- [ ] README principal actualizado con nueva feature
- [ ] Instrucciones de rollback documentadas

### 1️⃣3️⃣ Backup y Rollback

- [ ] Backup de `empresas`, `modelo_posiciones`, `modelo_config` tablas
- [ ] Script de rollback preparado (DROP tables)
- [ ] PDFs respaldados en ubicación segura
- [ ] Procedimiento de restauración documentado

### 1️⃣4️⃣ Monitoreo Post-Deployment

- [ ] Configurar alertas para errores en `error.log`
- [ ] Monitorear uso de espacio en `pdfmodelo/`
- [ ] Revisar logs 24h después de deploy
- [ ] Feedback de usuarios en primeros días

---

## 🎯 Checklist Rápido (Antes de hacer Deploy)

**Ejecutar en orden:**

```bash
# 1. Verificar archivos
ls -la factura_.php save_modelo_posiciones.php get_modelo_posiciones.php imprimir_factura_pdf.php

# 2. Verificar directorio
chmod 755 pdfmodelo/
touch pdfmodelo/test && rm pdfmodelo/test

# 3. Verificar BD
mysql -u root -p invoice_app -e "DESC modelo_posiciones; DESC modelo_config;"

# 4. Verificar vendor
php -r "require 'vendor/autoload.php'; echo 'Autoload OK';"

# 5. Generar plantilla de prueba
php generate_test_template.php

# 6. Setup datos de prueba
php test_e2e.php 1 1

# 7. Diagnóstico
php diagnostico_pdf.php 1 1
```

**Si todas las salidas son "✓ OK":**
→ Sistema listo para testing en producción

---

## 📊 Matriz de Riesgos

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|-------------|--------|-----------|
| Upload falla | Media | Bajo | Validar permisos `pdfmodelo/` |
| Posición incorrecta | Media | Bajo | Manual adjustment en BD o UI re-edit |
| PDF no genera | Baja | Alto | Fallback a PDF genérico |
| BD llena | Muy baja | Alto | Monitorear espacio, cleanup de old PDFs |
| Fuente no disponible | Muy baja | Bajo | Fallback a Helvetica |

---

## ✨ Sign-Off

**Investigador/Developer:** _________________ Fecha: _______

**QA/Tester:** _________________ Fecha: _______

**Administrador:** _________________ Fecha: _______

**Aprobación Deploy:** ☐ SÍ ☐ NO

---

**Notas Adicionales:**
```
_____________________________________________________________
_____________________________________________________________
_____________________________________________________________
```

