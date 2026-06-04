<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

class AuthController
{
    public static function procesar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $accion = $_POST['accion_auth'] ?? '';

        if ($accion === 'login') {
            self::login();
        } elseif ($accion === 'logout') {
            self::logout();
        }
    }

    private static function login()
    {
        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($usuario === '' || $password === '') {
            $_SESSION['login_error'] = 'Usuario y contraseña son obligatorios.';
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        $fila = UsuarioModel::autenticar($usuario, $password);
        if ($fila) {
            $_SESSION['usuario_id'] = $fila['id'];
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['rol'] = $fila['rol'];
            unset($_SESSION['login_error']);
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }
        } else {
            $_SESSION['login_error'] = 'Credenciales incorrectas.';
        }
        header('Location: index.php?accion=Nosotros');
        exit;
    }

    private static function logout()
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header('Location: index.php?accion=Nosotros');
        exit;
    }

    public static function estaLogueado()
    {
        return isset($_SESSION['usuario_id'], $_SESSION['rol']);
    }

    public static function esAdmin()
    {
        return self::estaLogueado() && $_SESSION['rol'] === 'ADMIN';
    }

    public static function esCliente()
    {
        return self::estaLogueado() && $_SESSION['rol'] === 'CLIENTE';
    }
}
