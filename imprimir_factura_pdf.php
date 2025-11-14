<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// imprimir_factura_pdf.php
// Requiere: composer require setasign/fpdf setasign/fpdi
require_once('vendor/autoload.php');
require_once('db_config.php');

use setasign\Fpdi\Fpdi;

if (!isset($_GET['factura_id'])) {
    die('Falta el parámetro factura_id');
}
$factura_id = intval($_GET['factura_id']);

// Debug: mostrar el ID de factura que estamos buscando


// Obtener datos de la factura y la empresa
$sql = "SELECT f.*, e.modelo_pdf, e.nombre AS empresa_nombre, e.cuit AS empresa_cuit 
        FROM facturas f 
        LEFT JOIN empresas e ON f.empresa_id = e.id 
        WHERE f.id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Error en la consulta: ' . $conn->error);
}

if (!$stmt->bind_param('i', $factura_id)) {
    die('Error al vincular parámetros: ' . $stmt->error);
}

if (!$stmt->execute()) {
    die('Error al ejecutar la consulta: ' . $stmt->error);
}

$result = $stmt->get_result();

// Debug: mostrar cuántos resultados encontramos


if (!$row = $result->fetch_assoc()) {
    die('Factura no encontrada. ID: ' . $factura_id . '. Verifique que la factura existe y tiene una empresa asociada.');
}

// Debug: mostrar datos encontrados


$modelo_pdf = $row['modelo_pdf'];
if (!$modelo_pdf || !file_exists(__DIR__ . '/' . $modelo_pdf)) {
    die('Modelo PDF no encontrado. Ruta buscada: ' . __DIR__ . '/' . $modelo_pdf);
}

$pdf = new Fpdi();
$pdf->AddPage();
$pdf->setSourceFile(__DIR__ . '/' . $modelo_pdf);
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 210);

// Escribir datos
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0,0,0);

// Datos de la empresa
$pdf->SetXY(20, 20);
$pdf->Write(0, $row['empresa_nombre']);
$pdf->SetXY(20, 28);
$pdf->Write(0, $row['empresa_cuit']);

// Número y fecha de factura
$pdf->SetXY(150, 20);
$pdf->Write(0, $row['numero_factura']);
$pdf->SetXY(150, 28);
$pdf->Write(0, $row['fecha']);

// Datos del cliente
$pdf->SetXY(20, 40);
$pdf->Write(0, 'Cliente: ' . $row['cliente_nombre']);
$pdf->SetXY(20, 48);
$pdf->Write(0, 'CUIT: ' . $row['cliente_cuit']);
$pdf->SetXY(20, 56);
$pdf->Write(0, 'Domicilio: ' . $row['cliente_domicilio']);

// Totales
$pdf->SetXY(150, 100);
$pdf->Write(0, 'Subtotal: $' . number_format($row['subtotal'], 2, ',', '.'));
$pdf->SetXY(150, 108);
$pdf->Write(0, 'IVA: $' . number_format($row['iva'], 2, ',', '.'));
$pdf->SetXY(150, 116);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Write(0, 'Total: $' . number_format($row['total'], 2, ',', '.'));

// Output del PDF
$pdf->Output('I', 'factura_' . $row['numero_factura'] . '.pdf');