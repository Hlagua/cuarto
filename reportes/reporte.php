<?php
require_once("../fpdf186/fpdf.php");
require_once("../models/conexion.php");

$sqlSelect="select*from estudiantes";
$result=$conn->query($sqlSelect);

$fpdf=new FPDF();

$fpdf->AddPage();
$fpdf->setTitle("estudiantes");
$fpdf->setFont("Arial","B",16);

$fpdf->Cell(0,10,"Reporte de los Estudiantes",0,1,"C");

$fpdf->Cell(40,10,"cedula");
$fpdf->Cell(40,10,"nombre");
$fpdf->Cell(40,10,"Apellido");
$fpdf->Cell(40,10,"Telefono");
$fpdf->Cell(40,10,"Direccion");

$fpdf->Ln();
while($row=$result->fetch_array()){
$cedula=$row["cedula"];
$nombre=$row["nombre"];
$apellido=$row["apellido"];
$telefono=$row["telefono"];
$direccion=$row["direccion"];
$fpdf->cell(40,10,$cedula,1);//1 significa que va a sacr de la bd
$fpdf->cell(40,10,$nombre,1);
$fpdf->cell(40,10,$apellido,1);
$fpdf->cell(40,10,$telefono,1);
$fpdf->cell(40,10,$direccion,1);
$fpdf->Ln();
}
$fpdf->Output();
?>