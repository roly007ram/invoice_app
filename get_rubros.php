<?php
require_once('db_config.php');
header('Content-Type: application/json');

$sql = "SELECT id, nombre, descripcion FROM rubro ORDER BY nombre";
$result = $conn->query($sql);

if ($result) {
    $rubros = array();
    while ($row = $result->fetch_assoc()) {
        $rubros[] = $row;
    }
    echo json_encode($rubros);
} else {
    echo json_encode(['error' => 'Error al obtener rubros: ' . $conn->error]);
}

$conn->close();
?>