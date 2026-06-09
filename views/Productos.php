<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../controllers/ProductoController.php';

if (!AuthController::esAdmin()) {
    echo '<div class="alert alert-danger m-4">Acceso restringido a administradores.</div>';
    return;
}

$productos = ProductoModel::listar();
$editarId = isset($_GET['editar']) ? (int) $_GET['editar'] : 0;
$editar = $editarId ? ProductoModel::obtenerPorId($editarId) : null;
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h1 class="h3 mb-4" style="color:#781E24;">Administración de productos</h1>

    <?php if (!empty($_SESSION['producto_ok'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['producto_ok']) ?></div>
        <?php unset($_SESSION['producto_ok']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['producto_error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['producto_error']) ?></div>
        <?php unset($_SESSION['producto_error']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <?= $editar ? 'Editar producto' : 'Nuevo producto' ?>
                </div>
                <div class="card-body">
                    <form method="post" action="index.php?accion=Productos" enctype="multipart/form-data">
                        <input type="hidden" name="accion_producto" value="<?= $editar ? 'actualizar' : 'crear' ?>">
                        <?php if ($editar): ?>
                            <input type="hidden" name="id" value="<?= (int) $editar['id'] ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required
                                   value="<?= $editar ? htmlspecialchars($editar['nombre']) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" required><?= $editar ? htmlspecialchars($editar['descripcion']) : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" name="precio" step="0.01" min="0.01" class="form-control" required
                                   value="<?= $editar ? (float) $editar['precio'] : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock disponible</label>
                            <input type="number" name="stock" min="0" class="form-control" required
                                   value="<?= $editar ? (int) $editar['stock'] : '0' ?>">
                        </div>
                        <?php if ($editar): ?>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="activo" class="form-select">
                                    <option value="1" <?= (int)$editar['activo'] === 1 ? 'selected' : '' ?>>Activo</option>
                                    <option value="0" <?= (int)$editar['activo'] === 0 ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label">Imagen<?= $editar ? ' (opcional)' : '' ?></label>
                            <input type="file" name="imagen" class="form-control" accept="image/*" <?= $editar ? '' : '' ?>>
                            <?php if ($editar): ?>
                                <small class="text-muted">Actual: <?= htmlspecialchars($editar['imagen']) ?></small>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-danger"><?= $editar ? 'Guardar cambios' : 'Crear producto' ?></button>
                        <?php if ($editar): ?>
                            <a href="index.php?accion=Productos" class="btn btn-outline-secondary">Cancelar</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td><?= (int) $p['id'] ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars(ProductoController::rutaImagen($p['imagen'])) ?>"
                                         alt="" width="60" height="60" style="object-fit:cover">
                                </td>
                                <td><?= htmlspecialchars($p['nombre']) ?></td>
                                <td>$<?= number_format((float) $p['precio'], 2) ?></td>
                                <td><?= (int) $p['stock'] ?></td>
                                <td>
                                    <?php if ((int)$p['activo'] === 1): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="index.php?accion=Productos&editar=<?= (int) $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <form method="post" action="index.php?accion=Productos" class="d-inline"
                                          onsubmit="return confirm('¿Seguro que desea eliminar/desactivar este producto?');">
                                        <input type="hidden" name="accion_producto" value="eliminar">
                                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
