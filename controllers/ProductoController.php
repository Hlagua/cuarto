<?php
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/AuthController.php';

class ProductoController
{
    private static $dirImagenes = 'imagenes/productos/';

    public static function procesar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (!AuthController::esAdmin()) {
            $_SESSION['producto_error'] = 'Acceso denegado.';
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        $accion = $_POST['accion_producto'] ?? '';

        switch ($accion) {
            case 'crear':
                self::crear();
                break;
            case 'actualizar':
                self::actualizar();
                break;
            case 'eliminar':
                self::eliminar();
                break;
        }
    }

    private static function subirImagen($campo = 'imagen')
    {
        if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $dir = __DIR__ . '/../' . self::$dirImagenes;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $permitidas, true)) {
            return false;
        }

        $nombre = uniqid('prod_', true) . '.' . $ext;
        $destino = $dir . $nombre;
        if (move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
            return $nombre;
        }
        return false;
    }

    private static function crear()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = (float) ($_POST['precio'] ?? 0);
        $imagen = self::subirImagen();

        if ($nombre === '' || $descripcion === '' || $precio <= 0) {
            $_SESSION['producto_error'] = 'Complete todos los campos obligatorios.';
            header('Location: index.php?accion=Productos');
            exit;
        }

        if ($imagen === false) {
            $_SESSION['producto_error'] = 'Imagen no válida.';
            header('Location: index.php?accion=Productos');
            exit;
        }

        if ($imagen === null) {
            $imagen = 'sin_imagen.jpg';
        }

        ProductoModel::guardar($nombre, $descripcion, $precio, $imagen);
        $_SESSION['producto_ok'] = 'Producto creado correctamente.';
        header('Location: index.php?accion=Productos');
        exit;
    }

    private static function actualizar()
    {
        $id = (int) ($_POST['id'] ?? 0);
        $producto = ProductoModel::obtenerPorId($id);
        if (!$producto) {
            $_SESSION['producto_error'] = 'Producto no encontrado.';
            header('Location: index.php?accion=Productos');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = (float) ($_POST['precio'] ?? 0);
        $imagen = self::subirImagen();
        if ($imagen === false) {
            $_SESSION['producto_error'] = 'Imagen no válida.';
            header('Location: index.php?accion=Productos&editar=' . $id);
            exit;
        }
        if ($imagen === null) {
            $imagen = $producto['imagen'];
        }

        ProductoModel::actualizar($id, $nombre, $descripcion, $precio, $imagen);
        $_SESSION['producto_ok'] = 'Producto actualizado.';
        header('Location: index.php?accion=Productos');
        exit;
    }

    private static function eliminar()
    {
        $id = (int) ($_POST['id'] ?? 0);
        ProductoModel::eliminar($id);
        $_SESSION['producto_ok'] = 'Producto eliminado.';
        header('Location: index.php?accion=Productos');
        exit;
    }

    public static function rutaImagen($archivo)
    {
        $ruta = self::$dirImagenes . $archivo;
        $full = __DIR__ . '/../' . $ruta;
        if (file_exists($full)) {
            return $ruta;
        }
        $banner = __DIR__ . '/../imagenes/banner.png';
        if (file_exists($banner)) {
            return 'imagenes/banner.png';
        }
        return $ruta;
    }
}
