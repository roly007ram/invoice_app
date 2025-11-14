<?php
// Incluye el archivo de configuración de la base de datos
require_once('db_config.php');

// Establece el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

// Verifica si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene y sanitiza los datos del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $domicilio = mysqli_real_escape_string($conn, $_POST['domicilio']);
    $localidad = mysqli_real_escape_string($conn, $_POST['localidad']);
    $tipo_iva = mysqli_real_escape_string($conn, $_POST['tipo_iva']);
    $cuit = mysqli_real_escape_string($conn, $_POST['cuit']);
    $condicion_venta_default = mysqli_real_escape_string($conn, $_POST['condicion_venta_default']);

    // Prepara la consulta SQL para actualizar o insertar un cliente
    if ($id > 0) {
        // Si el ID es mayor que 0, es una actualización
        $sql = "UPDATE clientes SET nombre=?, domicilio=?, localidad=?, tipo_iva=?, cuit=?, condicion_venta_default=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        // Vincula los parámetros a la consulta de actualización
        $stmt->bind_param("ssssssi", $nombre, $domicilio, $localidad, $tipo_iva, $cuit, $condicion_venta_default, $id);
    } else {
        // Si el ID es 0, es una nueva inserción
        $sql = "INSERT INTO clientes (nombre, domicilio, localidad, tipo_iva, cuit, condicion_venta_default) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        // Vincula los parámetros a la consulta de inserción
        $stmt->bind_param("ssssss", $nombre, $domicilio, $localidad, $tipo_iva, $cuit, $condicion_venta_default);
    }

    // Ejecuta la consulta y devuelve una respuesta JSON
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    // Cierra la declaración preparada
    $stmt->close();
    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    // Si la solicitud no es POST, devuelve un error
    echo json_encode(['success' => false, 'error' => 'Método de solicitud inválido.']);
}
?>

