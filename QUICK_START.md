# Quick Start: Sistema de Plantillas PDF

## ⚡ Inicio Rápido (5 minutos)

### 1️⃣ Paso 1: Seleccionar Empresa
```
Abrir app → Seleccionar empresa en combo
```

### 2️⃣ Paso 2: Abrir Modal
```
Botón "Configuración de modelos" (junto a "Imprimir por modelo")
```

### 3️⃣ Paso 3: Subir PDF (opcional)
```
Upload → Seleccionar archivo PDF → Esperar upload
```

### 4️⃣ Paso 4: Colocar Variables
```
1. Seleccionar variable en combo (ej: "Nombre y Apellido")
2. Pulsar "Hacer clic en el PDF para colocar"
3. Hacer clic en el lugar del PDF donde quiero que vaya
4. Ver marcador rojo aparecer
5. Repetir pasos 1-4 para más variables
```

### 5️⃣ Paso 5: Configurar Página
```
Ancho: Seleccionar 55mm / 80mm / 210mm (defecto: 80mm)
Fuente: Seleccionar Arial / Helvetica / Times / Courier
Tamaño: Escribir tamaño en puntos (defecto: 10)
```

### 6️⃣ Paso 6: Guardar
```
Botón "Guardar configuración" → Esperar respuesta
```

### 7️⃣ Paso 7: Generar PDF
```
Cerrar modal → Abrir/crear factura → Botón "Imprimir por modelo"
→ PDF se abre en nueva ventana
```

---

## 🎯 Casos de Uso Comunes

### Caso 1: Tique de 80mm (estándar)
```
Ancho: 80mm
Fuente: Helvetica
Tamaño: 9pt
Posiciones: Colocar según plantilla visual
```

### Caso 2: Factura A4 (210mm)
```
Ancho: 210mm
Fuente: Arial
Tamaño: 10pt
Posiciones: Usar márgenes más amplios
```

### Caso 3: Recibo pequeño (55mm)
```
Ancho: 55mm
Fuente: Courier
Tamaño: 8pt
Posiciones: Colocar cerca de bordes
```

---

## 🔧 Ajustes Rápidos

| Problema | Solución | Dónde |
|----------|----------|-------|
| Texto muy pequeño | Aumentar tamaño fuente | Modal → Tamaño |
| Texto muy grande | Disminuir tamaño fuente | Modal → Tamaño |
| Ancho incorrecta | Cambiar ancho hoja | Modal → Ancho de hoja |
| Fuente ilegible | Cambiar fuente | Modal → Fuente |
| Desalineado izq/derecha | Editar en BD `x_pct` | phpMyAdmin / MySQL |
| Desalineado arriba/abajo | Editar en BD `y_pct` | phpMyAdmin / MySQL |

---

## 📋 Requisitos

- ✅ XAMPP con Apache y MySQL activos
- ✅ Carpeta `pdfmodelo/` con permisos 755
- ✅ `php.ini` con `upload_max_filesize >= 5M`
- ✅ Empresa seleccionada en la app
- ✅ Factura con datos (cliente, items, etc.)

---

## 🚀 Modo Experto: CLI

### Test rápido
```bash
# 1. Generar plantilla de prueba
php generate_test_template.php

# 2. Setup datos
php test_e2e.php 1 1

# 3. Diagnóstico
php diagnostico_pdf.php 1 1
```

### Editar posiciones directamente
```sql
UPDATE modelo_posiciones 
SET x_pct = 10 
WHERE empresa_id = 1 AND key_name = 'clienteNombre';
```

### Ver configuración
```sql
SELECT * FROM modelo_config WHERE empresa_id = 1;
```

---

## 📖 Documentación Completa

- **`TESTING_E2E.md`** → Guía detallada de pruebas
- **`RESUMEN_PLANTILLAS_PDF.md`** → Especificación técnica
- **`CHANGELOG.md`** → Cambios implementados
- **`RESUMEN_PLANTILLAS_PDF.md`** → Requisitos y limitaciones

---

## ⚠️ Troubleshooting Rápido

### "PDF no se genera"
1. Verifica que existe `pdfmodelo/`
2. Verifica permisos: `chmod 755 pdfmodelo/`
3. Revisa logs: `C:\xampp\apache\logs\error.log`

### "Variables no se alinean"
1. Abre DevTools (F12)
2. Network → Busca `save_modelo_posiciones.php`
3. Verifica respuesta: `"success": true`
4. En BD: verifica tabla `modelo_posiciones` tiene filas

### "Modal no carga"
1. Selecciona empresa primero
2. Recarga página
3. Intenta de nuevo

### "Upload de PDF falla"
1. Verifica archivo sea PDF válido
2. Verifica tamaño < 5MB
3. Verifica permisos `pdfmodelo/` (755)

---

## 💡 Tips Pro

1. **Usar percentajes:** Las posiciones en % escalan automáticamente
2. **Múltiples items:** El espaciado de filas es 6mm (editable en código)
3. **Fuentes:** Solo las estándar funcionan (Arial, Helvetica, Times, Courier)
4. **Template multi-página:** Soporta, asigna variable a página específica
5. **Rollback:** Si algo falla, DELETE de `modelo_posiciones` y `modelo_config` reestablece valores por defecto

---

**¿Necesitas ayuda?** Ver `TESTING_E2E.md` o contactar al administrador.

