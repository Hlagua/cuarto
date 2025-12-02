<?php   

$numero1 = 3;
echo $numero1;
print_r ($numero1);
var_dump ($numero1);   

echo("<br>");

$cadena = "Hola Mundo";
echo $cadena;
print_r ($cadena);
var_dump ($cadena);

echo("<br>");

$booleano = true;
echo $booleano;
print_r ($booleano);
var_dump ($booleano);

echo("<br>");

$vector=array("Rojo","Verde","Azul");
echo $vector[0];
print_r ($vector);
var_dump ($vector);

// arreglo de propiedades

echo("<br>");

$colores=array("color1"=>"Rojo","color2"=>"Verde","color3"=>"Azul");
echo $colores["color2"];

// objeto

echo("<br>");

$objeto=(object)["color1"=>"Rojo","color2"=>"Verde","color3"=>"Azul"];
echo $objeto->color3;

echo("<br>");

function saludar()
{
    echo ("Hola a todos");
}

function saludo($llegada) {
    echo $llegada;
}

function Saludo1($Despedida) 
{
    return $Despedida;
}

saludar();
echo("<br>");
saludo("Buenos días a todos");
echo(Saludo1("Buenas noches"));

$automovil1 = (object)["Placa"=>"XYZ123","Marca"=>"Toyota","Modelo"=>"Corolla"];
$automovil2 = (object)["Placa"=>"ABC456","Marca"=>"Honda","Modelo"=>"Civic"];

function imprimir($automovil) {

    echo("<br>");
    echo "Placa: " . $automovil->Placa . "<br>";
    echo "Marca: " . $automovil->Marca . "<br>";
    echo "Modelo: " . $automovil->Modelo . "<br>";
    
}

imprimir($automovil1);
imprimir($automovil2);

$num1=8;
$num2=9;
if ($num1 > $num2) {

    echo("Num 1 es el mayor ".$num1);
}
else
{

    echo("Num dos es el mayor ".$num2);

}

echo("<br>");

for($i=0; $i <= 5; $i++) {

    echo($i);

}

?>