<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


// --- SOLUCIÓN DE PERMISOS AUTOMÁTICA ---
$ruta_imagenes = __DIR__ . '/imagenes/productos/';
if (!file_exists($ruta_imagenes)) {
    mkdir($ruta_imagenes, 0775, true);
}
// Forzamos los permisos en cada carga de la página para asegurar que PHP pueda escribir
chmod($ruta_imagenes, 0775);


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