<?php
$servername = "localhost";
$username = "root";
$password = "Noraan007*";
$dbname = "invoice_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Si ya se está devolviendo JSON, devolverlo también aquí
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
    }
    die(json_encode(['success' => false, 'error' => 'Error de conexión: ' . $conn->connect_error]));
}
?>