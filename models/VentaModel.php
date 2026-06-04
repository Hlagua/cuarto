<?php
require_once __DIR__ . '/conexion.php';

class VentaModel
{
    public static function registrarVenta($items)
    {
        global $conn;
        $total = 0;
        foreach ($items as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare('INSERT INTO ventas (fecha, total) VALUES (NOW(), ?)');
            $stmt->bind_param('d', $total);
            $stmt->execute();
            $ventaId = (int) $conn->insert_id;
            $stmt->close();

            $stmtDet = $conn->prepare(
                'INSERT INTO detalle_venta (venta_id, producto_id, cantidad, subtotal) VALUES (?, ?, ?, ?)'
            );

            foreach ($items as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $stmtDet->bind_param(
                    'iiid',
                    $ventaId,
                    $item['id'],
                    $item['cantidad'],
                    $subtotal
                );
                $stmtDet->execute();
            }
            $stmtDet->close();
            $conn->commit();
            return $ventaId;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public static function obtenerVentaCompleta($ventaId)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM ventas WHERE id = ?');
        $stmt->bind_param('i', $ventaId);
        $stmt->execute();
        $venta = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$venta) {
            return null;
        }

        $sql = 'SELECT dv.*, p.nombre, p.precio AS precio_unitario
                FROM detalle_venta dv
                INNER JOIN productos p ON p.id = dv.producto_id
                WHERE dv.venta_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $ventaId);
        $stmt->execute();
        $detalles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return ['venta' => $venta, 'detalles' => $detalles];
    }
}
