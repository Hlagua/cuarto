<?php
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/AuthController.php';

class ResumenController
{
    public static function procesar()
    {
        // Intercepta solicitudes API para actualización en vivo por AJAX
        if (isset($_GET['accion']) && $_GET['accion'] === 'ResumenFacturacion' && isset($_GET['api'])) {
            if (!AuthController::estaLogueado()) {
                header('HTTP/1.1 403 Forbidden');
                header('Content-Type: application/json');
                echo json_encode(['error' => 'No autorizado']);
                exit;
            }
            header('Content-Type: application/json');
            echo json_encode([
                'stats' => self::obtenerStats(),
                'recientes' => self::obtenerVentasRecientes(10)
            ]);
            exit;
        }
    }

    public static function obtenerStats()
    {
        if (!AuthController::estaLogueado()) {
            return [
                'total_facturas' => 0,
                'monto_total' => 0.0,
                'promedio_factura' => 0.0
            ];
        }
        return VentaModel::obtenerResumenFacturacion();
    }

    public static function obtenerVentasRecientes($limite = 10)
    {
        if (!AuthController::estaLogueado()) {
            return [];
        }
        return VentaModel::obtenerVentasRecientes($limite);
    }
}
