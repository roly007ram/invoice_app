<?php
require_once('db_config.php');
header('Content-Type: application/json');

$detalles = [];

try {
    // Primero verificar si la tabla rubros existe
    $check_table = "SHOW TABLES LIKE 'rubros'";
    $table_exists = $conn->query($check_table);

    if ($table_exists && $table_exists->num_rows > 0) {
        // La tabla rubros existe, verificar la columna rubro_id
        $check_column = "SHOW COLUMNS FROM detalles LIKE 'rubro_id'";
        $column_exists = $conn->query($check_column);

        if ($column_exists && $column_exists->num_rows > 0) {
            $sql = "SELECT d.id, d.descripcion, d.precio, d.rubro_id, r.nombre as rubro_nombre
                    FROM detalles d
                    LEFT JOIN rubros r ON d.rubro_id = r.id
                    ORDER BY d.descripcion ASC";
        } else {
            $sql = "SELECT id, descripcion, precio,
                    NULL as rubro_id, 'Sin Rubro' as rubro_nombre
                    FROM detalles
                    ORDER BY descripcion ASC";
        }
    } else {
        // Si la tabla rubros no existe, devolver datos sin rubro
        $sql = "SELECT id, descripcion, precio,
                NULL as rubro_id, 'Sin Rubro' as rubro_nombre
                FROM detalles
                ORDER BY descripcion ASC";
    }    error_log("SQL ejecutada: " . $sql);
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