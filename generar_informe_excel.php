<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require 'vendor/autoload.php';
require 'db_config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

// Set document properties
$spreadsheet->getProperties()->setCreator("InvoiceApp")
    ->setLastModifiedBy("InvoiceApp")
    ->setTitle("Informe de Facturación por Cliente")
    ->setSubject("Informe de Facturación");

// Title
$sheet->mergeCells('A1:I1');
$sheet->setCellValue('A1', 'Informe de Facturación por Cliente');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Sub-header
$sheet->mergeCells('A2:I2');
$sheet->setCellValue('A2', "Cliente: {$cliente_nombre}");
$sheet->mergeCells('A3:I3');
$sheet->setCellValue('A3', "Período: " . date('d/m/Y', strtotime($fecha_desde)) . " - " . date('d/m/Y', strtotime($fecha_hasta)));

// Header row
$header = ['Empresa', 'CUIT', 'Comprobante', 'Fecha', 'Cantidad', 'Detalle', 'P. Unitario', 'Total Item', 'Total Factura'];
$sheet->fromArray($header, NULL, 'A5');

// Style header
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
];
$sheet->getStyle('A5:I5')->applyFromArray($headerStyle);


// Data rows
$data = [];
$current_factura = null;
$total_general = 0;
$row_num = 6;

while ($row = $result->fetch_assoc()) {
    $factura_num = $row['numero_factura'];
    $is_new_factura = ($factura_num !== $current_factura);

    if ($is_new_factura) {
        if ($current_factura !== null) {
            // Add a border before starting a new invoice group
            $sheet->getStyle("A{$row_num}:I{$row_num}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        }
        $current_factura = $factura_num;
        $total_general += $row['total_factura'];
    }
    
    $item_total = $row['cantidad'] * $row['precio_unitario'];

    $sheet->setCellValue("A{$row_num}", $row['empresa_nombre']);
    $sheet->setCellValue("B{$row_num}", $row['empresa_cuit']);
    $sheet->setCellValue("C{$row_num}", $factura_num);
    $sheet->setCellValue("D{$row_num}", \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($row['fecha']));
    $sheet->getStyle("D{$row_num}")->getNumberFormat()->setFormatCode('DD/MM/YYYY');
    $sheet->setCellValue("E{$row_num}", $row['cantidad']);
    $sheet->setCellValue("F{$row_num}", $row['detalle']);
    $sheet->setCellValue("G{$row_num}", $row['precio_unitario']);
    $sheet->getStyle("G{$row_num}")->getNumberFormat()->setFormatCode('#,##0.00');
    $sheet->setCellValue("H{$row_num}", $item_total);
    $sheet->getStyle("H{$row_num}")->getNumberFormat()->setFormatCode('#,##0.00');

    // Only show the total_factura for the first item of each invoice to avoid repetition
    if ($is_new_factura) {
        $sheet->setCellValue("I{$row_num}", $row['total_factura']);
        $sheet->getStyle("I{$row_num}")->getNumberFormat()->setFormatCode('#,##0.00');
    }

    $row_num++;
}
$stmt->close();
$conn->close();

// Set column widths for better readability
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(12);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);

// Add Grand Total
$total_row_num = $row_num + 1;
$sheet->mergeCells("A{$total_row_num}:H{$total_row_num}");
$sheet->setCellValue("A{$total_row_num}", 'TOTAL GENERAL:');
$sheet->getStyle("A{$total_row_num}")->getFont()->setBold(true)->setSize(14);
$sheet->getStyle("A{$total_row_num}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$sheet->setCellValue("I{$total_row_num}", $total_general);
$sheet->getStyle("I{$total_row_num}")->getNumberFormat()->setFormatCode('#,##0.00');
$sheet->getStyle("I{$total_row_num}")->getFont()->setBold(true)->setSize(14);


// Set headers for download
$filename = 'Informe_Cliente_' . str_replace([' ', '.'], '_', $cliente_nombre) . '_' . date('Ymd') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');

// Create writer and output
$writer = new Xlsx($spreadsheet);
try {
    $writer->save('php://output');
} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
    // Log error or handle it
    die('Error al generar el archivo Excel: ' . $e->getMessage());
}
exit;
