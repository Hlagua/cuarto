<?php
require_once __DIR__ . '/conexion.php';

class UsuarioModel
{
    public static function autenticar($usuario, $password)
    {
        global $conn;
        $stmt = $conn->prepare('SELECT id, usuario, password, rol FROM usuarios WHERE usuario = ? LIMIT 1');
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $fila = $result->fetch_assoc();
        $stmt->close();

        if ($fila && password_verify($password, $fila['password'])) {
            return $fila;
        }
        return false;
    }
}
