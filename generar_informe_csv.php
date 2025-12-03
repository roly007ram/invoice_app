<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require 'vendor/autoload.php';
require 'db_config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv; // Use Csv writer
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['cliente_id']) || empty($_POST['fecha_desde']) || empty($_POST['fecha_hasta'])) {
    die('Parámetros inválidos para generar el informe.');
}

$cliente_id = intval($_POST['cliente_id']);
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

// Obtener nombre del cliente
$cliente_nombre = 'N/A';
$stmt_cliente = $conn->prepare("SELECT nombre FROM clientes WHERE id = ?");
if ($stmt_cliente) {
    $stmt_cliente->bind_param('i', $cliente_id);
    $stmt_cliente->execute();
    $result_cliente = $stmt_cliente->get_result();
    if ($row_cliente = $result_cliente->fetch_assoc()) {
        $cliente_nombre = $row_cliente['nombre'];
    }
    $stmt_cliente->close();
}

$sql = "SELECT 
            e.nombre AS empresa_nombre, 
            e.cuit AS empresa_cuit,
            f.numero_factura,
            f.fecha, 
            f.subtotal,
            f.iva,
            f.total AS total_factura,
            i.cantidad, 
            i.detalle, 
            i.precio_unitario
        FROM facturas f
        JOIN empresas e ON f.empresa_id = e.id
        JOIN items i ON f.id = i.factura_id
        WHERE f.cliente_id = ? 
        AND f.fecha BETWEEN ? AND ?
        ORDER BY f.fecha, f.numero_factura, i.id";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

$stmt->bind_param('iss', $cliente_id, $fecha_desde, $fecha_hasta);
$stmt->execute();
$result = $stmt->get_result();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties - CSV doesn't support these directly, but good practice
$spreadsheet->getProperties()->setCreator("InvoiceApp")
    ->setLastModifiedBy("InvoiceApp")
    ->setTitle("Informe de Facturación por Cliente (CSV)")
    ->setSubject("Informe de Facturación");

// Add header rows for CSV
$sheet->setCellValue('A1', 'Informe de Facturación por Cliente');
$sheet->setCellValue('A2', "Cliente: {$cliente_nombre}");
$sheet->setCellValue('A3', "Período: " . date('d/m/Y', strtotime($fecha_desde)) . " - " . date('d/m/Y', strtotime($fecha_hasta)));

// Header row for data
$header = ['Empresa', 'CUIT', 'Comprobante', 'Fecha', 'Cantidad', 'Detalle', 'P. Unitario', 'Total Item'];
// Add additional headers for invoice totals
$sheet->fromArray($header, NULL, 'A5');
$sheet->setCellValue('I5', 'Subtotal Factura');
$sheet->setCellValue('J5', 'IVA Factura');
$sheet->setCellValue('K5', 'Total Factura');

// Data rows
$data = [];
$facturas_data = [];
while ($row = $result->fetch_assoc()) {
    $factura_num = $row['numero_factura'];
    if (!isset($facturas_data[$factura_num])) {
        $facturas_data[$factura_num] = [
            'info' => [
                'empresa_nombre' => $row['empresa_nombre'],
                'empresa_cuit' => $row['empresa_cuit'],
                'fecha' => $row['fecha'],
                'subtotal' => $row['subtotal'],
                'iva' => $row['iva'],
                'total_factura' => $row['total_factura'],
            ],
            'items' => []
        ];
    }
    $facturas_data[$factura_num]['items'][] = [
        'cantidad' => $row['cantidad'],
        'detalle' => $row['detalle'],
        'precio_unitario' => $row['precio_unitario'],
    ];
}

$row_num = 6;
$total_general = 0;

foreach ($facturas_data as $factura_num => $factura_info) {
    $info = $factura_info['info'];
    $items = $factura_info['items'];
    $total_general += $info['total_factura'];

    foreach ($items as $item) {
        $item_total = $item['cantidad'] * $item['precio_unitario'];

        $sheet->setCellValue("A{$row_num}", $info['empresa_nombre']);
        $sheet->setCellValue("B{$row_num}", $info['empresa_cuit']);
        $sheet->setCellValue("C{$row_num}", $factura_num);
        $sheet->setCellValue("D{$row_num}", date('d/m/Y', strtotime($info['fecha'])));
        $sheet->setCellValue("E{$row_num}", $item['cantidad']);
        $sheet->setCellValue("F{$row_num}", $item['detalle']);
        $sheet->setCellValue("G{$row_num}", number_format($item['precio_unitario'], 2, ',', '.'));
        $sheet->setCellValue("H{$row_num}", number_format($item_total, 2, ',', '.'));
        // These are item rows, so subtotal/iva/total of the invoice are not here yet
        $row_num++;
    }
    
    // Add invoice totals after all items for that invoice
    // Add an empty row for visual separation for invoice totals
    $row_num++;
    $sheet->setCellValue("H{$row_num}", 'Subtotal Factura:');
    $sheet->setCellValue("I{$row_num}", number_format($info['subtotal'], 2, ',', '.'));
    $row_num++;
    $sheet->setCellValue("H{$row_num}", 'IVA Factura:');
    $sheet->setCellValue("I{$row_num}", number_format($info['iva'], 2, ',', '.'));
    $row_num++;
    $sheet->setCellValue("H{$row_num}", 'Total Factura:');
    $sheet->setCellValue("I{$row_num}", number_format($info['total_factura'], 2, ',', '.'));
    $row_num++; // Move to next row for the next invoice or grand total
    $row_num++; // Another empty row for separation

}

$stmt->close();
$conn->close();

// Add Grand Total
$total_row_num = $row_num + 1;
$sheet->setCellValue("H{$total_row_num}", 'TOTAL GENERAL:');
$sheet->setCellValue("I{$total_row_num}", number_format($total_general, 2, ',', '.'));


// Set headers for download
$filename = 'Informe_Cliente_' . str_replace([' ', '.'], '_', $cliente_nombre) . '_' . date('Ymd') . '.csv';
header('Content-Type: text/csv'); // Changed to text/csv
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');

// Create writer and output
$writer = new Csv($spreadsheet); // Use Csv writer
$writer->setDelimiter(';'); // Set delimiter if needed, common for Spanish locales
$writer->setEnclosure('"');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);

try {
    $writer->save('php://output');
} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
    // Log error or handle it
    die('Error al generar el archivo CSV: ' . $e->getMessage());
}
exit;
