<?php 
include "conexion.php";

$cedula = $_GET['estcedula'];
$nombre = $_POST['estnombre'];
$apellido = $_POST['estapellido'];
$direccion = $_POST['estdireccion'];
$telefono = $_POST['esttelefono'];

$sqlUpdate = "UPDATE estudiantes SET 
                estnombre='$nombre',
                estapellido='$apellido',
                estdireccion='$direccion',
                esttelefono='$telefono'
              WHERE estcedula='$cedula'";

if ($conn -> query($sqlUpdate)) {
    echo json_encode("Se actualizó correctamente");
} else {
    echo json_encode("Error al actualizar: " . $sqlUpdate.$conn ->error);
}
?>