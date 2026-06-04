<?php
session_start();

require_once "controllers/controller.php";
require_once "controllers/AuthController.php";
require_once "controllers/CarritoController.php";
require_once "controllers/ProductoController.php";

AuthController::procesar();
CarritoController::procesar();
ProductoController::procesar();

$mvc = new EnlacesPaginaController();
$mvc->plantilla();
?>