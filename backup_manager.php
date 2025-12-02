<?php
set_time_limit(300);
ini_set('memory_limit', '256M');

// --- Basic Security & Action Routing ---
$allowed_actions = ['list', 'create', 'download', 'delete'];
$action = $_GET['action'] ?? 'list'; // Default action

if (!in_array($action, $allowed_actions)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
    exit;
}

$backup_dir = __DIR__ . '/backups';

// --- Action Handler ---
switch ($action) {
    case 'list':
        handle_list($backup_dir);
        break;
    case 'create':
        handle_create($backup_dir);
        break;
    case 'download':
        handle_download($backup_dir, $_GET['file'] ?? '');
        break;
    case 'delete':
        handle_delete($backup_dir, $_GET['file'] ?? '');
        break;
}

// --- Function Implementations ---

function return_json_error($message, $details = '') {
    if(ob_get_level()) ob_get_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $message, 'details' => $details]);
    exit;
}

function handle_list($backup_dir) {
    header('Content-Type: application/json');
    $backups = [];
    if (is_dir($backup_dir)) {
        $files = array_diff(scandir($backup_dir, SCANDIR_SORT_DESCENDING), ['.', '..']);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $file_path = $backup_dir . '/' . $file;
                $backups[] = [
                    'filename' => $file,
                    'size' => filesize($file_path),
                    'date' => filemtime($file_path)
                ];
            }
        }
    }
    echo json_encode(['success' => true, 'backups' => $backups]);
}

function handle_download($backup_dir, $filename) {
    if (empty($filename) || !preg_match('/^invoice_app_backup_\d{8}_\d{6}\.zip$/', $filename)) {
        http_response_code(400);
        die("Nombre de archivo inválido.");
    }
    
    $file_path = realpath($backup_dir . '/' . $filename);

    if ($file_path === false || strpos($file_path, realpath($backup_dir)) !== 0) {
        http_response_code(403);
        die("Acceso denegado.");
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    flush(); 
    readfile($file_path);
    exit;
}

function handle_delete($backup_dir, $filename) {
    header('Content-Type: application/json');
    if (empty($filename) || !preg_match('/^invoice_app_backup_\d{8}_\d{6}\.zip$/', $filename)) {
        return_json_error('Nombre de archivo inválido.');
    }

    $file_path = realpath($backup_dir . '/' . $filename);

    if ($file_path === false || strpos($file_path, realpath($backup_dir)) !== 0) {
        return_json_error('Acceso denegado.');
    }

    if (@unlink($file_path)) {
        echo json_encode(['success' => true, 'message' => 'Copia de seguridad eliminada.']);
    } else {
        return_json_error('Error al eliminar el archivo. Verifique los permisos.');
    }
}

function handle_create($backup_dir) {
    header('Content-Type: application/json');
    
    // --- Configuración ---
    $mysqldump_path = ''; // Dejar vacío para autodetección

    // 1. Obtener config de la DB
    if (!file_exists('db_config.php')) return_json_error('El archivo de configuración db_config.php no existe.');
    require_once('db_config.php');

    // 2. Autodetectar mysqldump
    if (empty($mysqldump_path)) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $mysqldump_path = file_exists('C:\\xampp\\mysql\\bin\\mysqldump.exe') ? 'C:\\xampp\\mysql\\bin\\mysqldump.exe' : 'mysqldump';
        } else {
            $mysqldump_path = 'mysqldump';
        }
    }

    @exec('"' . $mysqldump_path . '" --version', $version_output, $return_code);
    if ($return_code !== 0) return_json_error('No se pudo ejecutar `mysqldump`.');

    // 3. Crear volcado SQL
    if (!is_dir($backup_dir)) {
        if (!@mkdir($backup_dir, 0755, true)) return_json_error('No se pudo crear el directorio de backups.');
    }

    $timestamp = date('Ymd_His');
    $sql_file = $backup_dir . '/db_backup_' . $timestamp . '.sql';
    $db_pass_param = !empty($password) ? '--password=' . escapeshellarg($password) : '';
    $dump_command = sprintf('"%s" -u%s %s -h%s %s > "%s"', $mysqldump_path, escapeshellarg($username), $db_pass_param, escapeshellarg($servername), escapeshellarg($dbname), $sql_file);

    @exec($dump_command, $dump_output, $dump_return_code);

    if ($dump_return_code !== 0 || !file_exists($sql_file) || filesize($sql_file) === 0) {
        $error_details = "Código de retorno: $dump_return_code. Salida: " . implode("\n", $dump_output);
        if (file_exists($sql_file)) @unlink($sql_file);
        return_json_error('Error al crear el volcado de la base de datos.', $error_details);
    }

    // 4. Crear archivo ZIP
    if (!class_exists('ZipArchive')) {
        @unlink($sql_file);
        return_json_error('La extensión ZipArchive de PHP no está habilitada.');
    }

    $zip_file_path = $backup_dir . '/invoice_app_backup_' . $timestamp . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($zip_file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        @unlink($sql_file);
        return_json_error('No se pudo crear el archivo ZIP.');
    }

    // 5. Añadir archivos del proyecto
    $source_path = __DIR__;
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source_path, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($source_path) + 1);
        if (strpos($filePath, $backup_dir) === 0 || strpos($relativePath, '.git') === 0) continue;
        if ($file->isDir()) $zip->addEmptyDir($relativePath);
        else $zip->addFile($filePath, $relativePath);
    }

    // 6. Finalizar ZIP
    $zip->addFile($sql_file, basename($sql_file));
    $zip->close();
    @unlink($sql_file);

    // 7. Responder con éxito
    echo json_encode(['success' => true, 'message' => 'Copia de seguridad completada exitosamente.', 'details' => 'Archivo creado: ' . basename($zip_file_path)]);
}
?>
