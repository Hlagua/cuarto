<?php
$servername = "localhost:3306";
$dbname = "cuarto";
$username = "root";
$password = "";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

?>