<?php
/**
 * Script para generar un PDF plantilla de prueba simple.
 * Uso: Acceder a http://localhost/invoice_app/generate_test_template.php
 * Genera un PDF básico con áreas marcadas y lo guarda en pdfmodelo/test_template.pdf
 */

require_once('vendor/autoload.php');

use setasign\Fpdf\Fpdf;

// Crear PDF con tamaño 80mm x 200mm (tique)
$pdf = new Fpdf('P', 'mm', [80, 200]);
$pdf->AddPage();

// Fondo blanco y bordes para visualizar dimensiones
$pdf->SetDrawColor(200, 200, 200);
$pdf->Rect(0.5, 0.5, 79, 199);

// Título
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->SetXY(2, 2);
$pdf->Write(0, 'FACTURA MODELO');

// Línea de separación
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line(2, 8, 78, 8);

// Sección empresa (etiquetas para referencia)
$pdf->SetFont('Helvetica', '', 8);
$pdf->SetXY(2, 10);
$pdf->Write(0, 'Empresa:');

$pdf->SetFont('Helvetica', '', 9);
$pdf->SetXY(2, 14);
$pdf->Write(0, '_______________');  // Placeholder para clienteNombre

$pdf->SetFont('Helvetica', '', 8);
$pdf->SetXY(2, 20);
$pdf->Write(0, 'CUIT:');

$pdf->SetFont('Helvetica', '', 9);
$pdf->SetXY(2, 24);
$pdf->Write(0, '_______________');  // Placeholder para clienteCuit

// Sección factura (derecha)
$pdf->SetFont('Helvetica', '', 8);
$pdf->SetXY(45, 10);
$pdf->Write(0, 'Factura:');

$pdf->SetFont('Helvetica', '', 9);
$pdf->SetXY(45, 14);
$pdf->Write(0, '_______________');  // Placeholder para numeroFactura

$pdf->SetFont('Helvetica', '', 8);
$pdf->SetXY(45, 20);
$pdf->Write(0, 'Fecha:');

$pdf->SetFont('Helvetica', '', 9);
$pdf->SetXY(45, 24);
$pdf->Write(0, '_______________');  // Placeholder para fecha

// Línea de separación
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line(2, 30, 78, 30);

// Detalles de cliente
$pdf->SetFont('Helvetica', '', 8);
$pdf->SetXY(2, 32);
$pdf->Write(0, 'Domicilio:');

$pdf->SetFont('Helvetica', '', 9);
$pdf->SetXY(2, 36);
$pdf->Write(0, '_______________');  // Placeholder para clienteDomicilio

$pdf->SetFont('Helvetica', '', 8);
$pdf->SetXY(2, 42);
$pdf->Write(0, 'Localidad:');

$pdf->SetFont('Helvetica', '', 9);
$pdf->SetXY(2, 46);
$pdf->Write(0, '_______________');  // Placeholder para localidad

// Línea de separación
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line(2, 52, 78, 52);

// Encabezado de items
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->SetXY(2, 54);
$pdf->Write(0, 'CANT');

$pdf->SetXY(15, 54);
$pdf->Write(0, 'DETALLE');

$pdf->SetXY(55, 54);
$pdf->Write(0, 'P.UNIT');

$pdf->SetXY(70, 54);
$pdf->Write(0, 'TOTAL');

// Línea separadora
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line(2, 60, 78, 60);

// Espacios para items (6 filas de ejemplo)
$pdf->SetFont('Helvetica', '', 8);
for ($i = 0; $i < 6; $i++) {
    $y = 62 + ($i * 6);
    $pdf->SetXY(2, $y);
    $pdf->Write(0, '_');  // Placeholder para cantidad
    $pdf->SetXY(15, $y);
    $pdf->Write(0, '_____________');  // Placeholder para detalle
    $pdf->SetXY(55, $y);
    $pdf->Write(0, '____');  // Placeholder para precio_unitario
    $pdf->SetXY(70, $y);
    $pdf->Write(0, '____');  // Placeholder para total_item
}

// Línea de separación
$pdf->SetDrawColor(150, 150, 150);
$pdf->Line(2, 98, 78, 98);

// Totales
$pdf->SetFont('Helvetica', '', 8);
$pdf->SetXY(50, 100);
$pdf->Write(0, 'Subtotal:');

$pdf->SetXY(70, 100);
$pdf->Write(0, '_________');  // Placeholder para subtotal

$pdf->SetXY(50, 106);
$pdf->Write(0, 'IVA:');

$pdf->SetXY(70, 106);
$pdf->Write(0, '_________');  // Placeholder para ivaTotal

$pdf->SetFont('Helvetica', 'B', 9);
$pdf->SetXY(50, 114);
$pdf->Write(0, 'TOTAL:');

$pdf->SetXY(70, 114);
$pdf->Write(0, '_________');  // Placeholder para totalGeneral

// Guardar en pdfmodelo/
$baseDir = __DIR__ . DIRECTORY_SEPARATOR . 'pdfmodelo';
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
}

$filename = $baseDir . DIRECTORY_SEPARATOR . 'test_template.pdf';
$pdf->Output('F', $filename);

// Establecer permisos
@chmod($filename, 0644);

echo "✓ PDF plantilla generado en: $filename\n";
echo "Tamaño: " . filesize($filename) . " bytes\n";
echo "Úsalo para pruebas: sube este archivo en 'Configuración de modelos'.\n";
?>
