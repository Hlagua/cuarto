<?php
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/AuthController.php';

class CarritoController
{
    public static function procesar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (!AuthController::estaLogueado()) {
            $_SESSION['carrito_error'] = 'Debe iniciar sesión para usar el carrito.';
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $accion = $_POST['accion_carrito'] ?? '';

        switch ($accion) {
            case 'agregar':
                self::agregar();
                break;
            case 'actualizar':
                self::actualizar();
                break;
            case 'eliminar':
                self::eliminar();
                break;
            case 'finalizar':
                self::finalizar();
                break;
        }
    }

    private static function agregar()
    {
        $id = (int) ($_POST['producto_id'] ?? 0);
        $cantidad = max(1, (int) ($_POST['cantidad'] ?? 1));
        $producto = ProductoModel::obtenerPorId($id);

        if (!$producto) {
            $_SESSION['carrito_error'] = 'Producto no encontrado.';
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        if ((int)$producto['activo'] !== 1) {
            $_SESSION['carrito_error'] = 'El producto no está disponible.';
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        $cantExistente = isset($_SESSION['carrito'][$id]) ? $_SESSION['carrito'][$id]['cantidad'] : 0;
        $totalRequerido = $cantExistente + $cantidad;

        if ($totalRequerido > (int)$producto['stock']) {
            $_SESSION['carrito_error'] = 'No hay suficiente stock para "' . $producto['nombre'] . '". Stock disponible: ' . $producto['stock'] . ' (ya tienes ' . $cantExistente . ' en el carrito).';
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] = $totalRequerido;
        } else {
            $_SESSION['carrito'][$id] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => (float) $producto['precio'],
                'imagen' => $producto['imagen'],
                'cantidad' => $cantidad,
            ];
        }

        $_SESSION['carrito_ok'] = 'Producto agregado al carrito.';
        header('Location: index.php?accion=Nosotros');
        exit;
    }

    private static function actualizar()
    {
        $cantidades = $_POST['cantidad'] ?? [];
        $advertencias = [];
        foreach ($cantidades as $id => $cant) {
            $id = (int) $id;
            $cant = max(1, (int) $cant);

            $producto = ProductoModel::obtenerPorId($id);
            if (!$producto || (int)$producto['activo'] !== 1) {
                unset($_SESSION['carrito'][$id]);
                $advertencias[] = 'Un producto ya no está activo y fue removido de tu carrito.';
                continue;
            }

            if ($cant > (int)$producto['stock']) {
                $cant = (int)$producto['stock'];
                $advertencias[] = 'La cantidad de "' . $producto['nombre'] . '" se limitó a ' . $cant . ' por falta de stock.';
            }

            if (isset($_SESSION['carrito'][$id])) {
                $_SESSION['carrito'][$id]['cantidad'] = $cant;
            }
        }

        if (!empty($advertencias)) {
            $_SESSION['carrito_error'] = implode('<br>', $advertencias);
        } else {
            $_SESSION['carrito_ok'] = 'Cantidades actualizadas.';
        }

        header('Location: index.php?accion=Nosotros');
        exit;
    }

    private static function eliminar()
    {
        $id = (int) ($_POST['producto_id'] ?? 0);
        unset($_SESSION['carrito'][$id]);
        $_SESSION['carrito_ok'] = 'Producto eliminado del carrito.';
        header('Location: index.php?accion=Nosotros');
        exit;
    }

    private static function finalizar()
    {
        if (AuthController::esAdmin()) {
            $_SESSION['carrito_error'] = 'Solo los clientes pueden finalizar compras.';
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        if (empty($_SESSION['carrito'])) {
            $_SESSION['carrito_error'] = 'El carrito está vacío.';
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        // Validación final de stock antes de pasar al modelo
        foreach ($_SESSION['carrito'] as $id => $item) {
            $producto = ProductoModel::obtenerPorId($id);
            if (!$producto || (int)$producto['activo'] !== 1) {
                $_SESSION['carrito_error'] = 'El producto "' . $item['nombre'] . '" ya no está disponible. Actualiza tu carrito.';
                header('Location: index.php?accion=Nosotros');
                exit;
            }
            if ((int)$item['cantidad'] > (int)$producto['stock']) {
                $_SESSION['carrito_error'] = 'Stock insuficiente para el producto "' . $producto['nombre'] . '". Stock disponible: ' . $producto['stock'] . '. Por favor, actualiza la cantidad en tu carrito.';
                header('Location: index.php?accion=Nosotros');
                exit;
            }
        }

        $items = array_values($_SESSION['carrito']);
        $ventaId = VentaModel::registrarVenta($items);

        if (!$ventaId) {
            // El mensaje detallado ya viene de VentaModel en $_SESSION['carrito_error']
            if (empty($_SESSION['carrito_error'])) {
                $_SESSION['carrito_error'] = 'No se pudo registrar la venta.';
            }
            header('Location: index.php?accion=Nosotros');
            exit;
        }

        $_SESSION['carrito'] = [];
        $_SESSION['ultima_venta_id'] = $ventaId;
        header('Location: index.php?accion=Factura&venta_id=' . $ventaId);
        exit;
    }
}
