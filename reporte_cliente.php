<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require 'db_config.php';

// Fetch clients for the dropdown
$clientes = [];
$result = $conn->query("SELECT id, nombre FROM clientes ORDER BY nombre");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe por Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Generar Informe por Cliente</h2>
        <form id="informeForm" action="generar_informe_pdf.php" method="post" target="_blank">
            <div class="mb-3">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select class="form-select" id="cliente_id" name="cliente_id" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo htmlspecialchars($cliente['id']); ?>">
                            <?php echo htmlspecialchars($cliente['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" onclick="setTarget('pdf')">Generar PDF</button>
            <button type="submit" class="btn btn-info" onclick="setTarget('csv')">Generar CSV</button>
            <a href="index.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>

    <script>
        function setTarget(format) {
            const form = document.getElementById('informeForm');
            if (format === 'pdf') {
                form.action = 'generar_informe_pdf.php';
                form.target = '_blank';
            } else if (format === 'csv') {
                form.action = 'generar_informe_csv.php';
                form.target = '_self'; // O '_blank' si prefieres
            }
        }
    </script>
</body>
</html>
