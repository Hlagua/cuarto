<?php
$host = 'kodama.proxy.rlwy.net';
$user = 'root'; 
$pass = 'pzhkIBEHOQnMtGvEROfMaAOAHVdfhwFF';
$db   = 'railway'; // Este es el nombre estándar que asigna Railway
$port = 43206;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "¡Conectado exitosamente a la nube!";


?>