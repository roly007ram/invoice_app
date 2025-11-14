<?php
session_start();
require_once('db_config.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $sql = "SELECT id, usuario, nombre FROM usuarios WHERE usuario=? AND password=SHA2(?, 256) LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['nombre'] = $row['nombre'];
        header('Location: factura_.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width:400px;margin-top:80px;">
    <div class="card">
        <div class="card-header text-center"><h4>Iniciar Sesión</h4></div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Usuario</label>
                    <input type="text" name="usuario" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
