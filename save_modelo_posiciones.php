<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

require_once 'db_config.php';

$empresa_id = isset($_POST['empresa_id']) ? intval($_POST['empresa_id']) : 0;
$posiciones = isset($_POST['posiciones']) ? $_POST['posiciones'] : '[]';

if ($empresa_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Empresa inválida']);
    exit;
}

$baseDir = __DIR__ . DIRECTORY_SEPARATOR . 'pdfmodelo';
if (!is_dir($baseDir)) mkdir($baseDir, 0755, true);

$modelo_file_name = '';
// Manejar carga de archivo PDF opcional
if (isset($_FILES['modelo_pdf_file'])) {
    $f = $_FILES['modelo_pdf_file'];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $err = $f['error'];
        $msg = 'Error desconocido en la carga de archivos';
        switch ($err) {
            case UPLOAD_ERR_INI_SIZE: case UPLOAD_ERR_FORM_SIZE:
                $msg = 'El archivo excede el tamaño máximo permitido por el servidor.'; break;
            case UPLOAD_ERR_PARTIAL:
                $msg = 'El archivo fue subido parcialmente.'; break;
            case UPLOAD_ERR_NO_FILE:
                $msg = 'No se seleccionó ningún archivo.'; break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $msg = 'Falta carpeta temporal en el servidor.'; break;
            case UPLOAD_ERR_CANT_WRITE:
                $msg = 'Error al escribir el archivo en disco.'; break;
            case UPLOAD_ERR_EXTENSION:
                $msg = 'La carga fue detenida por una extensión.'; break;
        }
        echo json_encode(['success' => false, 'error' => $msg]);
        exit;
    }
    if ($f['error'] === UPLOAD_ERR_OK) {
        // proceed
    }
    
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
    $allowed = ['pdf'];
    if (!in_array(strtolower($ext), $allowed)) {
        echo json_encode(['success' => false, 'error' => 'Solo se permiten archivos PDF']);
        exit;
    }
    $timestamp = time();
    $modelo_file_name = 'modelo_' . $empresa_id . '_' . $timestamp . '.' . $ext;
    $dest = $baseDir . DIRECTORY_SEPARATOR . $modelo_file_name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        echo json_encode(['success' => false, 'error' => 'Error al mover el archivo subido. Verifique permisos de la carpeta pdfmodelo/']);
        exit;
    }
    @chmod($dest, 0644);
    // Guardar nombre en la tabla empresas
    $stmt = $conn->prepare("UPDATE empresas SET modelo_pdf = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('si', $modelo_file_name, $empresa_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Decodificar posiciones
$decodedPos = json_decode($posiciones, true);
if (!is_array($decodedPos)) $decodedPos = [];

// Leer configuración enviada (si existe)
$page_width_mm = isset($_POST['page_width_mm']) ? intval($_POST['page_width_mm']) : null;
$font_name = isset($_POST['font_name']) ? trim($_POST['font_name']) : null;
$font_size = isset($_POST['font_size']) ? intval($_POST['font_size']) : null;
// Nuevos flags de estilo
$font_bold = isset($_POST['font_bold']) ? intval($_POST['font_bold']) : null;
$font_italic = isset($_POST['font_italic']) ? intval($_POST['font_italic']) : null;
$font_underline = isset($_POST['font_underline']) ? intval($_POST['font_underline']) : null;

// Guardar posiciones en la tabla modelo_posiciones (borrar las previas y reinsertar)
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

$conn->begin_transaction();
try {
    $del = $conn->prepare("DELETE FROM modelo_posiciones WHERE empresa_id = ?");
    if ($del) { $del->bind_param('i', $empresa_id); $del->execute(); $del->close(); }

    $ins = $conn->prepare("INSERT INTO modelo_posiciones (empresa_id, key_name, label, x_pct, y_pct, page) VALUES (?,?,?,?,?,?)");
    if (!$ins) throw new Exception('Error prepare insert: ' . $conn->error);
    foreach ($decodedPos as $p) {
        $key = isset($p['key']) ? $p['key'] : (isset($p['key_name']) ? $p['key_name'] : '');
        $label = isset($p['label']) ? $p['label'] : '';
        $x = isset($p['xPct']) ? floatval($p['xPct']) : (isset($p['x_pct']) ? floatval($p['x_pct']) : 0);
        $y = isset($p['yPct']) ? floatval($p['yPct']) : (isset($p['y_pct']) ? floatval($p['y_pct']) : 0);
        $page = isset($p['page']) ? intval($p['page']) : 1;
        $ins->bind_param('issddi', $empresa_id, $key, $label, $x, $y, $page);
        $ins->execute();
    }
    $ins->close();

    // Guardar/actualizar configuración en tabla modelo_config
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

    if ($page_width_mm !== null || $font_name !== null || $font_size !== null) {
        // Obtener valores actuales si existen
        $current = ['page_width_mm' => 80, 'font_name' => 'Helvetica', 'font_size' => 10, 'font_bold' => 0, 'font_italic' => 0, 'font_underline' => 0];
        $sel = $conn->prepare("SELECT page_width_mm, font_name, font_size, font_bold, font_italic, font_underline FROM modelo_config WHERE empresa_id = ? LIMIT 1");
        if ($sel) {
            $sel->bind_param('i', $empresa_id);
            $sel->execute();
            $resSel = $sel->get_result();
            if ($rowSel = $resSel->fetch_assoc()) {
                $current['page_width_mm'] = intval($rowSel['page_width_mm']);
                $current['font_name'] = $rowSel['font_name'];
                $current['font_size'] = intval($rowSel['font_size']);
                $current['font_bold'] = isset($rowSel['font_bold']) ? intval($rowSel['font_bold']) : 0;
                $current['font_italic'] = isset($rowSel['font_italic']) ? intval($rowSel['font_italic']) : 0;
                $current['font_underline'] = isset($rowSel['font_underline']) ? intval($rowSel['font_underline']) : 0;
            }
            $sel->close();
        }

        $new_page_width = ($page_width_mm !== null) ? $page_width_mm : $current['page_width_mm'];
        $new_font_name = ($font_name !== null && $font_name !== '') ? $font_name : $current['font_name'];
        $new_font_size = ($font_size !== null && $font_size > 0) ? $font_size : $current['font_size'];
        $new_font_bold = ($font_bold !== null) ? intval($font_bold) : intval($current['font_bold']);
        $new_font_italic = ($font_italic !== null) ? intval($font_italic) : intval($current['font_italic']);
        $new_font_underline = ($font_underline !== null) ? intval($font_underline) : intval($current['font_underline']);

        // Insertar o actualizar
        $chk = $conn->prepare("SELECT id FROM modelo_config WHERE empresa_id = ? LIMIT 1");
        if ($chk) {
            $chk->bind_param('i', $empresa_id);
            $chk->execute();
            $resChk = $chk->get_result();
            if ($rowChk = $resChk->fetch_assoc()) {
                $upd = $conn->prepare("UPDATE modelo_config SET page_width_mm = ?, font_name = ?, font_size = ?, font_bold = ?, font_italic = ?, font_underline = ?, updated_at = CURRENT_TIMESTAMP WHERE empresa_id = ?");
                if ($upd) { $upd->bind_param('isiiiii', $new_page_width, $new_font_name, $new_font_size, $new_font_bold, $new_font_italic, $new_font_underline, $empresa_id); $upd->execute(); $upd->close(); }
            } else {
                $insCfg = $conn->prepare("INSERT INTO modelo_config (empresa_id, page_width_mm, font_name, font_size, font_bold, font_italic, font_underline) VALUES (?,?,?,?,?,?,?)");
                if ($insCfg) { $insCfg->bind_param('iisiiii', $empresa_id, $new_page_width, $new_font_name, $new_font_size, $new_font_bold, $new_font_italic, $new_font_underline); $insCfg->execute(); $insCfg->close(); }
            }
            $chk->close();
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'modelo_file' => $modelo_file_name]);
    exit;
} catch (Exception $e) {
    $conn->rollback();
    error_log('save_modelo_posiciones error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error al guardar posiciones en la base de datos']);
    exit;
}
?>