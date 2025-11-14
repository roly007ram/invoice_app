<?php
require_once('db_config.php');

header('Content-Type: application/json');

$response = ['success' => false, 'error' => null];

$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$descripcion = trim($_POST['descripcion']);
$precio = floatval($_POST['precio']);
$rubro_id = isset($_POST['rubro_id']) && !empty($_POST['rubro_id']) ? intval($_POST['rubro_id']) : null;

if (empty($descripcion)) {
    $response['error'] = 'La descripción es requerida';
    echo json_encode($response);
    exit;
}

if ($id) {
    // Update
    $stmt = $conn->prepare("UPDATE detalles SET descripcion = ?, precio = ?, rubro_id = ? WHERE id = ?");
    $stmt->bind_param("sdii", $descripcion, $precio, $rubro_id, $id);
} else {
    // Insert
    $stmt = $conn->prepare("INSERT INTO detalles (descripcion, precio, rubro_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $descripcion, $precio, $rubro_id);
}

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['error'] = $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);