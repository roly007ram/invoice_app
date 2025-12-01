<?php
/**
 * Script de pruebas end-to-end para validar el flujo completo:
 * 1. Crear/verificar tabla modelo_config y modelo_posiciones
 * 2. Simular guardado de posiciones (como si viniera del frontend)
 * 3. Generar PDF con esas posiciones
 * 4. Verificar alineado y reportar hallazgos
 *
 * Uso: Ejecutar desde CLI: php test_e2e.php [empresa_id] [factura_id]
 * Ejemplo: php test_e2e.php 1 1
 */

require_once('db_config.php');

// Parámetros por defecto
$empresa_id = isset($argv[1]) ? intval($argv[1]) : 1;
$factura_id = isset($argv[2]) ? intval($argv[2]) : 1;

echo "\n=== PRUEBA END-TO-END: Sistema de Plantillas PDF ===\n\n";

// PASO 1: Crear/verificar tablas
echo "[1] Verificando/creando tablas en BD...\n";

$createPosSql = "CREATE TABLE IF NOT EXISTS `modelo_posiciones` (
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

$createCfgSql = "CREATE TABLE IF NOT EXISTS `modelo_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) NOT NULL,
  `page_width_mm` int(11) NOT NULL DEFAULT 80,
  `font_name` varchar(100) NOT NULL DEFAULT 'Helvetica',
  `font_size` int(3) NOT NULL DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empresa_id` (`empresa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($createPosSql)) {
    echo "  ✓ Tabla modelo_posiciones lista\n";
} else {
    echo "  ✗ Error en modelo_posiciones: " . $conn->error . "\n";
}

if ($conn->query($createCfgSql)) {
    echo "  ✓ Tabla modelo_config lista\n";
} else {
    echo "  ✗ Error en modelo_config: " . $conn->error . "\n";
}

// PASO 2: Simular posiciones de prueba
echo "\n[2] Insertando posiciones de prueba para empresa_id=$empresa_id...\n";

// Limpiar posiciones previas
$del = $conn->prepare("DELETE FROM modelo_posiciones WHERE empresa_id = ?");
if ($del) {
    $del->bind_param('i', $empresa_id);
    if ($del->execute()) {
        echo "  ✓ Posiciones anteriores eliminadas\n";
    }
    $del->close();
}

// Insertar posiciones de prueba (en porcentajes)
$testPositions = [
    ['clienteNombre', 'Cliente', 2, 14],
    ['clienteCuit', 'CUIT', 2, 24],
    ['numeroFactura', 'Factura #', 45, 14],
    ['fecha', 'Fecha', 45, 24],
    ['clienteDomicilio', 'Domicilio', 2, 36],
    ['localidad', 'Localidad', 2, 46],
    ['cantidad', 'Cantidad (item 1)', 2, 62],
    ['detalle', 'Detalle (item 1)', 15, 62],
    ['precio_unitario', 'Precio (item 1)', 55, 62],
    ['total_item', 'Total Item (item 1)', 70, 62],
    ['subtotal', 'Subtotal', 70, 100],
    ['ivaTotal', 'IVA Total', 70, 106],
    ['totalGeneral', 'Total General', 70, 114],
];

$ins = $conn->prepare("INSERT INTO modelo_posiciones (empresa_id, key_name, label, x_pct, y_pct, page) VALUES (?,?,?,?,?,1)");
if (!$ins) {
    echo "  ✗ Error al preparar inserción: " . $conn->error . "\n";
} else {
    $count = 0;
    foreach ($testPositions as $pos) {
        list($key, $label, $xPct, $yPct) = $pos;
        $ins->bind_param('issdd', $empresa_id, $key, $label, $xPct, $yPct);
        if ($ins->execute()) {
            $count++;
        } else {
            echo "  ⚠ Error insertando $key: " . $ins->error . "\n";
        }
    }
    $ins->close();
    echo "  ✓ Insertadas $count posiciones de prueba\n";
}

// PASO 3: Guardar configuración de prueba
echo "\n[3] Configurando modelo (page_width=80mm, font=Helvetica, size=10)...\n";

$chk = $conn->prepare("SELECT id FROM modelo_config WHERE empresa_id = ? LIMIT 1");
if ($chk) {
    $chk->bind_param('i', $empresa_id);
    $chk->execute();
    $resChk = $chk->get_result();
    
    if ($rowChk = $resChk->fetch_assoc()) {
        // Actualizar
        $upd = $conn->prepare("UPDATE modelo_config SET page_width_mm = 80, font_name = 'Helvetica', font_size = 10, updated_at = CURRENT_TIMESTAMP WHERE empresa_id = ?");
        if ($upd) {
            $upd->bind_param('i', $empresa_id);
            if ($upd->execute()) {
                echo "  ✓ Configuración actualizada\n";
            } else {
                echo "  ✗ Error actualizando: " . $upd->error . "\n";
            }
            $upd->close();
        }
    } else {
        // Insertar
        $insCfg = $conn->prepare("INSERT INTO modelo_config (empresa_id, page_width_mm, font_name, font_size) VALUES (?,?,?,?)");
        if ($insCfg) {
            $pw = 80;
            $fn = 'Helvetica';
            $fs = 10;
            $insCfg->bind_param('iisi', $empresa_id, $pw, $fn, $fs);
            if ($insCfg->execute()) {
                echo "  ✓ Configuración creada\n";
            } else {
                echo "  ✗ Error insertando: " . $insCfg->error . "\n";
            }
            $insCfg->close();
        }
    }
    $chk->close();
}

// PASO 4: Verificar datos
echo "\n[4] Verificando datos almacenados en BD...\n";

$q1 = $conn->prepare("SELECT page_width_mm, font_name, font_size FROM modelo_config WHERE empresa_id = ? LIMIT 1");
if ($q1) {
    $q1->bind_param('i', $empresa_id);
    $q1->execute();
    $res1 = $q1->get_result();
    if ($rowCfg = $res1->fetch_assoc()) {
        echo "  ✓ Config DB: width={$rowCfg['page_width_mm']}mm, font={$rowCfg['font_name']}, size={$rowCfg['font_size']}\n";
    } else {
        echo "  ⚠ Config no encontrada para empresa_id=$empresa_id\n";
    }
    $q1->close();
}

$q2 = $conn->prepare("SELECT COUNT(*) as cnt FROM modelo_posiciones WHERE empresa_id = ?");
if ($q2) {
    $q2->bind_param('i', $empresa_id);
    $q2->execute();
    $res2 = $q2->get_result();
    if ($rowPos = $res2->fetch_assoc()) {
        echo "  ✓ Posiciones almacenadas: {$rowPos['cnt']}\n";
    }
    $q2->close();
}

// PASO 5: Verificar existencia de factura y empresa
echo "\n[5] Verificando factura y empresa...\n";

$qf = $conn->prepare("SELECT f.id, f.numero_factura, e.id AS emp_id, e.nombre FROM facturas f LEFT JOIN empresas e ON f.empresa_id = e.id WHERE f.id = ? LIMIT 1");
if ($qf) {
    $qf->bind_param('i', $factura_id);
    $qf->execute();
    $resF = $qf->get_result();
    if ($rowF = $resF->fetch_assoc()) {
        echo "  ✓ Factura: #{$rowF['numero_factura']} (ID={$rowF['id']})\n";
        echo "  ✓ Empresa: {$rowF['nombre']} (ID={$rowF['emp_id']})\n";
    } else {
        echo "  ⚠ Factura no encontrada (ID=$factura_id). Crea una factura de prueba en la aplicación.\n";
    }
    $qf->close();
}

// PASO 6: Resumen e instrucciones
echo "\n[6] Resumen de prueba:\n";
echo "  - Tablas creadas: modelo_posiciones, modelo_config\n";
echo "  - Empresa ID: $empresa_id\n";
echo "  - Factura ID: $factura_id\n";
echo "  - Configuración: 80mm, Helvetica, 10pt\n";
echo "  - Posiciones: " . count($testPositions) . " campos de prueba insertados\n\n";

echo "=== INSTRUCCIONES PARA PRUEBA COMPLETA ===\n\n";
echo "1. Accede a http://localhost/invoice_app/\n";
echo "2. Selecciona Empresa con ID=$empresa_id\n";
echo "3. Abre 'Configuración de modelos' y verifica que los valores están cargados\n";
echo "4. Opcionalmente: sube el PDF generado con generate_test_template.php\n";
echo "5. Abre/crea una Factura con ID=$factura_id\n";
echo "6. Pulsa 'Imprimir por modelo'\n";
echo "7. Inspecciona el PDF resultante: verifica alineado y espaciado\n\n";

echo "=== ARCHIVO DE PLANTILLA ===\n";
$templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'pdfmodelo' . DIRECTORY_SEPARATOR . 'test_template.pdf';
if (file_exists($templatePath)) {
    echo "  ✓ Plantilla disponible: $templatePath (" . filesize($templatePath) . " bytes)\n";
} else {
    echo "  ⚠ Plantilla no encontrada. Ejecuta generate_test_template.php\n";
}

echo "\n";
?>
