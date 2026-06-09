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

            $stmtStock = $conn->prepare('SELECT stock, activo, nombre FROM productos WHERE id = ? FOR UPDATE');
            $stmtUpdateStock = $conn->prepare('UPDATE productos SET stock = stock - ? WHERE id = ?');

            foreach ($items as $item) {
                $stmtStock->bind_param('i', $item['id']);
                $stmtStock->execute();
                $res = $stmtStock->get_result()->fetch_assoc();

                if (!$res) {
                    throw new Exception('Producto ID ' . $item['id'] . ' no encontrado.');
                }
                if ((int)$res['activo'] !== 1) {
                    throw new Exception('El producto "' . $res['nombre'] . '" no está activo/disponible.');
                }
                if ((int)$res['stock'] < (int)$item['cantidad']) {
                    throw new Exception('Stock insuficiente para el producto "' . $res['nombre'] . '". Disponibles: ' . $res['stock'] . ', solicitados: ' . $item['cantidad']);
                }

                $subtotal = $item['precio'] * $item['cantidad'];
                $stmtDet->bind_param(
                    'iiid',
                    $ventaId,
                    $item['id'],
                    $item['cantidad'],
                    $subtotal
                );
                $stmtDet->execute();

                $stmtUpdateStock->bind_param('ii', $item['cantidad'], $item['id']);
                $stmtUpdateStock->execute();
            }

            $stmtStock->close();
            $stmtUpdateStock->close();
            $stmtDet->close();
            $conn->commit();
            return $ventaId;
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['carrito_error'] = 'Error en facturación: ' . $e->getMessage();
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

    public static function obtenerResumenFacturacion()
    {
        global $conn;
        $sql = 'SELECT COUNT(*) AS total_facturas, IFNULL(SUM(total), 0) AS monto_total, IFNULL(AVG(total), 0) AS promedio_factura FROM ventas';
        $resultado = $conn->query($sql);
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return [
                'total_facturas' => (int) $fila['total_facturas'],
                'monto_total' => (float) $fila['monto_total'],
                'promedio_factura' => (float) $fila['promedio_factura']
            ];
        }
        return [
            'total_facturas' => 0,
            'monto_total' => 0.0,
            'promedio_factura' => 0.0
        ];
    }

    public static function obtenerVentasRecientes($limite = 10)
    {
        global $conn;
        $sql = 'SELECT id, fecha, total FROM ventas ORDER BY id DESC LIMIT ?';
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $limite);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $res;
        }
        return [];
    }
}
