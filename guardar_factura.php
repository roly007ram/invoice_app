<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');
 
// Activa la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica si la conexión a la base de datos es válida
if (!$conn) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

// Verifica si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Desactiva el autocommit para iniciar una transacción
    $conn->autocommit(FALSE);

    // Sanitiza los datos de entrada del formulario de factura
    $numeroFactura = mysqli_real_escape_string($conn, $_POST["numeroFactura"]);
    $fecha = mysqli_real_escape_string($conn, $_POST["fecha"]);

    // Obtiene los IDs de empresa y cliente, si están presentes
    $empresaId = isset($_POST["empresaId"]) && !empty($_POST["empresaId"]) ? intval($_POST["empresaId"]) : null;
    $clienteId = isset($_POST["clienteId"]) && !empty($_POST["clienteId"]) ? intval($_POST["clienteId"]) : null;

    // Campos de cliente que pueden ser sobreescritos si se selecciona un cliente por ID
    $clienteNombre = mysqli_real_escape_string($conn, $_POST["clienteNombre"]);
    $clienteDomicilio = mysqli_real_escape_string($conn, $_POST["clienteDomicilio"]);
    $localidad = mysqli_real_escape_string($conn, $_POST["localidad"]);
    $clienteCuit = mysqli_real_escape_string($conn, $_POST["clienteCuit"]);
    $clienteIva = mysqli_real_escape_string($conn, $_POST["clienteIva"]);
    $condicionVenta = mysqli_real_escape_string($conn, $_POST["condicionVenta"]);

    // Validar campos obligatorios
    if (empty($_POST["empresaId"]) || empty($_POST["clienteId"]) || empty($_POST["clienteIva"]) || empty($_POST["condicionVenta"])) {
        echo "Error: Debe seleccionar una empresa, un cliente, el tipo de I.V.A. y la condición de venta.";
        exit;
    }

    // Calcula los totales (re-calculado en el servidor para seguridad y precisión)
    $subtotal = 0;
    $iva = 0;
    $total = 0;
    $cantidades = $_POST["cantidad"];
    $detalles = $_POST["detalle"];
    $preciosUnitarios = $_POST["precio_unitario"];

    // Asegura que los arrays existan y tengan la misma longitud
    if (is_array($cantidades) && is_array($detalles) && is_array($preciosUnitarios) &&
        count($cantidades) === count($detalles) && count($cantidades) === count($preciosUnitarios)) {

        for ($i = 0; $i < count($cantidades); $i++) {
            $cantidad = floatval($cantidades[$i]);
            $precioUnitario = floatval($preciosUnitarios[$i]);

            // Solo considera ítems con cantidad y precio válidos
            if ($cantidad > 0 && $precioUnitario >= 0) {
                $subtotal += $cantidad * $precioUnitario;
            }
        }
    } else {
        // Manejo de error: datos de ítems inconsistentes
        error_log("Datos de ítems inconsistentes recibidos durante el guardado de la factura.");
        $conn->rollback();
        echo "Error: Datos de ítems inconsistentes.";
        exit;
    }

    // Recalcula el IVA basado en el tipo de IVA del cliente
    if ($clienteIva === 'Resp. Inscripto') {
        $iva = $subtotal * 0.21; // 21% de IVA
    } else if ($clienteIva === 'Monotributo') {
        $iva = $subtotal * 0.105; // 10.5% de IVA para monotributo
    }
    // Para Consumidor Final y Exento, el IVA es 0

    $total = $subtotal + $iva;

    // Inserta en la tabla 'facturas', incluyendo 'empresa_id' y 'cliente_id'
    $sql_factura = "INSERT INTO facturas (numero_factura, fecha, cliente_nombre, cliente_domicilio, localidad, cliente_cuit, cliente_iva, condicion_venta, total, subtotal, iva, empresa_id, cliente_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_factura = $conn->prepare($sql_factura);

    // Verifica si la preparación de la consulta de factura fue exitosa
    if (!$stmt_factura) {
        error_log("Error en la preparación de la consulta de factura: " . $conn->error);
        $conn->rollback();
        echo "Error en la preparación de la consulta de factura.";
        exit;
    }

    // Asegura que los IDs sean enteros o null (para claves foráneas)
    $empresaId = $empresaId !== null ? $empresaId : null;
    $clienteId = $clienteId !== null ? $clienteId : null;
    // Vincula los parámetros. Si es null, usa bind_param con 'i' y pasa null
    $stmt_factura->bind_param(
        "ssssssssdddii",
        $numeroFactura,
        $fecha,
        $clienteNombre,
        $clienteDomicilio,
        $localidad,
        $clienteCuit,
        $clienteIva,
        $condicionVenta,
        $total,
        $subtotal,
        $iva,
        $empresaId,
        $clienteId
    );

    // Ejecuta la inserción de la factura principal
    // Después de guardar la factura y antes de commit, registrar el movimiento de cuenta corriente
    if ($stmt_factura->execute()) {
        $factura_id = $conn->insert_id; // Obtiene el ID de la factura recién insertada

        // Registrar movimiento de cuenta corriente solo si la condición de venta no es 'Contado'
        if (strtolower($condicionVenta) !== 'contado') {
            $sql_mov = "INSERT INTO movimientos_cuenta_corriente (cliente_id, tipo, monto, fecha, observacion, factura_id) VALUES (?, 'factura', ?, ?, ?, ?)";
            $stmt_mov = $conn->prepare($sql_mov);
            $obs = 'Factura N° ' . $numeroFactura;
            $stmt_mov->bind_param("idssi", $clienteId, $total, $fecha, $obs, $factura_id);
            $stmt_mov->execute();
            $stmt_mov->close();
        }

        // Inserta en la tabla 'items'
        $sql_items = "INSERT INTO items (factura_id, cantidad, detalle, precio_unitario) VALUES (?, ?, ?, ?)";
        $stmt_items = $conn->prepare($sql_items);

        // Verifica si la preparación de la consulta de ítems fue exitosa
        if (!$stmt_items) {
            error_log("Error en la preparación de la consulta de items: " . $conn->error);
            $conn->rollback();
            echo "Error en la preparación de la consulta de items.";
            exit;
        }

        $items_inserted = true;
        for ($i = 0; $i < count($cantidades); $i++) {
            $cantidad = floatval($cantidades[$i]);
            $detalle = mysqli_real_escape_string($conn, $detalles[$i]);
            $precioUnitario = floatval($preciosUnitarios[$i]);

            // Solo inserta ítems que tengan cantidad o descripción
            if ($cantidad > 0 || !empty($detalle) || $precioUnitario > 0) {
                 // Vincula los parámetros para cada ítem
                $stmt_items->bind_param("idsd", $factura_id, $cantidad, $detalle, $precioUnitario);
                // Si falla la inserción de algún ítem
                if (!$stmt_items->execute()) {
                    error_log("Error al insertar ítem: " . $stmt_items->error);
                    $items_inserted = false;
                    break; // Sale del bucle en el primer error de inserción de ítem
                }
            }
        }
        // Cierra la declaración preparada de ítems
        $stmt_items->close();

        // Si todos los ítems se insertaron correctamente
        if ($items_inserted) {
            $conn->commit(); // Confirma la transacción
            // Redirige de vuelta a la página de la factura con un mensaje de éxito
            header("Location: factura_.php?status=success&invoice_id=" . $factura_id);
            exit();
        } else {
            $conn->rollback(); // Revierte la transacción si falló la inserción de ítems
            echo "Error al guardar los ítems de la factura.";
        }

    } else {
        error_log("Error al guardar la factura principal: " . $stmt_factura->error);
        $conn->rollback(); // Revierte la transacción si falló la inserción de la factura principal
        echo "Error al guardar la factura principal.";
    }

    // Cierra la declaración preparada de factura
    $stmt_factura->close();
    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    echo "Acceso inválido.";
}
?>
