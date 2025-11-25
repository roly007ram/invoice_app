<?php
require_once('db_config.php');
header('Content-Type: application/json');

$detalles = [];

try {
    // Usar LEFT JOIN con la tabla rubro (la que tiene datos)
    // y verificar si existe la columna rubro_id en detalles
    $sql = "SELECT d.id, d.descripcion, d.precio, d.rubro_id, 
            COALESCE(r.nombre, 'Sin Rubro') as rubro_nombre
            FROM detalles d
            LEFT JOIN rubro r ON d.rubro_id = r.id
            ORDER BY d.descripcion ASC";

    error_log("SQL ejecutada: " . $sql);
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $detalles[] = [
                'id' => $row['id'],
                'descripcion' => $row['descripcion'],
                'precio' => $row['precio'],
                'rubro_id' => $row['rubro_id'],
                'rubro_nombre' => $row['rubro_nombre']
            ];
        }
        echo json_encode($detalles);
    } else {
        throw new Exception($conn->error);
    }
} catch (Exception $e) {
    error_log("Error en get_detalles.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta de productos/servicios: ' . $e->getMessage()]);
} finally {
    if ($conn) {
        $conn->close();
    }
}