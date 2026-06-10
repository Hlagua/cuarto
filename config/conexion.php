<?php
// Configuración de conexión a la base de datos (MySQL / Railway)
$host = 'kodama.proxy.rlwy.net';
$user = 'root'; 
$pass = 'pzhkIBEHOQnMtGvEROfMaAOAHVdfhwFF';
$db   = 'railway'; 
$port = 43206;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
