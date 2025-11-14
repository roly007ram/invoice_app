<?php
require_once('db_config.php');
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Primero verificar si el rubro está en uso
    $check_sql = "SELECT COUNT(*) as count FROM detalles WHERE rubro_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo json_encode(['success' => false, 'error' => 'No se puede eliminar el rubro porque está siendo utilizado']);
        $check_stmt->close();
        $conn->close();
        exit;
    }

    $check_stmt->close();

    // Si no está en uso, proceder con la eliminación
    $sql = "DELETE FROM rubro WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
}

$conn->close();
?>