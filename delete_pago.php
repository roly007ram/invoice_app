<?php
require_once('db_config.php');
header('Content-Type: application/json');
$response = ['success' => false, 'error' => null];

if (!isset($_GET['id'])) {
    $response['error'] = 'ID no proporcionado';
    echo json_encode($response);
    exit;
}
$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM pagos_facturas WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['error'] = $stmt->error;
}
$stmt->close();
$conn->close();
echo json_encode($response);