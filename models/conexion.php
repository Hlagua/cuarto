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


?>