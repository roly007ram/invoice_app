<?php
require_once('db_config.php');

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT id, descripcion, precio FROM detalles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'Detalle no encontrado']);
}

$stmt->close();
$conn->close();