<?php
require_once __DIR__ . '/conexion.php';

class ProductoModel
{
    public static function listar($soloActivos = false)
    {
        global $conn;
        $sql = 'SELECT * FROM productos';
        if ($soloActivos) {
            $sql .= ' WHERE activo = 1';
        }
        $sql .= ' ORDER BY id DESC';
        
        $result = $conn->query($sql);
        $lista = [];
        if ($result && $result->num_rows > 0) {
            while ($fila = $result->fetch_assoc()) {
                $lista[] = $fila;
            }
        }
        return $lista;
    }

    public static function obtenerPorId($id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM productos WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $fila;
    }

    public static function guardar($nombre, $descripcion, $precio, $imagen, $stock = 0)
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO productos (nombre, descripcion, precio, imagen, stock) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('ssdsi', $nombre, $descripcion, $precio, $imagen, $stock);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function actualizar($id, $nombre, $descripcion, $precio, $imagen, $stock = 0, $activo = 1)
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ?, stock = ?, activo = ? WHERE id = ?');
        $stmt->bind_param('ssdsiii', $nombre, $descripcion, $precio, $imagen, $stock, $activo, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function estaFacturado($id)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT COUNT(*) AS total FROM detalle_venta WHERE producto_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return ((int) ($res['total'] ?? 0)) > 0;
    }

    public static function desactivar($id)
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE productos SET activo = 0 WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function eliminar($id)
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM productos WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
