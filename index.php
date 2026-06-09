<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


require_once "controllers/controller.php";
require_once "controllers/AuthController.php";
require_once "controllers/CarritoController.php";
require_once "controllers/ProductoController.php";
require_once "controllers/ResumenController.php";

AuthController::procesar();
CarritoController::procesar();
ProductoController::procesar();
ResumenController::procesar();

$mvc = new EnlacesPaginaController();
$mvc->plantilla();
?>