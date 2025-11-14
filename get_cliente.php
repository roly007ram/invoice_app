<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Verifica si se ha proporcionado un ID de cliente válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $cliente_id = intval($_GET['id']); // Convierte el ID a entero

    // Prepara la consulta SQL para seleccionar un cliente por su ID
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Verifica si la preparación de la consulta fue exitosa
    if (!$stmt) {
        echo json_encode(['error' => 'Error al preparar la consulta de cliente: ' . $conn->error]);
        exit;
    }

    // Vincula el parámetro ID a la consulta
    $stmt->bind_param("i", $cliente_id);
    // Ejecuta la consulta
    $stmt->execute();
    // Obtiene el resultado de la consulta
    $result = $stmt->get_result();
    // Obtiene la fila del cliente como un array asociativo
    $cliente = $result->fetch_assoc();
    // Cierra la declaración preparada
    $stmt->close();
    // Cierra la conexión a la base de datos
    $conn->close();

    // Devuelve los datos del cliente o un mensaje de error si no se encontró
    if ($cliente) {
        echo json_encode($cliente);
    } else {
        echo json_encode(['error' => 'Cliente no encontrado.']);
    }
} else {
    // Si no se proporcionó un ID de cliente, devuelve un error
    echo json_encode(['error' => 'ID de cliente no proporcionado.']);
}
?>
