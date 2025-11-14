<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Asegurarse de que no haya salida antes de los headers
ob_start();

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Función de logging mejorada
function log_error($message, $data = null) {
    $logFile = '/tmp/save_empresa_debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message";
    if ($data !== null) {
        $logMessage .= "\nData: " . print_r($data, true);
    }
    $logMessage .= "\n";
    error_log($logMessage, 3, $logFile);
}

// Log de inicio
log_error('Iniciando save_empresa.php');
log_error('POST Data:', $_POST);

// Logging temporal para depuración (escribe en /tmp)
function _save_empresa_log($m) {
    $f = '/tmp/save_empresa_debug.log';
    $t = date('Y-m-d H:i:s');
    @file_put_contents($f, "[$t] " . $m . "\n", FILE_APPEND);
}
_save_empresa_log('Invocado: ' . $_SERVER['REQUEST_METHOD']);
_save_empresa_log('POST keys: ' . json_encode(array_keys($_POST)));

    // Verifica si la solicitud es de tipo POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Permitir subida de archivos
        if (!empty($_FILES['modelo_pdf']) && $_FILES['modelo_pdf']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/pdfmodelo/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileTmpPath = $_FILES['modelo_pdf']['tmp_name'];
            $fileName = basename($_FILES['modelo_pdf']['name']);
            $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName); // Sanitiza nombre
            $destPath = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $modelo_pdf_path = 'pdfmodelo/' . $fileName;
            } else {
                $modelo_pdf_path = null;
            }
        } else {
            $modelo_pdf_path = isset($_POST['modelo_pdf_actual']) ? $_POST['modelo_pdf_actual'] : null;
        }
        log_error('Procesando solicitud POST');

        // Verificar que los campos requeridos estén presentes
        $required_fields = ['nombre', 'tipo_contribuyente', 'cuit'];
        $missing_fields = [];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                $missing_fields[] = $field;
            }
        }
        if (!empty($missing_fields)) {
            log_error('Campos requeridos faltantes:', $missing_fields);
            ob_clean();
            echo json_encode(['success' => false, 'error' => 'Campos requeridos faltantes: ' . implode(', ', $missing_fields)]);
            exit;
        }

        // Obtiene y sanitiza los datos del formulario
        try {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $nombre = mysqli_real_escape_string($conn, $_POST['nombre'] ?? '');
            $direccion = mysqli_real_escape_string($conn, $_POST['direccion'] ?? '');
            $codigo_postal = mysqli_real_escape_string($conn, $_POST['codigo_postal'] ?? '');
            $tipo_contribuyente = mysqli_real_escape_string($conn, $_POST['tipo_contribuyente'] ?? '');
            $actividad = mysqli_real_escape_string($conn, $_POST['actividad'] ?? '');
            $cuit = mysqli_real_escape_string($conn, $_POST['cuit'] ?? '');
            $ingresos_brutos = mysqli_real_escape_string($conn, $_POST['ingresos_brutos'] ?? '');
            // Manejo especial para las fechas
            $inicio_actividad = !empty($_POST['inicio_actividad']) ?
                mysqli_real_escape_string($conn, $_POST['inicio_actividad']) : null;

            $registradora_fiscal = mysqli_real_escape_string($conn, $_POST['registradora_fiscal'] ?? '');
            $codigo_barra_cai = mysqli_real_escape_string($conn, $_POST['codigo_barra_cai'] ?? '');

            $fecha_vencimiento_cai = !empty($_POST['fecha_vencimiento_cai']) ?
                mysqli_real_escape_string($conn, $_POST['fecha_vencimiento_cai']) : null;

            // Log de las fechas para debugging
            log_error('Fechas procesadas:', [
                'inicio_actividad' => $inicio_actividad,
                'fecha_vencimiento_cai' => $fecha_vencimiento_cai
            ]);

            log_error('Datos sanitizados correctamente', [
                'id' => $id,
                'nombre' => $nombre,
                'tipo_contribuyente' => $tipo_contribuyente,
                'cuit' => $cuit
            ]);
        } catch (Exception $e) {
            log_error('Error al sanitizar datos:', $e->getMessage());
            ob_clean();
            echo json_encode(['success' => false, 'error' => 'Error al procesar los datos del formulario']);
            exit;
        }    try {
        log_error('Preparando consulta SQL');
    // Prepara la consulta SQL para actualizar o insertar una empresa
    if ($id > 0) {
            log_error('Actualizando empresa existente, ID: ' . $id);
            // Si el ID es mayor que 0, es una actualización
            // Construir la consulta UPDATE usando COALESCE para manejar NULL en las fechas
            $sql = "UPDATE empresas SET
                nombre=?,
                direccion=?,
                codigo_postal=?,
                tipo_contribuyente=?,
                actividad=?,
                cuit=?,
                ingresos_brutos=?,
                inicio_actividad=NULLIF(?, ''),
                registradora_fiscal=?,
                codigo_barra_cai=?,
                fecha_vencimiento_cai=NULLIF(?, ''),
                modelo_pdf=?
                WHERE id=?";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                log_error('Error en prepare (UPDATE):', $conn->error);
                echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
                $conn->close();
                exit;
            }
            // Vincula los parámetros a la consulta de actualización
            if (!$stmt->bind_param("ssssssssssssi",
                $nombre,
                $direccion,
                $codigo_postal,
                $tipo_contribuyente,
                $actividad,
                $cuit,
                $ingresos_brutos,
                $inicio_actividad,
                $registradora_fiscal,
                $codigo_barra_cai,
                $fecha_vencimiento_cai,
                $modelo_pdf_path,
                $id
            )) {
                log_error('Error en bind_param (UPDATE):', $stmt->error);
                echo json_encode(['success' => false, 'error' => 'bind_param failed: ' . $stmt->error]);
                $stmt->close(); $conn->close(); exit;
            }
            log_error('Parámetros vinculados correctamente (UPDATE)');
        } else {
            log_error('Insertando nueva empresa');
            // Si el ID es 0, es una nueva inserción
            // Construir la consulta INSERT usando NULLIF para las fechas
            $sql = "INSERT INTO empresas (
                nombre,
                direccion,
                codigo_postal,
                tipo_contribuyente,
                actividad,
                cuit,
                ingresos_brutos,
                inicio_actividad,
                registradora_fiscal,
                codigo_barra_cai,
                fecha_vencimiento_cai,
                modelo_pdf
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?,
                NULLIF(?, ''),
                ?, ?,
                NULLIF(?, ''),
                ?
            )";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                log_error('Error en prepare (INSERT):', $conn->error);
                echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
                $conn->close();
                exit;
            }
            // Vincula los parámetros a la consulta de inserción
            if (!$stmt->bind_param("ssssssssssss",
                $nombre,
                $direccion,
                $codigo_postal,
                $tipo_contribuyente,
                $actividad,
                $cuit,
                $ingresos_brutos,
                $inicio_actividad,
                $registradora_fiscal,
                $codigo_barra_cai,
                $fecha_vencimiento_cai,
                $modelo_pdf_path
            )) {
                log_error('Error en bind_param (INSERT):', $stmt->error);
                echo json_encode(['success' => false, 'error' => 'bind_param failed: ' . $stmt->error]);
                $stmt->close(); $conn->close(); exit;
            }
            log_error('Parámetros vinculados correctamente (INSERT)');
        }
    } catch (Exception $e) {
        log_error('Error en preparación de consulta:', $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Error en preparación de consulta: ' . $e->getMessage()]);
        if (isset($stmt)) $stmt->close();
        $conn->close();
        exit;
    }

    try {
        log_error('Ejecutando consulta SQL');
        // Ejecuta la consulta y devuelve una respuesta JSON
        if ($stmt->execute()) {
            log_error('Consulta ejecutada exitosamente');
            echo json_encode(['success' => true]);
        } else {
            $error = $stmt->error ? $stmt->error : $conn->error;
            log_error('Error al ejecutar consulta:', $error);
            echo json_encode(['success' => false, 'error' => $error]);
        }
    } catch (Exception $e) {
        log_error('Excepción al ejecutar consulta:', $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Error al ejecutar consulta: ' . $e->getMessage()]);
    } finally {
        // Cierra la declaración preparada y la conexión
        log_error('Cerrando conexiones');
        try {
            if ($stmt) $stmt->close();
            if ($conn) $conn->close();
        } catch (Exception $e) {
            log_error('Error al cerrar conexiones:', $e->getMessage());
        }
    }
} else {
    // Si la solicitud no es POST, devuelve un error
    echo json_encode(['success' => false, 'error' => 'Método de solicitud inválido.']);
}
?>
