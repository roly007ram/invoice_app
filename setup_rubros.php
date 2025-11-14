<?php
require_once('db_config.php');

try {
    // Leer el contenido del archivo SQL
    $sql = file_get_contents('add_rubros_table.sql');

    // Dividir el contenido en declaraciones individuales
    $queries = array_filter(array_map('trim', explode(';', $sql)));

    // Ejecutar cada consulta
    foreach ($queries as $query) {
        if (!empty($query)) {
            if (!$conn->query($query)) {
                throw new Exception("Error ejecutando consulta: " . $conn->error);
            }
        }
    }

    echo "Tablas creadas/actualizadas exitosamente\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    if ($conn) {
        $conn->close();
    }
}