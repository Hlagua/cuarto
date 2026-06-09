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
        
        // CORRECCIÓN 1: Crear la carpeta con permisos 0775 y forzarlos explícitamente
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
            chmod($dir, 0775); 
        }

        $ext = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $permitidas, true)) {
            return false;
        }

        $nombre = uniqid('prod_', true) . '.' . $ext;
        $destino = $dir . $nombre;
        
        // CORRECCIÓN 2: Mover el archivo y darle permisos de lectura/escritura inmediatamente
        if (move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
            chmod($destino, 0664);
            return $nombre;
        }
        
        return false;
    }

    private static function crear()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = (float) ($_POST['precio'] ?? 0);
        $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 0;
        $imagen = self::subirImagen();

        // Validaciones del servidor
        if ($nombre === '' || $descripcion === '') {
            $_SESSION['producto_error'] = 'El nombre y la descripción son obligatorios.';
            header('Location: index.php?accion=Productos');
            exit;
        }

        if ($precio <= 0) {
            $_SESSION['producto_error'] = 'El precio debe ser un número positivo mayor que cero.';
            header('Location: index.php?accion=Productos');
            exit;
        }

        if ($stock < 0) {
            $_SESSION['producto_error'] = 'El stock inicial no puede ser negativo.';
            header('Location: index.php?accion=Productos');
            exit;
        }

        if ($imagen === false) {
            $_SESSION['producto_error'] = 'Archivo de imagen no válido. Use formatos permitidos (jpg, png, gif, webp).';
            header('Location: index.php?accion=Productos');
            exit;
        }

        if ($imagen === null) {
            $imagen = 'sin_imagen.jpg';
        }

        ProductoModel::guardar($nombre, $descripcion, $precio, $imagen, $stock);
        $_SESSION['producto_ok'] = 'Producto creado correctamente con stock inicial de ' . $stock . '.';
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
        $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 0;
        $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 1;
        $imagen = self::subirImagen();

        // Validaciones del servidor
        if ($nombre === '' || $descripcion === '') {
            $_SESSION['producto_error'] = 'El nombre y la descripción son obligatorios.';
            header('Location: index.php?accion=Productos&editar=' . $id);
            exit;
        }

        if ($precio <= 0) {
            $_SESSION['producto_error'] = 'El precio debe ser un número positivo mayor que cero.';
            header('Location: index.php?accion=Productos&editar=' . $id);
            exit;
        }

        if ($stock < 0) {
            $_SESSION['producto_error'] = 'El stock no puede ser negativo.';
            header('Location: index.php?accion=Productos&editar=' . $id);
            exit;
        }
        
        if ($imagen === false) {
            $_SESSION['producto_error'] = 'Archivo de imagen no válido.';
            header('Location: index.php?accion=Productos&editar=' . $id);
            exit;
        }
        if ($imagen === null) {
            $imagen = $producto['imagen'];
        }

        ProductoModel::actualizar($id, $nombre, $descripcion, $precio, $imagen, $stock, $activo);
        $_SESSION['producto_ok'] = 'Producto actualizado correctamente.';
        header('Location: index.php?accion=Productos');
        exit;
    }

    private static function eliminar()
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['producto_error'] = 'ID de producto no válido.';
            header('Location: index.php?accion=Productos');
            exit;
        }

        if (ProductoModel::estaFacturado($id)) {
            ProductoModel::desactivar($id);
            $_SESSION['producto_ok'] = 'El producto ya ha sido facturado. Se ha desactivado (no aparecerá en el catálogo) para proteger el historial de ventas.';
        } else {
            ProductoModel::eliminar($id);
            $_SESSION['producto_ok'] = 'Producto eliminado físicamente de la base de datos ya que no poseía facturas asociadas.';
        }
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