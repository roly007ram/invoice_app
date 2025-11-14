<?php
require_once('db_config.php');
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$cliente_id = isset($data['cliente_id']) ? intval($data['cliente_id']) : 0;
$monto = isset($data['monto']) ? floatval($data['monto']) : 0;
$fecha = isset($data['fecha']) ? $data['fecha'] : date('Y-m-d');
$obs = isset($data['observacion']) ? $data['observacion'] : '';
if ($cliente_id > 0 && $monto > 0) {
    $sql = "INSERT INTO movimientos_cuenta_corriente (cliente_id, tipo, monto, fecha, observacion) VALUES (?, 'pago', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('idss', $cliente_id, $monto, $fecha, $obs);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}
$conn->close();
