<?php
/**
 * Script de diagnóstico para verificar PDF generado
 * Analiza el PDF y reporta sobre:
 * - Presencia de texto esperado
 * - Problemas de escalado
 * - Sugerencias de ajuste
 *
 * Nota: Este script está pensado para revisión visual y manual.
 * Para análisis automatizado profundo de PDFs, se recomienda usar pdftotext o similar.
 */

require_once('db_config.php');

echo "\n=== DIAGNÓSTICO DE PDF GENERADO ===\n\n";

$factura_id = isset($argv[1]) ? intval($argv[1]) : 1;
$empresa_id = isset($argv[2]) ? intval($argv[2]) : 1;

echo "Factura ID: $factura_id\nEmpresa ID: $empresa_id\n\n";

// Obtener datos de la factura
$sql = "SELECT f.*, e.modelo_pdf, e.nombre FROM facturas f 
        LEFT JOIN empresas e ON f.empresa_id = e.id WHERE f.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $factura_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo "✗ Factura no encontrada.\n";
    exit(1);
}

echo "Factura: #{$row['numero_factura']}\n";
echo "Empresa: {$row['nombre']}\n";
echo "Modelo PDF: {$row['modelo_pdf']}\n\n";

// Verificar plantilla
$templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'pdfmodelo' . DIRECTORY_SEPARATOR . $row['modelo_pdf'];
if (!file_exists($templatePath)) {
    echo "⚠ Plantilla no encontrada en: $templatePath\n\n";
} else {
    echo "✓ Plantilla encontrada: " . filesize($templatePath) . " bytes\n\n";
}

// Obtener configuración
$cfg_q = $conn->prepare("SELECT page_width_mm, font_name, font_size FROM modelo_config WHERE empresa_id = ? LIMIT 1");
$cfg_q->bind_param('i', $empresa_id);
$cfg_q->execute();
$cfgRes = $cfg_q->get_result();
$cfg = ['page_width_mm' => 80, 'font_name' => 'Helvetica', 'font_size' => 10];
if ($cfgRow = $cfgRes->fetch_assoc()) {
    $cfg = $cfgRow;
}
$cfg_q->close();

echo "[CONFIG]\n";
echo "  Ancho: {$cfg['page_width_mm']}mm\n";
echo "  Fuente: {$cfg['font_name']}\n";
echo "  Tamaño: {$cfg['font_size']}pt\n\n";

// Obtener posiciones
$pos_q = $conn->prepare("SELECT key_name, label, x_pct, y_pct, page FROM modelo_posiciones WHERE empresa_id = ? ORDER BY y_pct ASC");
$pos_q->bind_param('i', $empresa_id);
$pos_q->execute();
$posRes = $pos_q->get_result();

echo "[POSICIONES]\n";
$positions = [];
while ($pRow = $posRes->fetch_assoc()) {
    $positions[] = $pRow;
    printf("  %-25s | X:%6.2f%% Y:%6.2f%% | Página: %d\n",
        $pRow['label'],
        $pRow['x_pct'],
        $pRow['y_pct'],
        $pRow['page']
    );
}
$pos_q->close();

echo "\n[DATOS DE FACTURA]\n";
printf("  Cliente: %s\n", $row['cliente_nombre']);
printf("  CUIT: %s\n", $row['cliente_cuit']);
printf("  Factura: %s\n", $row['numero_factura']);
printf("  Fecha: %s\n", $row['fecha']);
printf("  Subtotal: \$%.2f\n", $row['subtotal']);
printf("  IVA: \$%.2f\n", $row['iva']);
printf("  Total: \$%.2f\n\n", $row['total']);

// Obtener items
$itq = $conn->prepare("SELECT cantidad, detalle, precio_unitario FROM items WHERE factura_id = ? ORDER BY id ASC LIMIT 6");
$itq->bind_param('i', $factura_id);
$itq->execute();
$itRes = $itq->get_result();

echo "[ITEMS]\n";
$items = [];
while ($itRow = $itRes->fetch_assoc()) {
    $items[] = $itRow;
    printf("  %s x %s = \$%.2f\n",
        $itRow['cantidad'],
        $itRow['detalle'],
        $itRow['cantidad'] * $itRow['precio_unitario']
    );
}
$itq->close();

echo "\n[CONSEJOS PARA AJUSTE MANUAL]\n\n";

echo "1. ESCALADO:\n";
echo "   - Si el PDF se ve muy pequeño o muy grande, ajusta page_width_mm\n";
echo "   - Valores: 55 (tique), 80 (tique estándar), 210 (A4)\n\n";

echo "2. FUENTE:\n";
echo "   - Si el texto no se ve clara, prueba: Helvetica, Arial, Times, Courier\n";
echo "   - Tamaño: 8-12pt es común para PDFs de factura\n\n";

echo "3. POSICIONES (X%, Y%):\n";
echo "   - 0,0 = esquina superior izquierda\n";
echo "   - 100,100 = esquina inferior derecha\n";
echo "   - Rango: 0-100 para ambos\n";
echo "   - Adjust en incrementos de 1-2% para movimientos visibles\n\n";

echo "4. VERIFICACIÓN VISUAL:\n";
echo "   a) Genera el PDF desde el botón 'Imprimir por modelo'\n";
echo "   b) Abre el PDF en un lector (navegador, Acrobat)\n";
echo "   c) Compara visualmente con la plantilla\n";
echo "   d) Si está desalineado:\n";
echo "      - Si está muy a la izquierda: aumenta X%\n";
echo "      - Si está muy arriba: aumenta Y%\n";
echo "      - Ajusta en pasos de 2-5% si es desalineamiento grueso\n\n";

echo "5. ITEMS EN FILAS:\n";
echo "   - El offset de filas es fijo en 6mm por línea\n";
echo "   - Si necesitas otro espaciado, modifica imprimir_factura_pdf.php línea ~150\n\n";

echo "=== FIN DEL DIAGNÓSTICO ===\n\n";
?>
