<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../controllers/ProductoController.php';

$logueado = AuthController::estaLogueado();
$esCliente = AuthController::esCliente();
$productos = ($logueado && $esCliente) ? ProductoModel::listar(true) : [];
$carrito = $_SESSION['carrito'] ?? [];
?>
<link rel="stylesheet" href="css/style.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="uta-container">
    <h1 class="uta-title">Nosotros — Tienda FISEI</h1>

    <?php if (!$logueado): ?>
        <div class="row justify-content-center mt-4">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <strong>Iniciar sesión</strong>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['login_error'])): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['login_error']) ?></div>
                            <?php unset($_SESSION['login_error']); ?>
                        <?php endif; ?>
                        <form method="post" action="index.php?accion=Nosotros">
                            <input type="hidden" name="accion_auth" value="login">
                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" name="usuario" class="form-control" required autocomplete="username">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" required autocomplete="current-password">
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Ingresar</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3 style="color: #781E24;">Nuestra Identidad</h3>
            <p class="uta-subtitle">Facultad de Ingeniería en Sistemas, Electrónica e Industrial — UTA</p>
            <p>Inicie sesión como cliente para ver el catálogo y realizar compras.</p>
        </div>

    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <p class="mb-0">Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>
                (<?= htmlspecialchars($_SESSION['rol']) ?>)</p>
            <form method="post" action="index.php?accion=Nosotros" class="d-inline">
                <input type="hidden" name="accion_auth" value="logout">
                <button type="submit" class="btn btn-outline-secondary btn-sm">Cerrar sesión</button>
            </form>
        </div>

        <?php if (!empty($_SESSION['carrito_ok'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['carrito_ok']) ?></div>
            <?php unset($_SESSION['carrito_ok']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['carrito_error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['carrito_error']) ?></div>
            <?php unset($_SESSION['carrito_error']); ?>
        <?php endif; ?>

        <h2 class="h4 mt-4 mb-3">Productos disponibles</h2>
        <div class="row g-4">
            <?php if (empty($productos)): ?>
                <?php if (AuthController::esAdmin()): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            Ha iniciado sesión como <strong>Administrador</strong>. Para gestionar la venta de productos, por favor diríjase al menú <a href="?accion=Productos" class="alert-link">Productos</a>.
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-12"><p class="text-muted">No hay productos disponibles para la compra en este momento.</p></div>
                <?php endif; ?>
            <?php else: ?>
                <?php foreach ($productos as $p): ?>
                    <?php $img = ProductoController::rutaImagen($p['imagen']); ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nombre']) ?>" style="height:200px;object-fit:cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>
                                <p class="card-text flex-grow-1"><?= htmlspecialchars($p['descripcion']) ?></p>
                                <p class="fw-bold text-danger fs-5 mb-2">$<?= number_format((float) $p['precio'], 2) ?></p>
                                
                                <p class="mb-2 small">
                                    <strong>Stock:</strong> 
                                    <?php if ((int)$p['stock'] > 0): ?>
                                        <span class="badge bg-success"><?= (int)$p['stock'] ?> disponibles</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Agotado</span>
                                    <?php endif; ?>
                                </p>

                                <?php if ($esCliente): ?>
                                    <?php if ((int)$p['stock'] > 0): ?>
                                        <form method="post" action="index.php?accion=Nosotros" class="mt-auto">
                                            <input type="hidden" name="accion_carrito" value="agregar">
                                            <input type="hidden" name="producto_id" value="<?= (int) $p['id'] ?>">
                                            <div class="input-group">
                                                <input type="number" name="cantidad" value="1" min="1" max="<?= (int)$p['stock'] ?>" class="form-control" style="max-width:80px">
                                                <button type="submit" class="btn btn-danger">Agregar al carrito</button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <div class="mt-auto">
                                            <button class="btn btn-secondary w-100" disabled>Agotado</button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($esCliente): ?>
            <hr class="my-5">
            <h2 class="h4 mb-3">Carrito de compras</h2>
            <?php if (empty($carrito)): ?>
                <p class="text-muted">Su carrito está vacío.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="tablaCarrito">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Precio unit.</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carrito as $item): 
                                $prodDB = ProductoModel::obtenerPorId($item['id']);
                                $maxStock = $prodDB ? (int)$prodDB['stock'] : 999;
                            ?>
                                <tr data-precio="<?= (float) $item['precio'] ?>">
                                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                                    <td class="precio-unit">$<?= number_format((float) $item['precio'], 2) ?></td>
                                    <td>
                                        <input type="number" name="cantidad[<?= (int) $item['id'] ?>]"
                                               form="formActualizarCarrito"
                                               value="<?= (int) $item['cantidad'] ?>" min="1" max="<?= $maxStock ?>"
                                               class="form-control cantidad-input" style="max-width:90px">
                                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Máx. disponible: <?= $maxStock ?></small>
                                    </td>
                                    <td class="subtotal-linea">$0.00</td>
                                    <td>
                                        <form method="post" action="index.php?accion=Nosotros" class="d-inline">
                                            <input type="hidden" name="accion_carrito" value="eliminar">
                                            <input type="hidden" name="producto_id" value="<?= (int) $item['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total general</th>
                                <th colspan="2" id="totalGeneral">$0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <form method="post" action="index.php?accion=Nosotros" id="formActualizarCarrito" class="d-none">
                    <input type="hidden" name="accion_carrito" value="actualizar">
                </form>

                <form method="post" action="index.php?accion=Nosotros" class="mt-3">
                    <input type="hidden" name="accion_carrito" value="finalizar">
                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('¿Confirmar la compra?');">
                        Comprar
                    </button>
                </form>
                <script src="js/carrito.js"></script>
            <?php endif; ?>
        <?php endif; ?>

    <?php endif; ?>
</div>
