<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Verifica si se ha proporcionado un ID de empresa válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $empresa_id = intval($_GET['id']); // Convierte el ID a entero

    // Prepara la consulta SQL para eliminar una empresa
    $sql = "DELETE FROM empresas WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Verifica si la preparación de la consulta fue exitosa
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta de eliminación: ' . $conn->error]);
        exit;
    }

    // Vincula el parámetro ID a la consulta
    $stmt->bind_param("i", $empresa_id);

    // Ejecuta la consulta y devuelve una respuesta JSON
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    // Cierra la declaración preparada
    $stmt->close();
    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    // Si no se proporcionó un ID de empresa, devuelve un error
    echo json_encode(['success' => false, 'error' => 'ID de empresa no proporcionado.']);
}
?>
