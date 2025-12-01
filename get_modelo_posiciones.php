<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

require_once 'db_config.php';

$empresa_id = isset($_GET['empresa_id']) ? intval($_GET['empresa_id']) : 0;
if ($empresa_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Empresa inválida']);
    exit;
}

// Obtener nombre de archivo del campo empresas.modelo_pdf
$modelo_file = '';
$stmt = $conn->prepare("SELECT modelo_pdf FROM empresas WHERE id = ? LIMIT 1");
if ($stmt) {
    $stmt->bind_param('i', $empresa_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $modelo_file = $row['modelo_pdf'] ?? '';
    }
    $stmt->close();
}

// Obtener posiciones desde la tabla modelo_posiciones
$createSql = "CREATE TABLE IF NOT EXISTS `modelo_posiciones` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `empresa_id` int(11) NOT NULL,
    `key_name` varchar(100) NOT NULL,
    `label` varchar(200) DEFAULT NULL,
    `x_pct` decimal(8,4) NOT NULL,
    `y_pct` decimal(8,4) NOT NULL,
    `page` int(3) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `empresa_id` (`empresa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
$conn->query($createSql);

$posiciones = [];
$q = $conn->prepare("SELECT key_name, label, x_pct, y_pct, page FROM modelo_posiciones WHERE empresa_id = ? ORDER BY id ASC");
if ($q) {
    $q->bind_param('i', $empresa_id);
    $q->execute();
    $res = $q->get_result();
    while ($r = $res->fetch_assoc()) {
        $posiciones[] = [
            'key' => $r['key_name'],
            'label' => $r['label'],
            'xPct' => $r['x_pct'],
            'yPct' => $r['y_pct'],
            'page' => intval($r['page'])
        ];
    }
    $q->close();
}
// Obtener configuración global del modelo (ancho, fuente, tamaño)
$createCfgSql = "CREATE TABLE IF NOT EXISTS `modelo_config` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `empresa_id` int(11) NOT NULL,
    `page_width_mm` int(11) NOT NULL DEFAULT 80,
    `font_name` varchar(100) NOT NULL DEFAULT 'Helvetica',
    `font_size` int(3) NOT NULL DEFAULT 10,
    `font_bold` tinyint(1) NOT NULL DEFAULT 0,
    `font_italic` tinyint(1) NOT NULL DEFAULT 0,
    `font_underline` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `empresa_id` (`empresa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
$conn->query($createCfgSql);

$cfg = ['page_width_mm' => 80, 'font_name' => 'Helvetica', 'font_size' => 10];
$qc = $conn->prepare("SELECT page_width_mm, font_name, font_size, font_bold, font_italic, font_underline FROM modelo_config WHERE empresa_id = ? LIMIT 1");
if ($qc) {
    $qc->bind_param('i', $empresa_id);
    $qc->execute();
    $res2 = $qc->get_result();
    if ($row2 = $res2->fetch_assoc()) {
        $cfg['page_width_mm'] = intval($row2['page_width_mm']);
        $cfg['font_name'] = $row2['font_name'];
        $cfg['font_size'] = intval($row2['font_size']);
        $cfg['font_bold'] = isset($row2['font_bold']) ? intval($row2['font_bold']) : 0;
        $cfg['font_italic'] = isset($row2['font_italic']) ? intval($row2['font_italic']) : 0;
        $cfg['font_underline'] = isset($row2['font_underline']) ? intval($row2['font_underline']) : 0;
    }
    $qc->close();
}

echo json_encode(['success' => true, 'modelo_file' => $modelo_file, 'posiciones' => $posiciones, 'modelo_config' => $cfg]);
exit;
?>