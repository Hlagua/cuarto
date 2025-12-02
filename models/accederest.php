<?php
include "conexion.php";
$cedula=$_GET['estcedula'];
$sqlSelect="select * from estudiantes where estcedula=$cedula";
$respuesta=$conn->query($sqlSelect);
$resultado=array();
if($respuesta->num_rows>=0){
    while ($fila=$respuesta->fetch_array()) {
        array_push($resultado,$fila);
    }
}else{
    echo('No hay estudiantes');
}
echo(json_encode($resultado));
?>