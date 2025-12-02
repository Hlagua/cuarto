<?php
Class enlacesPagina{
    public function enlacesPaginasModel($enlacesModel) {
        if($enlacesModel == "Inicio" ||
           $enlacesModel == "Servicios" ||
           $enlacesModel == "Nosotros" ||
           $enlacesModel == "Contactanos") {
            $modulo = "views/".$enlacesModel.".php";
        } else {
            $modulo = "views/Inicio.php";   
        }
        return $modulo;
    }
}
?>