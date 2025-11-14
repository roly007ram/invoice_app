<?php
// Evitar mostrar errores directos al navegador y capturarlos
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Forzar JSON y evitar salida previa accidental
header('Content-Type: application/json; charset=utf-8');
ob_start();

try {
    // Verifica si se ha proporcionado un ID de factura válido
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        http_response_code(400);
        $msg = json_encode(['success' => false, 'error' => 'ID de factura no proporcionado o inválido.']);
        echo $msg;
        exit;
    }

    $factura_id = intval($_GET['id']); // Convierte el ID a entero

    // Prepara la consulta SQL para eliminar una factura
    // Primero eliminar los movimientos de cuenta corriente asociados a la factura
    $deleteMovimientosSql = "DELETE FROM movimientos_cuenta_corriente WHERE factura_id = ?";
    $deleteMovimientosStmt = $conn->prepare($deleteMovimientosSql);

    if ($deleteMovimientosStmt === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta de eliminación de movimientos.']);
        exit;
    }

    $deleteMovimientosStmt->bind_param("i", $factura_id);
    $deleteMovimientosStmt->execute();
    $deleteMovimientosStmt->close();

    // Eliminar los items asociados a la factura
    $deleteSql = "DELETE FROM items WHERE factura_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);

    if ($deleteStmt === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta de eliminación de items.']);
        exit;
    }

    $deleteStmt->bind_param("i", $factura_id);
    $deleteStmt->execute();
    $deleteStmt->close();

    // Ahora eliminar la factura
    $sql = "DELETE FROM facturas WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Error en la preparación
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta de eliminación de factura.']);
        exit;
    }

    // Vincula el parámetro ID a la consulta
    $stmt->bind_param("i", $factura_id);

    // Ejecuta la consulta y devuelve una respuesta JSON
    try {
        $execResult = $stmt->execute();
        if ($execResult) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            error_log('delete_factura execute failed: ' . ($stmt->error ?: $conn->error));
            echo json_encode(['success' => false, 'error' => $stmt->error ?: $conn->error]);
        }
    } catch (Throwable $e) {
        error_log('delete_factura execute exception: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Exception durante execute: ' . $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();

} catch (Throwable $e) {
    // Captura errores fatales y devuelve JSON
    http_response_code(500);
    error_log('Error en delete_factura.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor.']);
}

// Limpiar cualquier salida accidental previa y enviar el JSON limpio
$output = ob_get_clean();
if ($output) {
    error_log('Output inesperado en delete_factura.php: ' . substr($output, 0, 1000));
    echo $output;
}

?>
