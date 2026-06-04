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
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h1 class="h4 mb-0">Factura de venta #<?= (int) $venta['id'] ?></h1>
        </div>
        <div class="card-body">
            <p><strong>Fecha:</strong> <?= htmlspecialchars($venta['fecha']) ?></p>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['nombre']) ?></td>
                            <td><?= (int) $d['cantidad'] ?></td>
                            <td>$<?= number_format((float) $d['precio_unitario'], 2) ?></td>
                            <td>$<?= number_format((float) $d['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total general</th>
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
