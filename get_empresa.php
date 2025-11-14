<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Verifica si se ha proporcionado un ID de empresa válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $empresa_id = intval($_GET['id']); // Convierte el ID a entero

    // Prepara la consulta SQL para seleccionar una empresa por su ID
    $sql = "SELECT * FROM empresas WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Verifica si la preparación de la consulta fue exitosa
    if (!$stmt) {
        echo json_encode(['error' => 'Error al preparar la consulta de empresa: ' . $conn->error]);
        exit;
    }

    // Vincula el parámetro ID a la consulta
    $stmt->bind_param("i", $empresa_id);
    // Ejecuta la consulta
    $stmt->execute();
    // Obtiene el resultado de la consulta
    $result = $stmt->get_result();
    // Obtiene la fila de la empresa como un array asociativo
    $empresa = $result->fetch_assoc();
    // Cierra la declaración preparada
    $stmt->close();
    // Cierra la conexión a la base de datos
    $conn->close();

    // Devuelve los datos de la empresa o un mensaje de error si no se encontró
    if ($empresa) {
        echo json_encode($empresa);
    } else {
        echo json_encode(['error' => 'Empresa no encontrada.']);
    }
} else {
    // Si no se proporcionó un ID de empresa, devuelve un error
    echo json_encode(['error' => 'ID de empresa no proporcionado.']);
}
?>
