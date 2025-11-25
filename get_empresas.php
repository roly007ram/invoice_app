<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Consulta SQL para seleccionar todas las empresas
$tipo_fac = isset($_GET['tipo_fac']) ? trim($_GET['tipo_fac']) : '';

if ($tipo_fac !== '') {
    $stmt = $conn->prepare("SELECT * FROM empresas WHERE tipo_fac = ? ORDER BY nombre ASC");
    if ($stmt) {
        $stmt->bind_param('s', $tipo_fac);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Fallback to no-filter query if prepare fails
        $result = $conn->query("SELECT * FROM empresas ORDER BY nombre ASC");
    }
} else {
    $result = $conn->query("SELECT * FROM empresas ORDER BY nombre ASC");
}

$empresas = [];
// Recorre los resultados y los añade al array de empresas
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $empresas[] = $row;
    }
}

// Cierra la conexión a la base de datos
$conn->close();

// Devuelve el array de empresas como JSON
echo json_encode($empresas);
?>
