<?php
$host = 'localhost';
$user = 'root'; 
$pass = '';
$db   = 'cuarto'; // Este es el nombre estándar que asigna Railway
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


// Redirección al archivo de configuración en /config para cumplir con la arquitectura MVC requerida.
require_once __DIR__ . '/../config/conexion.php';
?>