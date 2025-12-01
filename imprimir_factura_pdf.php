<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

// imprimir_factura_pdf.php
// Requiere: composer require setasign/fpdf setasign/fpdi
require_once('vendor/autoload.php');
require_once('db_config.php');

use setasign\Fpdi\Fpdi;

if (!isset($_GET['factura_id'])) {
    die('Falta el parámetro factura_id');
}
$factura_id = intval($_GET['factura_id']);

// Obtener datos de la factura y la empresa
$sql = "SELECT f.*, e.modelo_pdf, e.nombre AS empresa_nombre, e.cuit AS empresa_cuit, f.empresa_id 
        FROM facturas f 
        LEFT JOIN empresas e ON f.empresa_id = e.id 
        WHERE f.id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Error en la consulta: ' . $conn->error);
}

if (!$stmt->bind_param('i', $factura_id)) {
    die('Error al vincular parámetros: ' . $stmt->error);
}

if (!$stmt->execute()) {
    die('Error al ejecutar la consulta: ' . $stmt->error);
}

$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    die('Factura no encontrada. ID: ' . $factura_id . '. Verifique que la factura existe y tiene una empresa asociada.');
}

// Crear PDF
$pdf = new Fpdi();

// Intentar cargar el modelo PDF si existe (archivo en pdfmodelo/)
$modelo_pdf = $row['modelo_pdf'];
$modelo_cargado = false;
$templatePath = '';
if ($modelo_pdf) {
    $candidate = __DIR__ . DIRECTORY_SEPARATOR . 'pdfmodelo' . DIRECTORY_SEPARATOR . $modelo_pdf;
    if (file_exists($candidate)) {
        $templatePath = $candidate;
    }
}

// Obtener posiciones desde DB (por empresa)
$positions = [];
$empresaId = intval($row['empresa_id']);
if ($empresaId > 0) {
    // Asegurar existencia de la tabla (por si no se migró)
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

    $pq = $conn->prepare("SELECT key_name, label, x_pct, y_pct, page FROM modelo_posiciones WHERE empresa_id = ? ORDER BY id ASC");
    if ($pq) {
        $pq->bind_param('i', $empresaId);
        $pq->execute();
        $res = $pq->get_result();
        while ($r = $res->fetch_assoc()) {
            $positions[] = $r;
        }
        $pq->close();
    }
}

// Obtener configuración del modelo (page_width_mm, font_name, font_size)
 $modelo_config = ['page_width_mm' => 80, 'font_name' => 'Helvetica', 'font_size' => 10, 'font_bold' => 0, 'font_italic' => 0, 'font_underline' => 0];
 $cfg_q = $conn->prepare("CREATE TABLE IF NOT EXISTS `modelo_config` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
$conn->query("CREATE TABLE IF NOT EXISTS `modelo_config` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `empresa_id` int(11) NOT NULL,
      `page_width_mm` int(11) NOT NULL DEFAULT 80,
      `font_name` varchar(100) NOT NULL DEFAULT 'Helvetica',
      `font_size` int(3) NOT NULL DEFAULT 10,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `empresa_id` (`empresa_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
 $qc = $conn->prepare("SELECT page_width_mm, font_name, font_size, font_bold, font_italic, font_underline FROM modelo_config WHERE empresa_id = ? LIMIT 1");
if ($qc) {
    $qc->bind_param('i', $empresaId);
    $qc->execute();
    $resCfg = $qc->get_result();
    if ($rowCfg = $resCfg->fetch_assoc()) {
        $modelo_config['page_width_mm'] = intval($rowCfg['page_width_mm']);
        $modelo_config['font_name'] = $rowCfg['font_name'];
        $modelo_config['font_size'] = intval($rowCfg['font_size']);
        $modelo_config['font_bold'] = isset($rowCfg['font_bold']) ? intval($rowCfg['font_bold']) : 0;
        $modelo_config['font_italic'] = isset($rowCfg['font_italic']) ? intval($rowCfg['font_italic']) : 0;
        $modelo_config['font_underline'] = isset($rowCfg['font_underline']) ? intval($rowCfg['font_underline']) : 0;
    }
    $qc->close();
}

// Si tenemos plantilla -> renderizar páginas y colocar campos según posiciones
if ($templatePath) {
    try {
        $pageCount = $pdf->setSourceFile($templatePath);
        // Agrupar posiciones por página
        $posByPage = [];
        foreach ($positions as $p) {
            $pg = max(1, intval($p['page']));
            if (!isset($posByPage[$pg])) $posByPage[$pg] = [];
            $posByPage[$pg][] = $p;
        }

        // Obtener items de la factura
        $items = [];
        $itq = $conn->prepare("SELECT cantidad, detalle, precio_unitario FROM items WHERE factura_id = ? ORDER BY id ASC");
        if ($itq) {
            $itq->bind_param('i', $factura_id);
            $itq->execute();
            $r = $itq->get_result();
            while ($it = $r->fetch_assoc()) { $items[] = $it; }
            $itq->close();
        }

        for ($p=1; $p <= $pageCount; $p++) {
            $tplIdx = $pdf->importPage($p);
            $size = $pdf->getTemplateSize($tplIdx);

            // Validar dimensiones del template
            $origWidth = floatval($size['width']);
            $origHeight = floatval($size['height']);
            if ($origWidth <= 0 || $origHeight <= 0) {
                // Usar valores por defecto si template está corrupto
                $origWidth = 210;
                $origHeight = 297;
            }

            // Calcular escala para adaptar el ancho solicitado (page_width_mm)
            $targetWidth = floatval($modelo_config['page_width_mm']);
            if ($targetWidth <= 0 || $targetWidth > 1000) $targetWidth = $origWidth;
            $scale = $targetWidth / $origWidth;
            $targetHeight = $origHeight * $scale;

            // Validar escala (rango razonable 0.1x a 10x)
            if ($scale < 0.1 || $scale > 10) {
                // Scale fuera de rango; usar original
                $targetWidth = $origWidth;
                $targetHeight = $origHeight;
                $scale = 1.0;
            }

            // Añadir página con tamaño personalizado para mantener proporción
            $pdf->AddPage('', [$targetWidth, $targetHeight]);
            // Colocar template escalado desde origen (0, 0)
            $pdf->useTemplate($tplIdx, 0, 0, $targetWidth);

            // Render positions for this page
            if (isset($posByPage[$p])) {
                // map key => first position (base)
                $basePos = [];
                foreach ($posByPage[$p] as $pp) {
                    $basePos[$pp['key_name']][] = $pp;
                }

                // Configurar fuente desde la configuración del modelo y estilo (B/I/U)
                $fontToUse = $modelo_config['font_name'] ?: 'Helvetica';
                $fontSize = intval($modelo_config['font_size']) ?: 10;
                $fontStyle = '';
                if (!empty($modelo_config['font_bold'])) $fontStyle .= 'B';
                if (!empty($modelo_config['font_italic'])) $fontStyle .= 'I';
                if (!empty($modelo_config['font_underline'])) $fontStyle .= 'U';
                $pdf->SetFont($fontToUse, $fontStyle, $fontSize);
                $pdf->SetTextColor(0,0,0);

                // Helper to draw text at percentual position (usa targetWidth/targetHeight)
                $drawText = function($txt, $xPct, $yPct) use ($pdf, $targetWidth, $targetHeight) {
                    $x = ($xPct / 100.0) * $targetWidth;
                    $y = ($yPct / 100.0) * $targetHeight;
                    $pdf->SetXY($x, $y);
                    // No wrapping: use Write
                    $pdf->Write(0, $txt);
                };

                // Map of simple fields
                $fieldMap = [
                    'clienteNombre' => $row['cliente_nombre'],
                    'clienteCuit' => $row['cliente_cuit'],
                    'clienteDomicilio' => $row['cliente_domicilio'],
                    'localidad' => $row['localidad'],
                    'clienteIva' => $row['cliente_iva'],
                    'condicionVenta' => $row['condicion_venta'],
                    'fecha' => $row['fecha'],
                    'numeroFactura' => $row['numero_factura'],
                    'subtotal' => number_format($row['subtotal'], 2, ',', '.'),
                    'ivaTotal' => number_format($row['iva'], 2, ',', '.'),
                    'totalGeneral' => number_format($row['total'], 2, ',', '.')
                ];

                // Draw simple fields if position exists (aplicar estilo global antes de escribir)
                foreach ($fieldMap as $key => $value) {
                    if (isset($basePos[$key]) && count($basePos[$key])>0) {
                        // take first
                        $pp = $basePos[$key][0];
                        $pdf->SetFont($fontToUse, $fontStyle, $fontSize);
                        $drawText($value, floatval($pp['x_pct']), floatval($pp['y_pct']));
                    }
                }

                // Handle item rows: find base positions for keys
                $itemKeys = ['cantidad','detalle','precio_unitario','total_item'];
                $itemBase = [];
                foreach ($itemKeys as $ik) {
                    if (isset($basePos[$ik]) && count($basePos[$ik])>0) {
                        $itemBase[$ik] = $basePos[$ik][0];
                    }
                }
                if (!empty($itemBase) && count($items)>0) {
                    $lineHeight = 6; // mm
                    foreach ($items as $idx => $it) {
                        $rowOffset = $idx * $lineHeight;
                        // for each item key, compute y + offset
                        foreach ($itemBase as $k => $pinfo) {
                            $xPct = floatval($pinfo['x_pct']);
                            $yPct = floatval($pinfo['y_pct']);
                            // convert base yPct to mm relative to targetHeight and add offset
                            $baseYmm = ($yPct / 100.0) * $targetHeight;
                            $yPosMm = $baseYmm + $rowOffset;
                            $yPosPct = ($yPosMm / $targetHeight) * 100.0;
                            $text = '';
                            if ($k === 'cantidad') $text = $it['cantidad'];
                            elseif ($k === 'detalle') $text = $it['detalle'];
                            elseif ($k === 'precio_unitario') $text = number_format($it['precio_unitario'], 2, ',', '.');
                            elseif ($k === 'total_item') $text = number_format(($it['cantidad'] * $it['precio_unitario']), 2, ',', '.');
                            // aplicar estilo global antes de imprimir cada celda de item
                            $pdf->SetFont($fontToUse, $fontStyle, $fontSize);
                            $drawText($text, $xPct, $yPosPct);
                        }
                    }
                }
            }
        }

        $modelo_cargado = true;
    } catch (Exception $e) {
        error_log('Error al cargar PDF modelo: ' . $e->getMessage());
        $modelo_cargado = false;
    }
} else {
    // No template: generar página simple con configuración del modelo
    
    // Determinar ancho de página basado en page_width_mm
    $pageWidth = floatval($modelo_config['page_width_mm']);
    if ($pageWidth <= 0) $pageWidth = 80; // Default
    
    // AddPage con tamaño personalizado si es distinto del A4
    if ($pageWidth !== 210) {
        // Para tiques (55/80mm) usar altura proporcional (A4 es 210x297)
        $pageHeight = 297 * ($pageWidth / 210);
        $pdf->AddPage('', [$pageWidth, $pageHeight]);
    } else {
        // A4 estándar
        $pdf->AddPage();
    }
    
    $fontToUse = $modelo_config['font_name'] ?: 'Helvetica';
    $fontSize = intval($modelo_config['font_size']) ?: 12;
    $fontStyle = '';
    if (!empty($modelo_config['font_bold'])) $fontStyle .= 'B';
    if (!empty($modelo_config['font_italic'])) $fontStyle .= 'I';
    if (!empty($modelo_config['font_underline'])) $fontStyle .= 'U';
    $pdf->SetFont($fontToUse, $fontStyle, $fontSize);
    $pdf->SetTextColor(0,0,0);
    
    // Ajustar márgenes según ancho
    $margin = $pageWidth > 100 ? 20 : 5;
    $colRight = $pageWidth > 100 ? 150 : ($pageWidth * 0.6);
    
    $pdf->SetXY($margin, $margin);
    $pdf->Write(0, $row['empresa_nombre']);
    $pdf->SetXY($margin, $margin + 8);
    $pdf->Write(0, $row['empresa_cuit']);
    $pdf->SetXY($colRight, $margin);
    $pdf->Write(0, $row['numero_factura']);
    $pdf->SetXY($colRight, $margin + 8);
    $pdf->Write(0, $row['fecha']);
    $pdf->SetXY($margin, $margin + 20);
    $pdf->Write(0, 'Cliente: ' . $row['cliente_nombre']);
    $pdf->SetXY($margin, $margin + 28);
    $pdf->Write(0, 'CUIT: ' . $row['cliente_cuit']);
    $pdf->SetXY($margin, $margin + 36);
    $pdf->Write(0, 'Domicilio: ' . $row['cliente_domicilio']);
    $pdf->SetXY($colRight, $margin + 80);
    $pdf->Write(0, 'Subtotal: $' . number_format($row['subtotal'], 2, ',', '.'));
    $pdf->SetXY($colRight, $margin + 88);
    $pdf->Write(0, 'IVA: $' . number_format($row['iva'], 2, ',', '.'));
    $pdf->SetXY($colRight, $margin + 96);
    // Para el total forzamos (además de estilos globales) que aparezca en negrita si no lo está
    $totalStyle = $fontStyle;
    if (strpos($totalStyle, 'B') === false) $totalStyle .= 'B';
    $pdf->SetFont($fontToUse, $totalStyle, $fontSize);
    $pdf->Write(0, 'Total: $' . number_format($row['total'], 2, ',', '.'));
}

// Output del PDF
$pdf->Output('I', 'factura_' . $row['numero_factura'] . '.pdf');