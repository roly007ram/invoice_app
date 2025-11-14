<?php
require_once('db_config.php');
header('Content-Type: application/json');
$response = ['success' => false, 'error' => null];

$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$monto = isset($_POST['monto']) ? floatval($_POST['monto']) : 0;
$fecha_pago = isset($_POST['fecha_pago']) ? $_POST['fecha_pago'] : '';
$observacion = isset($_POST['observacion']) ? trim($_POST['observacion']) : '';

// Se requiere el id de la factura
$factura_id = isset($_GET['factura_id']) ? intval($_GET['factura_id']) : (isset($_POST['factura_id']) ? intval($_POST['factura_id']) : 0);
if ($factura_id <= 0) {
    // Intentar obtenerlo de la factura seleccionada en el frontend
    if (isset($_POST['factura_id'])) {
        $factura_id = intval($_POST['factura_id']);
    } elseif (isset($_GET['factura_id'])) {
        $factura_id = intval($_GET['factura_id']);
    }
}
if ($factura_id <= 0) {
    $response['error'] = 'Factura no especificada.';
    echo json_encode($response);
    exit;
}

if ($monto <= 0 || empty($fecha_pago)) {
    $response['error'] = 'Monto y fecha requeridos.';
    echo json_encode($response);
    exit;
}

if ($id) {
    $stmt = $conn->prepare("UPDATE pagos_facturas SET monto=?, fecha_pago=?, observacion=? WHERE id=?");
    $stmt->bind_param("dssi", $monto, $fecha_pago, $observacion, $id);
} else {
    $stmt = $conn->prepare("INSERT INTO pagos_facturas (factura_id, monto, fecha_pago, observacion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $factura_id, $monto, $fecha_pago, $observacion);
}
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['error'] = $stmt->error;
}
$stmt->close();
$conn->close();
echo json_encode($response);