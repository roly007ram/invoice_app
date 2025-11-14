<?php
require_once('db_config.php');
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);

    if ($id > 0) {
        // Actualización
        $sql = "UPDATE rubro SET nombre=?, descripcion=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
    } else {
        // Inserción
        $sql = "INSERT INTO rubro (nombre, descripcion) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $descripcion);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}

$conn->close();
?>