<?php
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';

if (!AuthController::estaLogueado()) {
    header('Location: index.php?accion=Nosotros');
    exit;
}

$ventaId = (int) ($_GET['venta_id'] ?? ($_SESSION['ultima_venta_id'] ?? 0));
$datos = $ventaId ? VentaModel::obtenerVentaCompleta($ventaId) : null;

if (!$datos) {
    echo '<div class="alert alert-warning m-4">Venta no encontrada.</div>';
    return;
}

$venta = $datos['venta'];
$detalles = $datos['detalles'];
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <!-- Pantalla de Confirmación de Venta -->
    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" style="min-width:24px;">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
        <div class="ms-2">
            <strong>¡Compra confirmada!</strong> Su venta ha sido registrada con éxito en el sistema.
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            <h1 class="h4 mb-0">Factura de venta #<?= (int) $venta['id'] ?></h1>
        </div>
        <div class="card-body">
            <p><strong>Fecha:</strong> <?= htmlspecialchars($venta['fecha']) ?></p>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 12%;">ID Producto</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td><?= (int) $d['producto_id'] ?></td>
                            <td><?= htmlspecialchars($d['nombre']) ?></td>
                            <td><?= (int) $d['cantidad'] ?></td>
                            <td>$<?= number_format((float) $d['precio_unitario'], 2) ?></td>
                            <td>$<?= number_format((float) $d['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total general</th>
                        <th>$<?= number_format((float) $venta['total'], 2) ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="d-flex flex-wrap gap-2">
                <a href="reportes/factura_venta.php?venta_id=<?= (int) $venta['id'] ?>" class="btn btn-danger" target="_blank">
                    Descargar PDF (FPDF)
                </a>
                <a href="index.php?accion=Nosotros" class="btn btn-outline-secondary">Volver a la tienda</a>
            </div>
        </div>
    </div>
</div>
