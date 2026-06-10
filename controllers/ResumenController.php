<?php
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/AuthController.php';

class ResumenController
{
    public static function procesar()
    {
        // No-op ya que no se permite el uso de AJAX/API asíncronas de acuerdo a las restricciones.
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
