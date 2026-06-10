<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cuartob";
$mysql=new mysqli($servername, $username, $password, $database);//este toca instanciar
$conn= mysqli_connect($servername, $username, $password, $database);// este es un metodo estatico
if(!$conn){
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}   

?>
