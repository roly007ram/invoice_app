<?php
session_start();
require_once('db_config.php');
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    if ($usuario && $nombre && $password && $password === $password2) {
        $sql = "INSERT INTO usuarios (usuario, password, nombre) VALUES (?, SHA2(?, 256), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $usuario, $password, $nombre);
        if ($stmt->execute()) {
            $mensaje = '<div class="alert alert-success">Usuario creado correctamente.</div>';
        } else {
            $mensaje = '<div class="alert alert-danger">Error: El usuario ya existe.</div>';
        }
        $stmt->close();
    } else {
        $mensaje = '<div class="alert alert-danger">Complete todos los campos y asegúrese que las contraseñas coincidan.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width:400px;margin-top:80px;">
    <div class="card">
        <div class="card-header text-center"><h4>Crear Usuario</h4></div>
        <div class="card-body">
            <?php echo $mensaje; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Repetir Contraseña</label>
                    <input type="password" name="password2" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Crear Usuario</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
