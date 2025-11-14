<?php
// Evitar mostrar errores directos al navegador y capturarlos
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Nota: debug temporal eliminado. Errores críticos se registran con error_log().

// Forzar JSON y evitar salida previa accidental
header('Content-Type: application/json; charset=utf-8');
ob_start();

try {
    // Verifica si se ha proporcionado un ID de cliente válido
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        http_response_code(400);
    $msg = json_encode(['success' => false, 'error' => 'ID de cliente no proporcionado o inválido.']);
    echo $msg;
        exit;
    }

    $cliente_id = intval($_GET['id']); // Convierte el ID a entero

    // Prepara la consulta SQL para eliminar un cliente
    // Antes de borrar, comprobar dependencias en tablas relacionadas
    $checkSql = "SELECT COUNT(*) as cnt FROM movimientos_cuenta_corriente WHERE cliente_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    if ($checkStmt !== false) {
        $checkStmt->bind_param('i', $cliente_id);
        $checkStmt->execute();
        $res = $checkStmt->get_result();
        if ($res) {
            $row = $res->fetch_assoc();
            if (!empty($row['cnt'])) {
                // Hay movimientos asociados: no permitir borrado
                http_response_code(409);
                $msg = json_encode(['success' => false, 'error' => 'No se puede eliminar el cliente porque tiene movimientos en cuenta corriente asociados.']);
                error_log('delete_cliente: prevented deletion, movimientos count=' . $row['cnt']);
                echo $msg;
                exit;
            }
        }
        $checkStmt->close();
    }

    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);



    if ($stmt === false) {
        // Error en la preparación
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta de eliminación.']);
        exit;
    }

    // Vincula el parámetro ID a la consulta
    $stmt->bind_param("i", $cliente_id);
    // bind_param realizado

    // Ejecuta la consulta y devuelve una respuesta JSON
    try {
        $execResult = $stmt->execute();
        if ($execResult) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            error_log('delete_cliente execute failed: ' . ($stmt->error ?: $conn->error));
            echo json_encode(['success' => false, 'error' => $stmt->error ?: $conn->error]);
        }
    } catch (Throwable $e) {
        error_log('delete_cliente execute exception: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Exception durante execute: ' . $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();

} catch (Throwable $e) {
    // Captura errores fatales y devuelve JSON
    http_response_code(500);
    // Log del error en el servidor (archivo de log o error_log)
    error_log('Error en delete_cliente.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor.']);
}

// Limpiar cualquier salida accidental previa y enviar el JSON limpio
// Recuperar el contenido bufferizado y enviarlo al cliente
$output = ob_get_clean();
if ($output) {
    // Registrar cualquier salida inesperada para depuración
    error_log('Output inesperado en delete_cliente.php: ' . substr($output, 0, 1000));
    // Enviar el contenido JSON (o lo que se haya generado) al cliente
    echo $output;
}

?>
