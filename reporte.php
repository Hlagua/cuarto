<?php
require_once("fpdf186/fpdf.php");
require_once("models/conexion.php");

$sqlSelect="select*from estudiantes";

$result=$conn->query($sqlSelect);

$fpdf=new FPDF();

$fpdf->AddPage();
$fpdf->setTitle("estudiantes");
$fpdf->setFont("Arial","B",16);

$fpdf->Cell(0,10,"Reporte de Estudiantes",0,1,"C");

//un reporte heco con ireport se envie aca

$fpdf->Cell(40,10,"cedula");
$fpdf->Cell(40,10,"nombre");
$fpdf->Cell(0,10,"Apellido");

$fpdf->Ln();


while($row=$result->fetch_array()){
$cedula=$row["estcedula"];
$nombre=$row["estnombre"];
$apellido=$row["estapellido"];
$fpdf->cell(40,10,$cedula,1);//1 significa que va a sacr de la bd
$fpdf->cell(40,10,$nombre,1);
$fpdf->cell(40,10,$apellido,1);

$fpdf->Ln();


}




$fpdf->Output();

?>