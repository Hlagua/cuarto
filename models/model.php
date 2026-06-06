<?php
Class enlacesPagina{
    public function enlacesPaginasModel($enlacesModel) {
        if($enlacesModel == "Inicio" ||
           $enlacesModel == "Servicios" ||
           $enlacesModel == "Nosotros" ||
           $enlacesModel == "Contactanos" ||
           $enlacesModel == "Productos" ||
           $enlacesModel == "Factura" ||
           $enlacesModel == "ResumenFacturacion") {
            $modulo = "views/".$enlacesModel.".php";
        } else {
            $modulo = "views/Inicio.php";   
        }
        return $modulo;
    }
}
?>