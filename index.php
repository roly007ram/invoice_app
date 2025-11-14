<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: factura_.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
