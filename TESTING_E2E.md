# Pruebas End-to-End: Sistema de Plantillas PDF

## Descripción General

Este sistema permite:
1. **Subir plantillas PDF** por empresa
2. **Posicionar variables** visualmente en el PDF mediante un editor interactivo
3. **Guardar configuración** (ancho, fuente, tamaño) en la base de datos
4. **Generar facturas** con datos posicionados automáticamente en la plantilla

## Archivos de Prueba

### 1. `generate_test_template.php`
Genera un PDF plantilla simple para pruebas.

**Uso:**
```bash
# Desde navegador
http://localhost/invoice_app/generate_test_template.php

# Desde CLI (PowerShell)
php generate_test_template.php
```

**Resultado:** Crea `pdfmodelo/test_template.pdf`

---

### 2. `test_e2e.php`
Script CLI que simula el flujo completo: crear tablas, insertar posiciones de prueba, guardar configuración.

**Uso:**
```bash
php test_e2e.php [empresa_id] [factura_id]

# Ejemplos:
php test_e2e.php 1 1      # Prueba con empresa 1, factura 1
php test_e2e.php          # Usa empresa_id=1, factura_id=1 por defecto
```

**Qué hace:**
- Crea tablas `modelo_posiciones` y `modelo_config`
- Inserta 13 posiciones de prueba
- Configura: 80mm, Helvetica, 10pt
- Verifica datos en BD
- Muestra instrucciones para la prueba UI

**Salida esperada:**
```
✓ Tabla modelo_posiciones lista
✓ Tabla modelo_config lista
✓ Insertadas 13 posiciones de prueba
✓ Configuración creada
✓ Config DB: width=80mm, font=Helvetica, size=10
✓ Posiciones almacenadas: 13
```

---

### 3. `diagnostico_pdf.php`
Script CLI que analiza una factura generada y sugiere ajustes.

**Uso:**
```bash
php diagnostico_pdf.php [factura_id] [empresa_id]

# Ejemplos:
php diagnostico_pdf.php 1 1      # Factura 1, empresa 1
php diagnostico_pdf.php 5 3       # Factura 5, empresa 3
```

**Qué hace:**
- Lee configuración y posiciones desde BD
- Muestra datos de la factura
- Proporciona consejos de ajuste manual
- Sugiere rangos de valores para X%, Y%

**Salida esperada:**
```
[CONFIG]
  Ancho: 80mm
  Fuente: Helvetica
  Tamaño: 10pt

[POSICIONES]
  Cliente                   | X:  2.00% Y: 14.00% | Página: 1
  CUIT                      | X:  2.00% Y: 24.00% | Página: 1
  ...

[DATOS DE FACTURA]
  Cliente: Juan García
  CUIT: 20123456789
  ...
```

---

## Flujo de Prueba Completo (UI + CLI)

### Fase 1: Preparación
```powershell
# Terminal PowerShell en c:\xampp\htdocs\invoice_app

# 1. Generar plantilla de prueba
php generate_test_template.php

# Espera output:
# ✓ PDF plantilla generado en: ...pdfmodelo\test_template.pdf
```

### Fase 2: Setup de datos
```powershell
# 2. Crear tablas e insertar posiciones de prueba (para empresa_id=1)
php test_e2e.php 1 1

# Espera output:
# ✓ Tabla modelo_posiciones lista
# ✓ Tabla modelo_config lista
# ✓ Insertadas 13 posiciones de prueba
# ...instrucciones...
```

### Fase 3: Prueba UI
1. Abre navegador: `http://localhost/invoice_app/`
2. Selecciona **Empresa** con ID = 1 (verifica en tabla empresas)
3. Abre modal **"Configuración de modelos"**
4. Verifica que se cargan los valores:
   - Ancho: 80 mm
   - Fuente: Helvetica
   - Tamaño: 10 pt
5. Opcionalmente: sube `pdfmodelo/test_template.pdf` nuevamente
6. Coloca un marcador de prueba o guarda sin cambios
7. Cierra modal

### Fase 4: Generación y análisis
1. Abre/crea una **Factura** con empresa_id=1 (ej: factura_id=1)
   - Asegúrate de que tenga al menos un item
   - Datos mínimos: cliente, CUIT, domicilio, items con cantidad/detalle/precio
2. Pulsa botón **"Imprimir por modelo"**
3. Se abre PDF en nueva ventana
4. **Verifica visualmente:**
   - ¿Escalado correcto? (¿se ve al ancho de 80mm?)
   - ¿Textos legibles? (¿fuente Helvetica a 10pt?)
   - ¿Alineamiento? (¿textos en posiciones esperadas?)
   - ¿Items en columnas? (¿cantidad, detalle, precio alineados?)

### Fase 5: Diagnóstico
```powershell
# 3. Obtener sugerencias de ajuste
php diagnostico_pdf.php 1 1

# Muestra:
# [POSICIONES] - lista todas las posiciones
# [DATOS DE FACTURA] - datos que se renderizaron
# [CONSEJOS PARA AJUSTE MANUAL] - guía de calibración
```

---

## Ajustes Comunes

### Problema: Texto muy pequeño/grande

**Solución:** Cambiar `page_width_mm`
- **55 mm**: Para tiques de rollo (thermal)
- **80 mm**: Estándar para tiques (usar por defecto)
- **210 mm**: Para A4 completo

En modal → Ancho de hoja → Selecciona → Guardar

### Problema: Texto desalineado horizontalmente (muy a izq/derecha)

**Solución:** Ajustar `x_pct`
- Posición actual: X% = 2%
- Mover a derecha: aumenta (ej: 2% → 5%)
- Mover a izquierda: disminuye (ej: 5% → 2%)
- Rango: 0-100

**Dónde cambiar:** En BD (tabla `modelo_posiciones`):
```sql
UPDATE modelo_posiciones 
SET x_pct = 5 
WHERE empresa_id = 1 AND key_name = 'clienteNombre';
```

### Problema: Texto desalineado verticalmente (muy arriba/abajo)

**Solución:** Ajustar `y_pct`
- Posición actual: Y% = 14%
- Mover abajo: aumenta (ej: 14% → 18%)
- Mover arriba: disminuye (ej: 18% → 14%)
- Rango: 0-100

**Dónde cambiar:**
```sql
UPDATE modelo_posiciones 
SET y_pct = 18 
WHERE empresa_id = 1 AND key_name = 'clienteNombre';
```

### Problema: Items (filas) superpuestos

**Solución:** Cambiar espaciado de filas en `imprimir_factura_pdf.php`
- Busca línea: `$lineHeight = 6; // mm`
- Aumenta para más espaciado: `$lineHeight = 8;`
- Disminuye para menos: `$lineHeight = 5;`

### Problema: Fuente ilegible / Quiero Times o Arial

**Solución:** En modal → Fuente → Selecciona (Arial, Helvetica, Times, Courier) → Guardar

---

## Verificación Rápida (Checklist)

- [ ] `pdfmodelo/` existe y tiene permisos de escritura (755)
- [ ] `php.ini` tiene `upload_max_filesize >= 5M` y `post_max_size >= 5M`
- [ ] Tablas `modelo_posiciones` y `modelo_config` creadas (verificar en phpMyAdmin)
- [ ] Empresa con ID=1 existe en tabla `empresas`
- [ ] Factura con ID=1 existe y tiene empresa_id=1
- [ ] Factura tiene al menos 1 item en tabla `items`
- [ ] PDF se genera sin errores (revisar console del navegador)

---

## Flujo de Debugging

Si algo falla:

1. **Revisar logs PHP:**
   ```powershell
   # PowerShell en XAMPP
   Get-Content "C:\xampp\apache\logs\error.log" -Tail 20
   ```

2. **Revisar network en DevTools:**
   - F12 → Network
   - Busca llamadas a `save_modelo_posiciones.php`, `get_modelo_posiciones.php`
   - Verifica respuestas JSON (success, error message)

3. **Verificar BD:**
   ```sql
   -- phpMyAdmin o MySQL client
   SELECT * FROM modelo_config WHERE empresa_id = 1;
   SELECT * FROM modelo_posiciones WHERE empresa_id = 1 LIMIT 5;
   ```

4. **Generar PDF manualmente desde CLI:**
   ```powershell
   # Para ver errores PHP más claros
   php imprimir_factura_pdf.php
   
   # (Nota: este archivo normalmente se accede vía GET en navegador)
   ```

---

## Notas Técnicas

- **Escalado:** La plantilla se escala proporcionalmente al `page_width_mm`
- **Posiciones:** Se guardan como porcentajes (0-100) para que sean independientes de la escala
- **Items:** Se renderiza cada fila con offset de `$lineHeight` mm
- **Fuentes:** Solo las estándar de FPDF (Arial, Helvetica, Times, Courier)

---

## Próximos Pasos Opcionales

1. **Soporte para fuentes TTF:** Registrar fuentes personalizadas en FPDF
2. **UI multi-página:** Permitir seleccionar página en el modal
3. **Arrastrar marcadores:** Permitir edit visual en lugar de porcentajes
4. **Export de configuración:** Guardar/cargar templates como JSON

