<?php
require_once __DIR__ . '/conexion.php';

class ProductoModel
{
    public static function listar()
    {
        global $conn;
        $result = $conn->query('SELECT * FROM productos ORDER BY id DESC');
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

    public static function guardar($nombre, $descripcion, $precio, $imagen)
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssds', $nombre, $descripcion, $precio, $imagen);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function actualizar($id, $nombre, $descripcion, $precio, $imagen)
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ? WHERE id = ?');
        $stmt->bind_param('ssdsi', $nombre, $descripcion, $precio, $imagen, $id);
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
