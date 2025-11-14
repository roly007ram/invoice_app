<?php
require_once('db_config.php');
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT id, nombre, descripcion FROM rubro WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Rubro no encontrado']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}

$conn->close();
?>