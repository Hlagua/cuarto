<?php
include_once ("conexion.php");

$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];

$sqlInsert = "insert into estudiantes values ('$cedula','$nombre','$apellido','$telefono','$direccion')";
if($conn->query($sqlInsert)==true){
    echo json_encode("Se inserto al estudiante");
}else{
    echo json_encode("Error ".$sqlInsert.$conn->error);
}
$conn->close();
?>