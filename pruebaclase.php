<?php
class Automovil{
    public $placa; 
    public $marca;
    public $modelo;
    public function __construct($placa, $marca, $modelo) {
        $this->placa = $placa;
        $this->marca = $marca;
        $this->modelo = $modelo;
    }

    public function imprimir() {
        echo($this->placa. $this->marca. $this->modelo);
    }
}

$auto1 = new Automovil();
$auto1-> placa="TAA111";
$auto1-> marca="Chevrolet";
$auto1-> modelo="corsa";

$auto1->imprimir();
$auto2 = new Automovil("");
?>