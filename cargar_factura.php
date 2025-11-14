<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Verifica si se ha proporcionado un ID de factura válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $factura_id = intval($_GET['id']); // Convierte el ID a entero

    // Prepara la consulta para seleccionar los detalles de la factura
    // Incluye un LEFT JOIN para obtener los datos de la empresa y el cliente
    // (si están asociados a la factura)
    $sql_factura = "SELECT f.*,
                           e.nombre AS empresa_nombre,
                           e.direccion AS empresa_direccion,
                           e.codigo_postal AS empresa_codigo_postal,
                           e.tipo_contribuyente AS empresa_tipo_contribuyente,
                           e.cuit AS empresa_cuit,
                           e.ingresos_brutos AS empresa_ingresos_brutos,
                           e.inicio_actividad AS empresa_inicio_actividad,
                           e.registradora_fiscal AS empresa_registradora_fiscal,
                           e.codigo_barra_cai AS empresa_codigo_barra_cai,
                           e.fecha_vencimiento_cai AS empresa_fecha_vencimiento_cai,
                           c.nombre AS cliente_nombre_db,
                           c.domicilio AS cliente_domicilio_db,
                           c.localidad AS cliente_localidad_db,
                           c.tipo_iva AS cliente_tipo_iva_db,
                           c.cuit AS cliente_cuit_db,
                           c.condicion_venta_default AS cliente_condicion_venta_default_db
                    FROM facturas f
                    LEFT JOIN empresas e ON f.empresa_id = e.id
                    LEFT JOIN clientes c ON f.cliente_id = c.id
                    WHERE f.id = ?";
    $stmt_factura = $conn->prepare($sql_factura);

    // Verifica si la preparación de la consulta de factura fue exitosa
    if (!$stmt_factura) {
        echo json_encode(['error' => 'Error al preparar la consulta de factura: ' . $conn->error]);
        exit;
    }

    // Vincula el parámetro ID a la consulta
    $stmt_factura->bind_param("i", $factura_id);
    // Ejecuta la consulta
    $stmt_factura->execute();
    // Obtiene el resultado de la consulta
    $result_factura = $stmt_factura->get_result();
    // Obtiene la fila de la factura como un array asociativo
    $factura = $result_factura->fetch_assoc();
    // Cierra la declaración preparada de factura
    $stmt_factura->close();

    // Si se encontró la factura
    if ($factura) {
        // Prepara la consulta para seleccionar los ítems de la factura
        $sql_items = "SELECT * FROM items WHERE factura_id = ?";
        $stmt_items = $conn->prepare($sql_items);

        // Verifica si la preparación de la consulta de ítems fue exitosa
        if (!$stmt_items) {
            echo json_encode(['error' => 'Error al preparar la consulta de ítems: ' . $conn->error]);
            exit;
        }

        // Vincula el parámetro factura_id a la consulta de ítems
        $stmt_items->bind_param("i", $factura_id);
        // Ejecuta la consulta
        $stmt_items->execute();
        // Obtiene el resultado de la consulta
        $result_items = $stmt_items->get_result();
        $items = [];
        // Recorre los resultados y los añade al array de ítems
        while ($row = $result_items->fetch_assoc()) {
            $items[] = $row;
        }
        // Cierra la declaración preparada de ítems
        $stmt_items->close();

        // Devuelve los datos de la factura y sus ítems como JSON
        echo json_encode(['factura' => $factura, 'items' => $items]);
    } else {
        // Si no se encontró la factura, devuelve un error
        echo json_encode(['error' => 'Factura no encontrada.']);
    }

    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    // Si no se proporcionó un ID de factura, devuelve un error
    echo json_encode(['error' => 'ID de factura no proporcionado.']);
}
?>
