<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Consulta SQL para seleccionar todas las empresas
$sql = "SELECT * FROM empresas ORDER BY nombre ASC";
$result = $conn->query($sql);

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
