<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require 'vendor/autoload.php';
require 'db_config.php';

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

$facturas = [];
while ($row = $result->fetch_assoc()) {
    $factura_id = $row['numero_factura'];
    if (!isset($facturas[$factura_id])) {
        $facturas[$factura_id] = [
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
    $facturas[$factura_id]['items'][] = [
        'cantidad' => $row['cantidad'],
        'detalle' => $row['detalle'],
        'precio_unitario' => $row['precio_unitario']
    ];
}
$stmt->close();

class PDF extends FPDF
{
    private $clienteNombre;
    private $fechaDesde;
    private $fechaHasta;

    function setReportHeader($cliente, $desde, $hasta) {
        $this->clienteNombre = $cliente;
        $this->fechaDesde = $desde;
        $this->fechaHasta = $hasta;
    }

    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Informe de Facturacion por Cliente', 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Cliente: ' . utf8_decode($this->clienteNombre), 0, 1, 'C');
        $this->Cell(0, 10, 'Periodo: ' . date('d/m/Y', strtotime($this->fechaDesde)) . ' - ' . date('d/m/Y', strtotime($this->fechaHasta)), 0, 1, 'C');
        $this->Ln(10);

        // Table Header
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(230, 230, 230);
        $header = ['Empresa', 'CUIT', 'Comprobante', 'Fecha', 'Cant.', 'Detalle', 'P. Unitario', 'Total Item'];
        $w = [40, 20, 25, 18, 10, 85, 22, 22];
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function CheckPageBreak($h)
    {
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->setReportHeader($cliente_nombre, $fecha_desde, $fecha_hasta);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);

if (empty($facturas)) {
    $pdf->Cell(0, 10, 'No se encontraron registros para el cliente y el periodo seleccionados.', 0, 1, 'C');
} else {
    $total_general = 0;
    $w = [40, 20, 25, 18, 10, 85, 22, 22];

    foreach ($facturas as $factura_id => $factura_data) {
        $info = $factura_data['info'];
        $items = $factura_data['items'];
        $total_general += $info['total_factura'];

        // Draw items for the current invoice
        foreach ($items as $item) {
            $pdf->CheckPageBreak(6);
            $pdf->Cell($w[0], 6, utf8_decode($info['empresa_nombre']), 'LR');
            $pdf->Cell($w[1], 6, $info['empresa_cuit'], 'LR', 0, 'C');
            $pdf->Cell($w[2], 6, $factura_id, 'LR', 0, 'C');
            $pdf->Cell($w[3], 6, date('d/m/Y', strtotime($info['fecha'])), 'LR', 0, 'C');
            $pdf->Cell($w[4], 6, $item['cantidad'], 'LR', 0, 'R');
            
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell($w[5], 6, utf8_decode($item['detalle']), 'LR', 'L');
            $pdf->SetXY($x + $w[5], $y);

            $item_total = $item['cantidad'] * $item['precio_unitario'];
            $pdf->Cell($w[6], 6, number_format($item['precio_unitario'], 2, ',', '.'), 'LR', 0, 'R');
            $pdf->Cell($w[7], 6, number_format($item_total, 2, ',', '.'), 'LR', 1, 'R');
        }

        // Draw totals for the invoice
        $pdf->Cell(array_sum($w), 0, '', 'T'); // Top border for summary
        $pdf->Ln(0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(array_sum(array_slice($w, 0, 7)), 6, 'Sub-Total Factura:', 'LR', 0, 'R');
        $pdf->Cell($w[7], 6, number_format($info['subtotal'], 2, ',', '.'), 'LR', 1, 'R');
        
        $pdf->Cell(array_sum(array_slice($w, 0, 7)), 6, 'IVA:', 'LR', 0, 'R');
        $pdf->Cell($w[7], 6, number_format($info['iva'], 2, ',', '.'), 'LR', 1, 'R');

        $pdf->Cell(array_sum(array_slice($w, 0, 7)), 6, 'TOTAL Factura:', 'LRB', 0, 'R');
        $pdf->Cell($w[7], 6, number_format($info['total_factura'], 2, ',', '.'), 'LRB', 1, 'R');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln(5); // Space between invoices
    }

    // Grand Total
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(array_sum(array_slice($w, 0, 7)), 8, 'TOTAL GENERAL:', 1, 0, 'R');
    $pdf->Cell($w[7], 8, number_format($total_general, 2, ',', '.'), 1, 1, 'R');
}


$pdf->Output('I', 'Informe_Cliente_' . str_replace(' ', '_', $cliente_nombre) . '.pdf');


$conn->close();
?>