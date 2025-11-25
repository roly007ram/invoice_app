<?php
require_once('db_config.php');
header('Content-Type: application/json');

$results = [];

try {
    $sql = "SELECT DISTINCT tipo_fac FROM empresas WHERE tipo_fac IS NOT NULL AND tipo_fac <> ''";
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $results[] = $row['tipo_fac'];
        }
    }
} catch (Throwable $e) {
    // ignore
}

// Ensure the main options are present
$defaults = ['Tique', 'Electrónica', 'Offline', 'Modelo PDF'];
foreach ($defaults as $d) {
    if (!in_array($d, $results)) $results[] = $d;
}

echo json_encode(array_values($results));
$conn->close();
?>