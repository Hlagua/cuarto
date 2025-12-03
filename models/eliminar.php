<?php
include_once("conexion.php");

$cedula = $_POST["estcedula"];
$sqlDelete = "delete from estudiantes where estcedula = '$cedula'";
if ($conn -> query($sqlDelete) === true) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'error' => $conn -> error]);
}
?>