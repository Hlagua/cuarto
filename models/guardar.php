<?php
include_once ("conexion.php");

$cedula = $_POST['estcedula'];
$nombre = $_POST['estnombre'];
$apellido = $_POST['estapellido'];
$direccion = $_POST['estdireccion'];
$telefono = $_POST['esttelefono'];

$sqlInsert = "insert into estudiantes values ('$cedula','$nombre','$apellido','$direccion','$telefono')";
if($conn->query($sqlInsert)==true){
    echo json_encode("Se inserto al estudiante");
}else{
    echo json_encode("Error ".$sqlInsert.$conn->error);
}
$conn->close();
?>