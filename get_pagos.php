<?php
require_once('db_config.php');
header('Content-Type: application/json');

$factura_id = isset($_GET['factura_id']) ? intval($_GET['factura_id']) : 0;
if ($factura_id <= 0) {
    echo json_encode([]);
    exit;
}

$query = "SELECT id, monto, fecha_pago, observacion FROM pagos_facturas WHERE factura_id = ? ORDER BY fecha_pago DESC, id DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $factura_id);
$stmt->execute();
$result = $stmt->get_result();
$pagos = [];
while ($row = $result->fetch_assoc()) {
    $pagos[] = $row;
}
$stmt->close();
$conn->close();
echo json_encode($pagos);