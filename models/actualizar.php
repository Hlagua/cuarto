<?php 
include "conexion.php";

$cedula = $_GET['cedula'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];

$sqlUpdate = "UPDATE estudiantes SET 
                nombre='$nombre',
                apellido='$apellido',
                direccion='$direccion',
                telefono='$telefono'
              WHERE cedula='$cedula'";

if ($conn -> query($sqlUpdate)) {
    echo json_encode("Se actualizó correctamente");
} else {
    echo json_encode("Error al actualizar: " . $sqlUpdate.$conn ->error);
}
?>