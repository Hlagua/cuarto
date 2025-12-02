<?php
require_once "models/model.php";

class EnlacesPaginaController{
    public static function plantilla(){
        include "views/template.php";
    }

    public static function EnlacesPaginaController(){
        if (isset($_GET["accion"]) && !empty($_GET["accion"])) {
            $enlacesController = $_GET["accion"];
        } else {
            $enlacesController = "Inicio.php";
        }
        $var = new enlacesPagina();
        $respuesta = $var->enlacesPaginasModel($enlacesController);
        include $respuesta;
    }
}
?>