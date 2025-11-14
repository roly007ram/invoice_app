<?php
require_once('db_config.php');
header('Content-Type: application/json');

$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : 0;
$movimientos = [];
$saldo = 0;

if ($cliente_id > 0) {
    // Trae también el campo iva de la factura asociada si corresponde
    // Ordena por fecha descendente y limita a 12 resultados
    $sql = "SELECT m.fecha, m.tipo, m.monto, m.observacion, f.iva
            FROM movimientos_cuenta_corriente m
            LEFT JOIN facturas f ON m.factura_id = f.id
            WHERE m.cliente_id=?
            ORDER BY m.fecha DESC, m.id DESC
            LIMIT 12";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $cliente_id);
        $stmt->execute();
        $result = $stmt->get_result();
        // Recalcula el saldo basado en los movimientos completos (no solo los 12 últimos)
        // Para esto, necesitamos una segunda consulta o ajustar la lógica.
        // La forma más simple es calcular el saldo total primero y luego obtener los últimos 12 movimientos.

        // Consulta para obtener el saldo total
        $sql_saldo = "SELECT SUM(CASE WHEN tipo = 'factura' THEN monto ELSE -monto END) AS saldo_total FROM movimientos_cuenta_corriente WHERE cliente_id=?";
        $stmt_saldo = $conn->prepare($sql_saldo);
        $saldo = 0; // Reset saldo for recalculation
        if ($stmt_saldo) {
            $stmt_saldo->bind_param('i', $cliente_id);
            $stmt_saldo->execute();
            $result_saldo = $stmt_saldo->get_result();
            if ($row_saldo = $result_saldo->fetch_assoc()) {
                $saldo = $row_saldo['saldo_total'] !== null ? $row_saldo['saldo_total'] : 0;
            }
            $stmt_saldo->close();
        } else {
             // Error en la preparación de la consulta de saldo
            http_response_code(500);
            echo json_encode(['error' => 'Error en la consulta SQL de saldo: ' . $conn->error]);
            $conn->close();
            exit;
        }

        // Consulta para obtener los últimos 12 movimientos con IVA
        $sql_movimientos = "SELECT m.fecha, m.tipo, m.monto, m.observacion, f.iva
                            FROM movimientos_cuenta_corriente m
                            LEFT JOIN facturas f ON m.factura_id = f.id
                            WHERE m.cliente_id=?
                            ORDER BY m.fecha DESC, m.id DESC
                            LIMIT 12";
        $stmt_movimientos = $conn->prepare($sql_movimientos);
         if ($stmt_movimientos) {
            $stmt_movimientos->bind_param('i', $cliente_id);
            $stmt_movimientos->execute();
            $result_movimientos = $stmt_movimientos->get_result();
             while ($row = $result_movimientos->fetch_assoc()) {
                $movimientos[] = $row;
            }
            $stmt_movimientos->close();
         } else {
             // Error en la preparación de la consulta de movimientos
            http_response_code(500);
            echo json_encode(['error' => 'Error en la consulta SQL de movimientos: ' . $conn->error]);
            $conn->close();
            exit;
         }


    } else {
        // Error en la preparación de la consulta inicial (aunque ahora usamos dos)
        http_response_code(500);
        echo json_encode(['error' => 'Error en la preparación de la consulta SQL: ' . $conn->error]);
        $conn->close();
        exit;
    }
}

// Cálculo de saldo_iva según la fórmula: saldo - (saldo / 1.21)
$saldo_iva = 0;
if ($saldo > 0) {
    $saldo_iva = $saldo - ($saldo / 1.21);
}

echo json_encode([
    'movimientos' => $movimientos,
    'saldo' => number_format($saldo, 2, ',', '.'),
    'saldo_iva' => number_format($saldo_iva, 2, ',', '.')
]);
$conn->close();