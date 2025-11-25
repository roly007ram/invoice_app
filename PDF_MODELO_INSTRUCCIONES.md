# Instrucciones para usar Modelos PDF

## Problema: "PDF document is encrypted"

Si recibes un error como:
```
This PDF document is encrypted and cannot be processed with FPDI
```

Significa que el archivo PDF que cargaste como modelo está protegido/encriptado.

## Solución

### Opción 1: Usar un PDF sin encripción (Recomendado)

1. Abre el archivo PDF en **Adobe Reader** o **Acrobat Pro**
2. Ve a **Archivo > Propiedades > Seguridad**
3. Si aparece "Seguridad de contraseña" o "Restricciones de permiso", el PDF está encriptado
4. Para eliminarlo:
   - En **Adobe Acrobat Pro**: Ve a **Herramientas > Proteger > Eliminar protección**
   - En **Adobe Reader**: No puedes eliminar la encripción, necesitas Acrobat Pro
   - En otras herramientas: Busca "Remover protección PDF" o "Decrypt PDF"

### Opción 2: Crear un nuevo PDF sin encripción

1. Usa un editor de PDF online (como **ILovePDF**, **SmallPDF**, **PDF Mergy**)
2. Sube tu PDF
3. Busca la opción "Desencriptar" o "Remove Password"
4. Descarga el PDF sin protección
5. Sube ese nuevo archivo como modelo

### Opción 3: Crear el PDF modelo desde cero

1. Usa **LibreOffice Draw** o **Inkscape** para diseñar tu template
2. Exporta como PDF (asegúrate de NO marcar "Encriptación")
3. Sube ese PDF como modelo

## Qué pasa ahora si el PDF está encriptado

Si cargas un PDF encriptado, el sistema:
- Intenta cargarlo automáticamente
- Si falla, genera un PDF simple sin el modelo
- Muestra una advertencia en rojo indicando que el modelo no se cargó
- Completa la factura con los datos pero sin el diseño del modelo

## Instrucciones para Desencriptar en Windows

### Usando Python (Gratis)
```bash
pip install pypdf2
```

Luego crea un archivo `desencriptar.py`:
```python
from PyPDF2 import PdfReader, PdfWriter

reader = PdfReader("archivo_encriptado.pdf")
writer = PdfWriter()

for page_num in range(len(reader.pages)):
    page = reader.pages[page_num]
    writer.add_page(page)

with open("archivo_desencriptado.pdf", "wb") as output_file:
    writer.write(output_file)
```

Ejecuta: `python desencriptar.py`

### Usando Ghostscript (Gratis)
```bash
gswin64c -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=desencriptado.pdf archivo_encriptado.pdf
```

### Usando un servicio online
- **ILovePDF**: https://www.ilovepdf.com/es/desencriptar_pdf
- **PDF Mergy**: https://www.pdfmerge.com/
- **SmallPDF**: https://smallpdf.com/decrypt-pdf

## Notas Importantes

- FPDI no puede procesar PDFs con encripción de contenido
- La mayoría de PDFs generados por Adobe tienen encripción ligera que necesita ser removida
- Los PDFs creados con LibreOffice, Google Docs o Microsoft no suelen tener este problema
- Si el error persiste, descarga un PDF de prueba sin encripción para verificar que el sistema funciona
